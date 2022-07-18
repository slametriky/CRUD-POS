<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\ProductInterface;

class ProductRepository implements ProductInterface 
{
    public function data(){

        $products = Product::all();

        return $products;
    }    

}


