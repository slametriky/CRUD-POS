<?php

namespace App\Repositories\SalesOrder;

use App\Models\SalesOrder;
use App\Repositories\SalesOrder\SalesOrderInterface;

class SalesOrderRepository implements SalesOrderInterface 
{
    public function data(){

        $salesOrders = SalesOrder::with('items.product', 'customer')->get();

        return $salesOrders;
    }    

}


