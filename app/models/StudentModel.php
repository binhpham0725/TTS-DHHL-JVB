<?php

class StudentModel extends Model
{
    protected $table = 'students';

    public function getAllStudents()
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table}");
    }

    public function countAll($keyword = '')
    {
        if ($keyword) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE mssv LIKE :kw OR name LIKE :kw OR email LIKE :kw";
            return $this->db->fetch($sql, [':kw' => "%$keyword%"])['total'] ?? 0;
        }
        return $this->db->fetch("SELECT COUNT(*) as total FROM {$this->table}")['total'] ?? 0;
    }

    public function getStudent($limit, $offset, $keyword = '')
    {
        $limit  = (int)$limit;
        $offset = (int)$offset;
        if ($keyword) {
            $sql = "SELECT * FROM {$this->table} WHERE mssv LIKE :kw OR name LIKE :kw OR email LIKE :kw LIMIT $limit OFFSET $offset";
            return $this->db->fetchAll($sql, [':kw' => "%$keyword%"]);
        }
        return $this->db->fetchAll("SELECT * FROM {$this->table} LIMIT $limit OFFSET $offset");
    }

    public function getStudentById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", [':id' => $id]);
    }

    public function insertStudent($mssv, $name, $gender, $birthday, $cccd, $email, $phone, $address, $password)
    {
        $sql = "INSERT INTO {$this->table} (mssv, name, gender, birthday, cccd, email, phone, address, password)
                VALUES (:mssv, :name, :gender, :birthday, :cccd, :email, :phone, :address, :password)";
        return $this->db->insert($sql, [
            ':mssv'     => $mssv,
            ':name'     => $name,
            ':gender'   => $gender,
            ':birthday' => $birthday,
            ':cccd'     => $cccd,
            ':email'    => $email,
            ':phone'    => $phone,
            ':address'  => $address,
            ':password' => md5($password),
        ]);
    }

    public function updateStudent($id, $mssv, $name, $gender, $birthday, $cccd, $email, $phone, $address)
    {
        $sql = "UPDATE {$this->table}
                SET mssv=:mssv, name=:name, gender=:gender, birthday=:birthday,
                    cccd=:cccd, email=:email, phone=:phone, address=:address
                WHERE id=:id";
        return $this->db->update($sql, [
            ':mssv'     => $mssv,
            ':name'     => $name,
            ':gender'   => $gender,
            ':birthday' => $birthday,
            ':cccd'     => $cccd,
            ':email'    => $email,
            ':phone'    => $phone,
            ':address'  => $address,
            ':id'       => $id,
        ]);
    }

    public function deleteById($id)
    {
        return $this->db->delete("DELETE FROM {$this->table} WHERE id = :id", [':id' => $id]);
    }
}
