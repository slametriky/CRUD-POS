<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $datas = [
            [
                'name' => 'Pensil 2B',
                'description' => 'Pensil 2B Ori',
                'price' => 6000,
            ],
            [
                'name' => 'Pulpen Pilot',
                'description' => 'Pulpen Pilot New Design',
                'price' => 3500,
            ],
            [
                'name' => 'Penggaris Plastik',
                'description' => 'Penggaris Plastik 30 CM',
                'price' => 4000,
            ]
        ];

        foreach ($datas as $data) {
            $product = new Product();
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->price = $data['price'];
            $product->save();
        }

    }
}
