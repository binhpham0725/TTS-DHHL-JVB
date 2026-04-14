<?php

class RoomAssignmentModal extends Model
{
    protected $roomAssignmentTable = 'room_assignment';
    protected $roomTypeTable = 'room_types';
    protected $studentTable = 'students';
    public function countAllData($filters) {
    $params = [];
    $where = " WHERE 1=1";
    if (!empty($filters['keyword'])) {
        $where .= " AND (sv.name LIKE :keyword OR sv.mssv LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }

    if (isset($filters['status']) && $filters['status'] !== '') {
        $where .= " AND rs.status = :status";
        $params[':status'] = $filters['status'];
    }

    // Sử dụng COUNT(*) để tối ưu Performance
    $sql = "SELECT COUNT(*) as total 
            FROM `{$this->roomAssignmentTable}` AS rs 
            JOIN `{$this->studentTable}` AS sv ON sv.id = rs.student_id 
            {$where}";

    $result = $this->db->fetch($sql, $params);
    return $result['total'] ?? 0;
}
   public function getListData($page, $per_page, $filters) {
    $params = [];
    $where = " WHERE 1=1"; 

    
    if (!empty($filters['keyword'])) {
        $where .= " AND (sv.name LIKE :keyword OR sv.mssv LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }

   
    if (isset($filters['status']) && $filters['status'] !== '') {
        $where .= " AND rs.status = :status";
        $params[':status'] = $filters['status'];
    }
    if (!empty($filters['time_start'])) {
        $where .= " AND rs.created_date BETWEEN :time_start AND CURRENT_DATE";
        $params[':time_start'] = $filters['time_start'];
    }

   
    $offset = ($page - 1) * $per_page;

    $sql = "SELECT rs.*, rt.type_name, rt.price, sv.name AS student_name, sv.mssv 
            FROM `{$this->roomAssignmentTable}` AS rs 
            JOIN `{$this->roomTypeTable}` AS rt ON rt.id = rs.room_type_id 
            JOIN `{$this->studentTable}` AS sv ON sv.id = rs.student_id 
            {$where} 
            ORDER BY rs.created_date DESC 
            LIMIT {$per_page} OFFSET {$offset}";

    return $this->db->fetchAll($sql, $params); 
}
    public function getList(){
        $sql = "SELECT rs.*, rt.type_name, rt.price, sv.name AS student_name, sv.mssv 
                FROM `{$this->roomAssignmentTable}` AS rs 
                JOIN `{$this->roomTypeTable}` AS rt ON rt.id = rs.room_type_id 
                JOIN `{$this->studentTable}` AS sv ON sv.id = rs.student_id;";
        $result = $this->db ->fetchAll($sql);
        return $result;
    }
    public function detail($id){
        $sql = "SELECT *  
                FROM `{$this->roomAssignmentTable}` AS rs 
                JOIN `{$this->roomTypeTable}` AS rt ON rt.id = rs.room_type_id 
                JOIN `{$this->studentTable}` AS sv ON sv.id = rs.student_id;
                WHERE rs.id = :id
                ";
        $result = $this->db ->fetchOne($sql,[
            ":id" => $id 
        ]);
        return $result;
    }
    public function reject($id, $note) {
    
    $sql = "UPDATE `{$this->roomAssignmentTable}` 
            SET `status` = 2, 
                `note` = :note 
            WHERE `id` = :id";
        return $this->db->update($sql, [
                ':note'    => $note,
                ':id'      => $id
                ]);
    }
    public function approve($id, $room_id,$check_in ,$note) {
    
    $sql = "UPDATE `{$this->roomAssignmentTable}` 
            SET `status` = 1, 
                `room_id` = :room_id, 
                `check_in` = :check_in,
                `note` = :note  
            WHERE `id` = :id";
         return $this->db->update($sql, [
                ':id'      => $id,
                'check_in' => $check_in,
                ':room_id' => $room_id,
                ':note'    => $note
                ]);
}
     public function getAvailableRooms($room_type_id,$gender){
        // 
        $sql = "SELECT r.* FROM `rooms` AS r
        JOIN `room_types` AS rt ON rt.id = r.room_type_id 
        WHERE r.room_type_id = :room_type_id
        AND r.gender = :gender  
        AND r.current_number < rt.max_people";
        $result = $this->db->fetchAll($sql, [
    ":room_type_id" => $room_type_id,
    ":gender"       => $gender
        ]);

        return $result;
            }
}