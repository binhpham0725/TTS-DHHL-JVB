<?php

class TeacherModel
{
    public function __construct(private readonly mysqli $db)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, password FROM Teacher WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $teacher = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $teacher;
    }
}
