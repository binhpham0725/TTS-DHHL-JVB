<?php

use app\models\StudentModel;
use core\Controller;

class Student extends Controller{
    public $data = [];
    public $sudent_model;
    public function __construct()
    {
        $this-> sudent_model= $this->model("StudentModel");



    }
    function index(){
        $page_title = "Quản lý tài khoản học sinh";
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["content"] = 'admin/student';
        $this->renderView('layouts/admin_layout',$this->data);
    }

    function getAllstudents(){
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->sudent_model->getAllStudents();
        if($data){
            echo json_encode($data);

        }else {
            echo json_encode([
                "message"=>"Lấy dữ liêu sinh viên không thành công"
            ]);
        }
    }
    function getStudentById($id){
        $id = intval($id);
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->sudent_model->getStudentById($id);
        echo json_encode($data);
    }
    function  getStudent($limit = 10,$offset = 0)
    {
        $limit = (int)$limit;
        $offset = (int)$offset;
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->sudent_model->getStudent($limit,$offset);
        echo json_encode($data);
    }
    function search($keyword,$limit = 10,$offset = 0)
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->sudent_model->search($keyword);
        echo json_encode($data,$limit,$offset);

    }

    function delete($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try{
            $result = $this->sudent_model->delete($id);
            if($result){
                echo json_encode([
                    "status" => "success",
                    "message" => "Xóa sinh viên thành công"
                ]);
            }else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Xóa sinh viên thất bại"
                ]);
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
    function update(){
        header('Content-Type: application/json; charset=utf-8');
        $data = file_get_contents('php://input');
        $jsonData = json_decode($data);
        $id = $jsonData->id;
        $mssv = $jsonData->mssv;
        $name = $jsonData->name;
        $gender = $jsonData->gender;
        $birthday = $jsonData->birthday;
        $cccd = $jsonData->cccd;
        $email = $jsonData->email;
        $phone = $jsonData->phone;
        $address = $jsonData->address;
        try{
            $result = $this -> sudent_model -> updateStudent($id,$mssv,$name,$gender,$birthday,$cccd,$email,$phone,$address);
            if($result){
                echo json_encode([
                    "status" => "success",
                    "message" => "Cập nhật thông tin sinh viên thành công"
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error",
                    "message" => "Cập nhật thông tin sinh viên thất bại"
                ]);
            }
        }
        catch (PDOException $e){
            echo json_encode([
                "status" => "error",
                "code" => $e->getCode(),
                "message" =>$e->errorInfo[2]
            ]);
        }

    }
    function  addStudent(){
        header('Content-Type: application/json; charset=utf-8');
        $data = file_get_contents('php://input');
        $jsonData = json_decode($data);
        $mssv = $jsonData->mssv;
        $name = $jsonData->name;
        $gender = $jsonData->gender;
        $birthday = $jsonData->birthday;
        $cccd = $jsonData->cccd;
        $email = $jsonData->email;
        $phone = $jsonData->phone;
        $address = $jsonData->address;
        $password = $jsonData->password;
        try{
            $result = $this -> sudent_model -> insertStudent($mssv, $name, $gender, $birthday, $cccd, $email, $phone, $address, $password);
            if($result){
                echo json_encode([
                    "status" => "success",
                    "message" => "Thêm sinh viên thành công"
                ]);
            }
            else{
                echo json_encode([
                    "status" => "error",
                    "message" => "Thêm sinh viên thất bại"
                ]);
            }
        }
        catch (PDOException $e){
            echo json_encode([
                "status" => "error",
                "code" => $e->getCode(),
                "message" =>$e->errorInfo[2]
            ]);
        }


    }
}
?>