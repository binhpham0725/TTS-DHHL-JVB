<?php

class RoomModel extends Model
{
    protected $roomTable = 'rooms';
    protected $roomTypeTable = 'room_types';

    public function getAllRooms() // Đã sửa tên hàm cho chuẩn
    {
        // Sử dụng nháy ngược để tránh lỗi SQL với dấu gạch ngang
        $sql = "SELECT r.*, rt.type_name 
                FROM `{$this->roomTable}` AS r 
                JOIN `{$this->roomTypeTable}` AS rt ON rt.id = r.room_type_id";

        return $this->db->fetchAll($sql);
    }
   
    public function getRoomByName($roomName)
    {
        $sql = "SELECT * FROM `{$this->roomTable}` WHERE `room_name` = :room_name";
        return $this->db->fetchAll($sql, array('room_name' => $roomName));
    }
    public function getRoomById($id)
    {
        $sql = "SELECT * FROM `{$this->roomTable}` WHERE `id` = :id";
        return $this->db->fetchOne($sql, array('id' => $id));
    }
    public function deleteRoom($id)
    {
        $sql = "DELETE FROM " . $this->roomTable . " WHERE id = :id";
        $params = [
            ':id' => $id
        ];
        $result = $this->db->delete($sql, $params);
        return $result;

    }
    public function addRoom($roomName, $roomTypeId, $gender){
        $sql = "INSERT INTO `{$this->roomTable}` (`room_name`, `room_type_id`, `gender`) VALUES (:room_name, :room_type_id, :gender)";
        $params = [
            ':room_name' => $roomName,
            ':room_type_id' => $roomTypeId,
            ':gender' => $gender
        ];
        $result = $this->db->insert($sql, $params);
        return $result;
    }

    public function updateRoom($id, $roomName, $roomTypeId, $roomStatus, $gender) {
        $sql = "UPDATE `{$this->roomTable}`
                SET `room_name`    = :room_name,
                    `room_type_id` = :room_type_id,
                    `gender`       = :gender,
                    `status`       = :status
                WHERE `id` = :id";
        return $this->db->update($sql, [
            ':room_name'    => $roomName,
            ':room_type_id' => $roomTypeId,
            ':gender'       => $gender,
            ':status'       => $roomStatus,
            ':id'           => $id,
        ]);
    }

        
}