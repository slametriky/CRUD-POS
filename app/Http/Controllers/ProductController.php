<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Actions\SaveProduct;
use Illuminate\Http\Request;
use App\Actions\UpdateProduct;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\Product\ProductRepository;

class ProductController extends Controller
{

    protected $productRepo;

    public function __construct(ProductRepository $productRepo) {
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        return view('products.index');
    }   

    public function data()
    {
        $products = $this->productRepo->data();
        
        return response()->json($products);
    }
    
    public function edit(Product $product)
    {
        return response()->json($product);
    }

    public function store(StoreProductRequest $request)
    {
        $store = (new SaveProduct(new Product(), $request->all()))->handle();

        return response()->json(['message' => 'success']);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {   
        $update = (new UpdateProduct($product, $request->except(['_method', 'id'])))->handle();
   
        return response()->json(['message' => 'success']);
    }

    public function destroy(Product $product)
    {   
        $delete = $product->delete();

        return response()->json(['message' => 'success']);
    }
}
