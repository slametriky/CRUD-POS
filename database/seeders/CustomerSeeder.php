<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'name' => 'Customer 1',
            'email' => 'customer1@gmail.com',
            'phone' => '081234334232',
            'address' => 'Jakarta',
        ]); 
    }
}
