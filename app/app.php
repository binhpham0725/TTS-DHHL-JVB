<?php

class App{
    private $__controller, $__action, $__params,$__routes;
public function __construct()
{

    global $routes,$config;
    $this ->__routes = new Route();
    if(!empty($routes['default_controller'])){
        $this->__controller = $routes['default_controller'];
    }

    $this->__controller = "Home";
    $this->__action = "index";
    $this->__params = "home";
    $this->handleUrl();


}
function getUrl(){

    if(!empty($_SERVER['PATH_INFO'])){
        $url=$_SERVER['PATH_INFO'];
    }else {
        $url='/';
    }
    return $url;
}
function handleUrl(){

    $url = $this ->getUrl();
    $url=$this -> __routes -> handelRoute($url);
    $urlAttr = array_filter(explode('/',$url));
    $urlAttr = array_values($urlAttr);

    $urlCheck = "";
    foreach($urlAttr as $key => $item){
        $urlCheck .= $item."/";
        $fileCheck = rtrim($urlCheck,"/");
        $fileAttr= explode("/",$fileCheck);
        $lastIndex = count($fileAttr) - 1;
        $fileAttr[$lastIndex] = ucfirst($fileAttr[$lastIndex]);
        $fileCheck = implode("/",$fileAttr);
        if(!empty($urlAttr[$key-1])){
            unset($urlAttr[$key-1]);
        }

        if(file_exists('app/controllers/'.$fileCheck.'.php')){
            $urlCheck = $fileCheck;
            break;
        }
    }

    $urlAttr = array_values($urlAttr);
    if(!empty($urlAttr[0])) {

        $this->__controller = ucfirst($urlAttr[0]);
    }
    else {
        $this->__controller = ucfirst($this->__controller);
    }
    if(empty($urlCheck)){
        $urlCheck = $this->__controller;
    }
    if(file_exists('app/controllers/'.($urlCheck).'.php')){
        require_once "controllers/".($urlCheck).'.php';

        if(class_exists($this->__controller)){
            $this->__controller = new $this->__controller();
            unset($urlAttr[0]);
        }else {
            $this->loadError();
        }
    }
    else {
             $this->loadError();
         }

//    Xử lý action
    if(!empty($urlAttr[1])) {
        $this->__action = $urlAttr[1];

        unset($urlAttr[1]);

    }

//    Xử lý prams
    $this->__params = array_values($urlAttr);

    if(method_exists($this->__controller, $this->__action)){
        call_user_func_array([$this->__controller,$this->__action],$this->__params);
    }
    else {
        $this->loadError();
    }

}
function loadError($name='404'){
    require "errors/".$name.".php";
}
}
?>