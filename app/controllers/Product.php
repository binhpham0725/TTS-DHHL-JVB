<?php

use core\Controller;

class Product extends  Controller
{
    public $data = [];
    public $home_model;
    public function __construct()
    {
        $this->home_model = $this->model("ProductModel");

    }

    function index(){
        $page_title = "Danh sách sản phẩm";
        $dataProduct = $this->home_model->getProducts();
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["products"] = $dataProduct;
        $this->renderView('layouts/client_layout',$this->data);

    }
    function detail(){
        $page_title = "Danh sách sản phẩm";
        $dataProduct = $this->home_model->getProducts();
        $this->data["page_title"] = $page_title ?? "Trang chủ";
        $this->data["products"] = $dataProduct;
        $this->renderView('layouts/client_layout',$this->data);
    }
}
?>