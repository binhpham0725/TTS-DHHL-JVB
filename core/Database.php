<?php

class Database
{
    private $__conn;

    public function __construct()
    {
        global $db_config;
        $instance = Connection::getInstance($db_config);

        $this->__conn = $instance;
    }



    public function update($sql, $params = [])
    {
        $stmt = $this->__conn->prepare($sql);
        $status = $stmt->execute($params);
        return $status;
    }
    public function delete($sql, $params = [])
    {
        $stmt = $this->__conn->prepare($sql);
        $status = $stmt->execute($params);
        return $status;
    }



public function fetchAll($sql, $params = [])
{
    try {
        $stmt = $this->__conn->prepare($sql);
        $result = $stmt->execute($params);

        if ($result) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    } catch (PDOException $e) {

        die("Lỗi SQL: " . $e->getMessage() . " - Câu lệnh: " . $sql);
    }
}


    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->__conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 6. Hàm COUNT: Đếm số dòng (Dùng cho phân trang)
    public function getCount($sql, $params = [])
    {
        $stmt = $this->__conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    public function insert($sql, $params = [])
    {
        $stmt = $this->__conn->prepare($sql);
        $status = $stmt->execute($params);
        return $status; // Trả về true nếu thành công, false nếu thất bại
    }
    public function fetch($sql, $params = [])
{
    try {
        $stmt = $this->__conn->prepare($sql);
        $result = $stmt->execute($params);

        if ($result) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    } catch (PDOException $e) {
        die("Lỗi SQL (fetch): " . $e->getMessage() . " - Câu lệnh: " . $sql);
    }
}
}
