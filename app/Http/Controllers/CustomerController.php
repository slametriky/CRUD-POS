<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index');
    }
    
    public function data()
    {
        $customers = Customer::all();

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());
   
        return response()->json($customer, 200);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {   
        $customer->fill($request->except(['id', '_method']))->save();
   
        return response()->json($customer, 200);
    }

    public function destroy(Customer $customer)
    {   
        $delete = $customer->delete();

        return response()->json(['message' => 'success']);
    }
}
