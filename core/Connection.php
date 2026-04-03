<?php

class Connection
{
    public static $instance = null,$connection;
    private function __construct($config){
        $password = $config['password'] ?? "";
        $dsn='mysql:host='.$config["host"].';dbname='.$config["dbname"];
        try{
            $con = new PDO($dsn, $config["user"], $password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection = $con;
        }
        catch(PDOException $e){
            die("Kết nối đên cơ sở dữ liệu thất baị: " . $e->getMessage());
        }
    }
    public static function getInstance($config){
        if(self::$instance == null){
            $connection = new Connection($config);
            self::$instance = self::$connection;
        }
        return self::$instance;
    }
}