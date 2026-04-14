<?php

class RoomTypeModel extends Model
{

    protected $roomTypeTable = 'room_types';
    public function getList()
    {
        $sql = "SELECT * FROM " . $this-> roomTypeTable;
        return $this->db->fetchAll($sql);
    }

}