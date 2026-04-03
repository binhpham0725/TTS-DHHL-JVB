<?php

class ProductModel extends Model
{
    protected $table = 'products';
    public function getProducts() {
        $data = [
            'item1',
            'item2'
        ];
        return $data;
    }
}