<?php

class StudentModel
{
    public function __construct(private readonly mysqli $db)
    {
    }

    public function getAllowedCourses(): array
    {
        return ['D16CNTT', 'D17CNTT', 'D18CNTT'];
    }

    public function all(): array
    {
        $result = $this->db->query('SELECT * FROM students ORDER BY id DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function filtered(string $search = '', string $class = ''): array
    {
        $students = $this->all();
        $allowedCourses = $this->getAllowedCourses();

        if ($search !== '') {
            $keyword = mb_strtolower($search);
            $students = array_filter($students, static function (array $student) use ($keyword): bool {
                return mb_stripos($student['mssv'], $keyword) !== false
                    || mb_stripos($student['fullname'], $keyword) !== false
                    || mb_stripos($student['phone'], $keyword) !== false
                    || mb_stripos($student['email'], $keyword) !== false;
            });
        }

        if ($class !== '' && in_array($class, $allowedCourses, true)) {
            $students = array_filter($students, static fn(array $student): bool => $student['class'] === $class);
        }

        return array_values($students);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $student;
    }

    public function existsByMssv(string $mssv, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT id FROM students WHERE mssv = ? AND id != ? LIMIT 1');
            $stmt->bind_param('si', $mssv, $excludeId);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM students WHERE mssv = ? LIMIT 1');
            $stmt->bind_param('s', $mssv);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO students (mssv, fullname, birthday, gender, phone, email, class, address)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssssssss',
            $data['mssv'],
            $data['fullname'],
            $data['birthday'],
            $data['gender'],
            $data['phone'],
            $data['email'],
            $data['class'],
            $data['address']
        );
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE students
             SET mssv = ?, fullname = ?, birthday = ?, gender = ?, phone = ?, email = ?, class = ?, address = ?
             WHERE id = ?'
        );
        $stmt->bind_param(
            'ssssssssi',
            $data['mssv'],
            $data['fullname'],
            $data['birthday'],
            $data['gender'],
            $data['phone'],
            $data['email'],
            $data['class'],
            $data['address'],
            $id
        );
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function importCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return ['imported' => 0, 'skipped' => 0, 'error' => 'error_file'];
        }

        $rowIndex = 0;
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowIndex++;

            if ($rowIndex === 1 && strtolower(trim($row[0] ?? '')) === 'mssv') {
                continue;
            }

            if (count($row) < 7) {
                $skipped++;
                continue;
            }

            $student = [
                'mssv' => trim($row[0] ?? ''),
                'fullname' => trim($row[1] ?? ''),
                'birthday' => trim($row[2] ?? ''),
                'gender' => trim($row[3] ?? ''),
                'phone' => trim($row[4] ?? ''),
                'email' => trim($row[5] ?? ''),
                'address' => trim($row[6] ?? ''),
            ];

            $validation = $this->validate($student, false);
            if ($validation['error'] !== null) {
                $skipped++;
                continue;
            }

            $student['class'] = $validation['class'];

            if ($this->existsByMssv($student['mssv'])) {
                $skipped++;
                continue;
            }

            if ($this->create($student)) {
                $imported++;
            } else {
                $skipped++;
            }
        }

        fclose($handle);

        return ['imported' => $imported, 'skipped' => $skipped, 'error' => null];
    }

    public function validate(array $data, bool $requireClass = false, ?int $excludeId = null): array
    {
        $mssv = trim($data['mssv'] ?? '');
        $fullname = trim($data['fullname'] ?? '');
        $birthday = trim($data['birthday'] ?? '');
        $gender = trim($data['gender'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $email = trim($data['email'] ?? '');
        $address = trim($data['address'] ?? '');
        $class = trim($data['class'] ?? '');

        if (!preg_match('/^\d{8}$/', $mssv)) {
            return ['error' => 'MSSV phải là đúng 8 chữ số.', 'class' => null];
        }

        if ($fullname === '' || $birthday === '' || $gender === '' || $phone === '' || $email === '' || $address === '') {
            return ['error' => 'Vui lòng nhập đầy đủ thông tin.', 'class' => null];
        }

        if ($requireClass) {
            if (!in_array($class, $this->getAllowedCourses(), true)) {
                return ['error' => 'Lớp không hợp lệ.', 'class' => null];
            }
        } else {
            $class = getClassFromMssv($mssv);
            if ($class === null) {
                return ['error' => 'Năm trong MSSV không hợp lệ.', 'class' => null];
            }
        }

        if ($this->existsByMssv($mssv, $excludeId)) {
            return ['error' => 'MSSV đã tồn tại.', 'class' => null];
        }

        return ['error' => null, 'class' => $class];
    }
}
