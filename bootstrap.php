<?php
define("__DIR_ROOT__", __DIR__);
// xử Lý http root
if(!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
    $web_root = 'https://'.$_SERVER['HTTP_HOST'];

}else {
    $web_root = 'http://'.$_SERVER['HTTP_HOST'];

}
$root = str_replace('\\', '/', __DIR_ROOT__);
$doc_root = str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"]);

$folder = str_replace(strtolower($doc_root), '', strtolower($root));

$web_root = $web_root . $folder;
define("__WEB_ROOT__", $web_root);
// Tự động load config
$configs_dir = scandir("configs");
if(!empty($configs_dir)){
    foreach($configs_dir as $item){
        // Kiểm tra: KHÔNG PHẢI là "." VÀ KHÔNG PHẢI là ".."
        // ĐỒNG THỜI phải là tệp tin (không phải thư mục con)
        if($item != "." && $item != ".." && is_file("configs/".$item)){
            require_once("configs/".$item);
        }
    }
}
require 'core/route.php';
require 'app/app.php';
if(!empty($config['database'])){

    $db_config = array_filter($config['database']);
    if(!empty($db_config)){
        require_once("core/Connection.php");
        require_once("core/Database.php");
    }
}
require 'core/Model.php';
require 'core/Controller.php';
?>