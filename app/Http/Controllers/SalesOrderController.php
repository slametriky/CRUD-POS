<?php

namespace App\Http\Controllers;

use App\Actions\SaveSalesOrder;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Repositories\Product\ProductRepository;
use App\Repositories\SalesOrder\SalesOrderRepository;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    protected $salesOrderRepo;
    protected $productRepo;

    public function __construct(SalesOrderRepository $salesOrderRepo, ProductRepository $productRepo) {
        $this->salesOrderRepo = $salesOrderRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $customers = Customer::all();
        $products =  $this->productRepo->data();

        return view('sales-orders.index', compact('customers', 'products'));
    }   

    public function data()
    {
        $salesOrders = $this->salesOrderRepo->data();
        
        return response()->json($salesOrders);
    }
    
    public function store(StoreSalesOrderRequest $request)
    {
        $store = (new SaveSalesOrder(new SalesOrder(), $request->all()))->handle();

        return response()->json(['message' => 'success']);
    }

    public function destroy(SalesOrder $salesOrder)
    {   
        $delete = $salesOrder->delete();

        return response()->json(['message' => 'success']);
    }
}
