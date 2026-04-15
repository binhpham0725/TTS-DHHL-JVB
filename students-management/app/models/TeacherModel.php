<?php

class TeacherModel
{
    public function __construct(private readonly mysqli $db)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, password FROM teacher WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $teacher = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $teacher;
    }

    public function verifyPassword(array $teacher, string $plainPassword): bool
    {
        $storedPassword = (string)($teacher['password'] ?? '');

        if ($storedPassword !== '' && password_verify($plainPassword, $storedPassword)) {
            if (password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
                $this->updatePasswordHash((int)$teacher['id'], password_hash($plainPassword, PASSWORD_DEFAULT));
            }

            return true;
        }

        // Hỗ trợ dữ liệu cũ đang lưu plain text; nếu đăng nhập đúng thì tự nâng cấp lên hash.
        if ($storedPassword !== '' && hash_equals($storedPassword, $plainPassword)) {
            $this->updatePasswordHash((int)$teacher['id'], password_hash($plainPassword, PASSWORD_DEFAULT));
            return true;
        }

        return false;
    }

    public function updatePasswordHash(int $id, string $passwordHash): void
    {
        $stmt = $this->db->prepare('UPDATE teacher SET password = ? WHERE id = ?');
        $stmt->bind_param('si', $passwordHash, $id);
        $stmt->execute();
        $stmt->close();
    }
}
