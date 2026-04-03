<?php



class StudentModel extends Model
{
    protected $table = 'students';

    public function getAllStudents()
    {
        $sql = "SELECT * FROM " . $this->table;
        $dataStudent = $this->db->fetchAll($sql);
        return $dataStudent;
    }

    public function insertStudent($mssv, $name, $gender, $birthday, $cccd, $email, $phone, $address, $password)
    {
        $sql = "INSERT INTO " . $this->table . " (mssv, name, gender, birthday, cccd, email, phone,address, password) 
        VALUES (:mssv, :name, :gender, :birthday, :cccd, :email, :phone,:address, :password)";
        $params = [
            ':mssv' => $mssv,
            ':name' => $name,
            ':gender' => $gender,
            ':birthday' => $birthday,
            ':cccd' => $cccd,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':password' => md5($password)
        ];

        $result = $this->db->insert($sql, $params);
        return $result;
    }

    function updateStudent($id, $mssv, $name, $gender, $birthday, $cccd, $email, $phone, $address)
    {
        $sql = "UPDATE " . $this->table . " SET 
            mssv = :mssv, 
            name = :name, 
            gender = :gender, 
            birthday = :birthday, 
            cccd = :cccd, 
            email = :email, 
            phone = :phone, 
            address = :address 
        WHERE id = :id";
        $params = [
            ':mssv' => $mssv,
            ':name' => $name,
            ':gender' => $gender,
            ':birthday' => $birthday,
            ':cccd' => $cccd,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':id' => $id
        ];
        $result = $this->db->update($sql, $params);
        return $result;

    }

    public function deleteStudent($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $params = [
            ':id' => $id
        ];
        $result = $this->db->delete($sql, $params);

    }

    function getStudent($limit, $offset)
    {
        $sql = "SELECT * FROM " . $this->table . " LIMIT " . $limit . " OFFSET " . $offset;
        $dataStudent = $this->db->fetchAll($sql);
        return $dataStudent;
    }

    function getStudentById($id)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $dataStudent = $this->db->fetchOne($sql, ['id' => $id]);
        return $dataStudent;
    }

    function search($keyword, $limit, $offset)
    {

        $sql = "SELECT * FROM " . $this->table . " WHERE mssv LIKE :mssv OR name LIKE :name LIMIT :limit OFFSET :limit OFFSET :offset";

        $searchTerm = "%" . $keyword . "%";
        $params = [
            ':mssv' => $searchTerm,
            ':name' => $searchTerm,
            ':limit' => $limit,
            ':offset' => $offset,
        ];
        $dataStudent = $this->db->fetchAll($sql, $params);
        return $dataStudent;
    }
}