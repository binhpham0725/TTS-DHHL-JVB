<?php


namespace core;
class Controller

{

   public function model($model)
   {
       // 1. Tạo đường dẫn và chuẩn hóa dấu \ thành /
       $path = __DIR_ROOT__ . '/app/models/' . $model . '.php';
       $path = str_replace('\\', '/', $path);

       if (file_exists($path)) {
           require_once $path;


           if (class_exists($model)) {
               return new $model();
           }
       }



       return false;
   }
    function renderView($view,$data=[]){
        extract($data);
        if (file_exists(__DIR_ROOT__ . '/app/views/' . $view . '.php')) {
            require_once __DIR_ROOT__ . '/app/views/' . $view . '.php';
            }
    }
    function renderPlainView($view, $data = []) {
        extract($data);
        $viewPath = __DIR_ROOT__ . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View $view không tồn tại!");
        }
    }

    public function isLogin()
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }
    public function isStudent(){
        if($this->isLogin()){
            return true;
        }
    }

}