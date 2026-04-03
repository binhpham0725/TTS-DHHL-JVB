<?php

class AuthModel extends Model
{
    protected $tableAdmin = 'admins';
    protected $tableStudent = 'students'; // Đã sửa lỗi chính tả 'tacble'

    public function login($email, $password, $role) {
        $tableName = ($role == 'admin') ? $this->tableAdmin : $this->tableStudent;
        $sql = "SELECT * FROM " . $tableName . " WHERE email = :email AND password = :password";
        $dataUser = $this->db->fetchOne($sql, [
            'email' => $email,
            'password' => $password
        ]);

        return $dataUser;
    }
}