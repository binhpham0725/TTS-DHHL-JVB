<?php

class StudentModel
{
    private const IMPORT_ERROR_FILE = 'error_file';

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
        // Phiên bản hiện tại lấy toàn bộ danh sách rồi lọc trong PHP; cách này đơn giản nhưng chưa tối ưu cho dữ liệu lớn.
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

    public function existsByEmail(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT id FROM students WHERE email = ? AND id != ? LIMIT 1');
            $stmt->bind_param('si', $email, $excludeId);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM students WHERE email = ? LIMIT 1');
            $stmt->bind_param('s', $email);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function create(array $data): bool
    {
        $birthday = $this->normalizeNullableValue($data['birthday'] ?? '');
        $gender = trim((string)($data['gender'] ?? '')) === '' ? 'Khác' : trim((string)$data['gender']);
        $phone = trim((string)($data['phone'] ?? ''));
        $email = $this->normalizeNullableValue($data['email'] ?? '');
        $address = $this->normalizeNullableValue($data['address'] ?? '');

        $stmt = $this->db->prepare(
            'INSERT INTO students (mssv, fullname, birthday, gender, phone, email, class, address)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssssssss',
            $data['mssv'],
            $data['fullname'],
            $birthday,
            $gender,
            $phone,
            $email,
            $data['class'],
            $address
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
        $delimiter = $this->detectCsvDelimiter($path);
        $handle = fopen($path, 'r');

        if (!$handle) {
            return ['imported' => 0, 'skipped' => 0, 'error' => self::IMPORT_ERROR_FILE, 'reason' => null];
        }

        $rowIndex = 0;
        $imported = 0;
        $skipped = 0;
        $firstReason = null;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowIndex++;
            $row = array_map([$this, 'sanitizeCsvValue'], $row);

            if ($this->isEmptyCsvRow($row)) {
                continue;
            }

            // Bỏ qua dòng tiêu đề nếu file CSV có header ở dòng đầu tiên.
            if ($rowIndex === 1 && $this->isStudentCsvHeader($row)) {
                continue;
            }

            if (count($row) < 7) {
                $skipped++;
                $firstReason ??= 'File CSV chưa đúng 7 cột theo định dạng yêu cầu.';
                continue;
            }

            $hasClassColumn = count($row) >= 8;
            $student = $this->normalizeImportedStudent([
                'mssv' => trim((string)($row[0] ?? '')),
                'fullname' => trim((string)($row[1] ?? '')),
                'birthday' => trim((string)($row[2] ?? '')),
                'gender' => trim((string)($row[3] ?? '')),
                'phone' => trim((string)($row[4] ?? '')),
                'email' => trim((string)($row[5] ?? '')),
                // Cho phép import lại file export 8 cột: mssv, fullname, birthday, gender, phone, email, class, address.
                'address' => trim((string)($hasClassColumn ? ($row[7] ?? '') : ($row[6] ?? ''))),
            ]);

            $validation = $this->validate($student, false);
            if ($validation['error'] !== null) {
                $skipped++;
                $firstReason ??= $validation['error'];
                continue;
            }

            // Trường class không lấy trực tiếp từ CSV mà suy ra từ 4 số đầu của MSSV.
            $student['class'] = $validation['class'];

            if ($this->existsByMssv($student['mssv'])) {
                $skipped++;
                $firstReason ??= 'MSSV đã tồn tại trong hệ thống.';
                continue;
            }

            if ($this->existsByEmail($student['email'])) {
                $skipped++;
                $firstReason ??= 'Email đã tồn tại trong hệ thống.';
                continue;
            }

            try {
                if ($this->create($student)) {
                    $imported++;
                } else {
                    $skipped++;
                    $firstReason ??= 'Có ít nhất một dòng dữ liệu không hợp lệ.';
                }
            } catch (Throwable $exception) {
                // Một dòng lỗi không nên làm hỏng toàn bộ lượt import CSV.
                $skipped++;
                $firstReason ??= 'Có ít nhất một dòng dữ liệu không hợp lệ hoặc bị trùng.';
            }
        }

        fclose($handle);

        return ['imported' => $imported, 'skipped' => $skipped, 'error' => null, 'reason' => $firstReason];
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
            // Khi thêm mới hoặc import, hệ thống tự xác định khóa học từ năm nhập học nằm trong MSSV.
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

    public function validateCreateForm(array $data): array
    {
        $mssv = trim($data['mssv'] ?? '');
        $fullname = trim($data['fullname'] ?? '');
        $birthday = trim($data['birthday'] ?? '');
        $gender = trim($data['gender'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $email = trim($data['email'] ?? '');
        $address = trim($data['address'] ?? '');

        $errors = [];
        $class = null;

        if ($mssv === '') {
            $errors['mssv'] = 'Vui lòng nhập MSSV.';
        } elseif (!preg_match('/^\d{8}$/', $mssv)) {
            $errors['mssv'] = 'MSSV phải gồm đúng 8 chữ số.';
        } else {
            $class = getClassFromMssv($mssv);
            if ($class === null) {
                $errors['mssv'] = '4 số đầu của MSSV chưa thuộc nhóm lớp đang hỗ trợ.';
            } elseif ($this->existsByMssv($mssv)) {
                $errors['mssv'] = 'MSSV đã tồn tại.';
            }
        }

        if ($fullname === '') {
            $errors['fullname'] = 'Vui lòng nhập họ và tên.';
        }

        if ($birthday === '') {
            $errors['birthday'] = 'Vui lòng chọn ngày sinh.';
        } else {
            $birthdayError = $this->validateCreateBirthday($birthday);
            if ($birthdayError !== null) {
                $errors['birthday'] = $birthdayError;
            }
        }

        if ($gender === '') {
            $errors['gender'] = 'Vui lòng chọn giới tính.';
        } elseif (!in_array($gender, ['Nam', 'Nữ', 'Khác'], true)) {
            $errors['gender'] = 'Giới tính không hợp lệ.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Vui lòng nhập số điện thoại.';
        } elseif (!preg_match('/^0\d{9}$/', $phone)) {
            $errors['phone'] = 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0.';
        }

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } elseif ($this->existsByEmail($email)) {
            $errors['email'] = 'Email đã tồn tại.';
        }

        if ($address === '') {
            $errors['address'] = 'Vui lòng nhập địa chỉ.';
        }

        return [
            'error' => $errors === [] ? null : reset($errors),
            'errors' => $errors,
            'class' => $errors === [] ? $class : null,
        ];
    }

    private function validateCreateBirthday(string $birthday): ?string
    {
        if ($birthday === '') {
            return 'Vui lòng chọn ngày sinh.';
        }

        $date = DateTime::createFromFormat('Y-m-d', $birthday);
        $lastErrors = DateTime::getLastErrors();
        $hasDateErrors = is_array($lastErrors)
            && (($lastErrors['warning_count'] ?? 0) > 0 || ($lastErrors['error_count'] ?? 0) > 0);

        if (!$date instanceof DateTime || $hasDateErrors || $date->format('Y-m-d') !== $birthday) {
            return 'Ngày sinh không hợp lệ.';
        }

        $today = new DateTime('today');
        if ($date > $today) {
            return 'Ngày sinh không được lớn hơn ngày hiện tại.';
        }

        return null;
    }

    private function normalizeNullableValue(string|null $value): ?string
    {
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function detectCsvDelimiter(string $path): string
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return ',';
        }

        $firstLine = fgets($handle) ?: '';
        fclose($handle);

        return substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
    }

    private function sanitizeCsvValue(string|null $value): string
    {
        return trim(str_replace("\xEF\xBB\xBF", '', (string)$value));
    }

    private function isEmptyCsvRow(array $row): bool
    {
        foreach ($row as $value) {
            if ($this->sanitizeCsvValue((string)$value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function isStudentCsvHeader(array $row): bool
    {
        $firstColumn = mb_strtolower($row[0] ?? '');
        return in_array($firstColumn, ['mssv', 'mã số sinh viên', 'ma so sinh vien'], true);
    }

    private function normalizeImportedStudent(array $student): array
    {
        $student['birthday'] = $this->normalizeBirthday($student['birthday']);
        $student['gender'] = $this->normalizeGender($student['gender']);
        $student['phone'] = preg_replace('/\s+/', '', $student['phone']) ?? $student['phone'];

        return $student;
    }

    private function normalizeBirthday(string $birthday): string
    {
        $birthday = trim($birthday);
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d'];

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $birthday);
            if ($date instanceof DateTime && $date->format($format) === $birthday) {
                return $date->format('Y-m-d');
            }
        }

        return $birthday;
    }

    private function normalizeGender(string $gender): string
    {
        $normalized = mb_strtolower(trim($gender));

        return match ($normalized) {
            'nam', 'male', 'm', '1' => 'Nam',
            'nữ', 'nu', 'female', 'f', '0' => 'Nữ',
            'khác', 'khac', 'other', '3' => 'Khác',
            default => $gender,
        };
    }
}
