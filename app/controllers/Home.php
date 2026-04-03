<?php

use core\Controller;

class Home extends Controller{
    public $data = [];
    public $home_model;
    public function __construct()
    {
        if(!$this->isLogin()){
            echo "Bạn không có quyền truy cập vào trang này";
            exit();
        }
        $this->home_model = $this->model("HomeModel");

    }

    function index(){
        $page_title = "Trang chủ";
        $dataProduct = $this->home_model->getProducts();
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["content"] = 'admin/student';
        $this->renderView('layouts/client_layout',$this->data);

    }

    function  loadUser()
    {
        header("Content-type: application/json");
        if(isset($_SESSION['user'])){
            echo json_encode($_SESSION['user']);
        }
    }
}
?>

