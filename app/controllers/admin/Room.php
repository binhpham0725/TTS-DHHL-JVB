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
        $this->data["js_file"]  ='room.js';
        $this->data["content"] = 'admin/room';
        $this->renderView('layouts/admin_layout', $this->data);
    }

    public function getRooms()
    {
        header("content-type: application/json");
        try{
            echo json_encode($this->room_model->getAllRooms());
        }catch (Exception $e){
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
    public function getAvailableRooms(){
        header("content-type: application/json");
        try{
            echo json_encode($this->room_model->getAvailableRooms());
        }catch (Exception $e){
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
    public function add(){
        header("content-type: application/json");
        $data = json_decode(file_get_contents("php://input"), true);
        $room_name    = $data['room_name'];
        $room_type_id = $data['room_type_id'];
        $gender       = $data['gender'];
        if ($this->room_model->getRoomByName($room_name) != []) {
            echo json_encode(["status" => "error", "message" => "Tên phòng này đã tồn tại"]);
            exit();
        }
        $result = $this->room_model->addRoom($room_name, $room_type_id, $gender);
        echo json_encode($result
            ? ["status" => "success", "message" => "Thêm phòng thành công"]
            : ["status" => "error",   "message" => "Thêm phòng thất bại"]
        );
        exit();
    }
    public function details($id){
        header("content-type: application/json");
        $data = $this->room_model->getRoomById($id);
        echo json_encode($data
            ? ["status" => "success", "data_room" => $data]
            : ["status" => "error",   "message"   => "Không tìm thấy phòng"]
        );
        exit();
    }
    public function delete($id){
        header("content-type: application/json");
        $result = $this->room_model->deleteRoom($id);
        echo json_encode($result
            ? ["status" => "success", "message" => "Xóa phòng thành công"]
            : ["status" => "error",   "message" => "Xóa phòng thất bại"]
        );
        exit();
    }
    public function getRoomByName($name){
        header("content-type: application/json");
        $result=$this->room_model -> getRoomByName($name);
        echo json_encode($result);
    }

    function update()
    {
        header("content-type: application/json");
        $data = json_decode(file_get_contents("php://input"));
        $room_id = $data->id;
        $room_name = $data->room_name;
        $room_type_id = $data->room_type;
        $room_status = $data->room_status;
        $gender = $data->gender;
        $result = $this->room_model->updateRoom($room_id, $room_name, $room_type_id, $room_status, $gender);

        if($result){
            echo json_encode([
                "status" => "success",
                "message" => "Cập nhật phòng thành công"
            ]);
        }
        else{
            echo json_encode([
                "status" => "error",
                "message" => $result
            ]);
        }

    }
}
?>