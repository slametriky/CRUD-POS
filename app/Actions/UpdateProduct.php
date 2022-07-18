<?php

namespace App\Actions;

use Exception;
use App\Models\Product;
use App\Services\ImageService;

class UpdateProduct
{
    private Product $product;
    private array $attributes;

    public function __construct(Product $product, array $attributes = [])
    {
        $this->product = $product;
        $this->attributes = $attributes;
    }

    public function handle(): void
    {
        $this->product->fill($this->attributes)->save();
        
    }
}
