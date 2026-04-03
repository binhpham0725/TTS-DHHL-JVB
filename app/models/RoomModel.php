<?php

class RoomModel
{
    protected $roomTable = 'rooms';
    protected $roomTypeTable = 'room-types';
    public function getAllRomm()
    {
        $sql = "SELECT r.*, rt.type_name FROM ".$this->roomTable." AS r JOIN ".$this->roomTypeTable." as rt on rt.id =r.room_type_id";
        $dataStudent = $this->db->fetchAll($sql);
        return $dataStudent;
    }

}