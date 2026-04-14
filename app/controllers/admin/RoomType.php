<?php

use app\models\RoomModel;
use core\Controller;

class RoomType   extends Controller
{
    public $data = [];
    public $room_type_model;

    public function __construct()
    {
        $this->room_type_model = $this->model("RoomTypeModel");
    }

    function index()
    {
        $page_title = "Quản lý phòng";
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["js_file"] = 'room.js';
        $this->data["content"] = 'admin/room';
        $this->renderView('layouts/admin_layout', $this->data);
    }
    public function list(){
        header("content-type: application/json");
        $result = $this -> room_type_model -> getList();
        echo json_encode($result);
    }

}
?>