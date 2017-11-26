<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 26/11/2017
 * Time: 16:22
 */
use Illuminate\Database\Seeder;

class ProductsSeed extends Seeder
{
    public function run() {
        App\Products::create([
            'name' => str_random(10),
            'description' => str_random(50),
            'image' => str_random(10),
            'price' => rand(1, 10),
            'product_category_id' => 1
        ]);
    }
}