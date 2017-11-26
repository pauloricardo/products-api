<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsCategory extends Model
{
    protected $table = 'product_categories';
    protected $fillable = ['name'];

    public function products() {
        return $this->hasMany(Products::class, 'product_category_id');
    }
}
