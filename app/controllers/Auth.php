<?php
use core\Controller;
class Auth extends Controller{
    public $data = [];
    public $auth_model;
    public function __construct()
    {
        $this->auth_model = $this->model("AuthModel");

    }
    function loginApi(){
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->email) && isset($data->password)){
            $email = $data->email;
            $password = MD5($data->password);
            $role = $data->role;
            $dataUser = $this ->auth_model->login($email, $password, $role);
            $redirectUrl = ($role == 'admin') ? __WEB_ROOT__.'/admin/student' : __WEB_ROOT__.'/';
            if($dataUser){
                $response = [
                    "status" => "success",
                    "message" => "Đăng nhập thành công",
                    "redirect" => $redirectUrl
                ];
                $_SESSION['user'] = $data;
                $_SESSION['role'] = $role;
            }else{
                $response = [
                    "status" => "error",
                    "message" => "Thông tin tài khoản hoặc mật khẩu không đúng",
                    "redirect" => $redirectUrl
                ];
            }

            echo json_encode($response);
            exit();
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Thiếu thông tin đăng nhập"
            ]);
            exit();
        }
    }
    public function login(){
        $data = [
            'page_title' => "Đăng nhập"
        ];
        $this -> renderPlainView('auth/login');
    }

    function logout()
    {
        session_unset();
        session_destroy();
    }
}
