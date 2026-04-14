<?php

use core\Controller;

class RoomAssignment extends Controller
{
    public $data = [];
    public $modal;
    public function __construct(){
        $this->model = $this->model("RoomAssignmentModal");
    }
    function index()
    {
        $page_title = "Yêu cầu đăng kí phòng";
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["js_file"]  ='room_assignment.js';
        $this->data["content"] = 'admin/roomassignment';
        $this->renderView('layouts/admin_layout', $this->data);
    }
    public function detail($id){
         header("Content-type: application/json");
        $data = $this ->model-> detail($id);
        echo json_encode($data);
    }   
    function list(){
        header("Content-type: application/json");
        $data = $this -> model -> getList();
        echo json_encode($data);
    }
    public function getAvailableRooms($room_type_id,$gender=0){
        header("Content-type: application/json");
        $data = $this -> model -> getAvailableRooms($room_type_id,$gender);
        echo json_encode($data);
    }
    public function reject(){
        header("Content-type: application/json");
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!$data || !isset($data["id"]) || !isset($data["note"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Dữ liệu gửi lên không hợp lệ hoặc thiếu thông tin"
        ]);
        return;
        }
        $id = $data["id"];
        $note = $data["note"] ?? "";
        $result = $this->model->reject($id,$note);
        if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Từ chối yêu cầu thành công"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Từ chối yêu cầu thất bại (Có thể do phòng đã đầy hoặc lỗi DB)"
        ]);
    }
    
    }
    public function getListData(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $now = date('Y-m-d H:i:s');
        switch($data['time_start']){
            case '1':
                $time_start = date('Y-m-d H:i:s', strtotime('-1 day'));
                break;
            case '2':
                $time_start = date('Y-m-d H:i:s', strtotime('-7 day'));
                break;
            case '3':
                $time_start = date('Y-m-d H:i:s', strtotime('-30day'));
                break;
            case '4':
                $time_start = date('Y-m-d H:i:s', strtotime('-90day'));
                break;
            default:
                $time_start = '';
        };
       
        header("Content-type: application/json");
        $page = $data['page'] ?? 1;
        $per_page = $data['per_page'] ?? 20;
        $filters = [
        'keyword' => $data["keyword"] ?? "",
        'status'  => $data['status'] ?? 0,
        'time_start'  => $time_start
    ];
        $total_page = $this -> model -> countAllData($filters);
        $data = $this->model->getListData($page, $per_page, $filters);
        if($data){
            echo json_encode([
                "status"=>"success",
                "message"=>"Lấy dữ liệu thành công",
                "total_page" => $total_page,
                "data" => $data
            ]);
            return;
        }
         echo json_encode([
                "status"=>"error",
                "message" => "Lấy dữ liệu thất bại"
            ]);
      
    }
    public function approve() {
        header("Content-type: application/json");
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data["id"]) || !isset($data["room_id"])) {
            echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ hoặc thiếu thông tin"]);
            exit();
        }

        $id       = $data["id"];
        $room_id  = $data["room_id"];
        $check_in = $data["check_in"] ?? date('Y-m-d');
        $note     = $data["note"] ?? "";

        $result = $this->model->approve($id, $room_id, $check_in, $note);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Xếp phòng cho sinh viên thành công"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Xếp phòng thất bại"]);
        }
        exit();
    }
}