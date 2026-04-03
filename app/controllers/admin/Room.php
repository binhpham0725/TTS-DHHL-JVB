<?php

use app\models\RoomModel;
use core\Controller;

class Room   extends Controller
{
    public $data = [];
    public $room_model;
    public function __construct()
    {
        $this->room_model = $this->model("RoomModel");
    }
    function index()
    {
        $page_title = "Quản lý phòng";
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["content"] = 'admin/room';
        $this->renderView('layouts/admin_layout', $this->data);
    }
    public function getRooms()
    {
        header("content-type: application/json");
        try{
            echo json_encode($this->room_model->getAllRomm());
        }catch (Exception $e){
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
?>