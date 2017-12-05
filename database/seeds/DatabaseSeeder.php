<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ProductCategoriesSeed::class);
         $this->call(ProductsSeed::class);
         $this->call(UserSeed::class);
    }
}
