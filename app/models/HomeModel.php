<?php

//Kế thừa từ class model
class HomeModel extends Model {
    protected $table = 'products';
    public function getProducts() {
        $data = [
            'item1',
            'item2'
        ];
            return $data;
    }
}
?>