<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 26/11/2017
 * Time: 16:26
 */
use Illuminate\Database\Seeder;

class ProductCategoriesSeed extends Seeder
{
    public function run() {
        App\ProductsCategory::create([
            'name' => str_random(10)
        ]);

    }
}