<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['name', 'description', 'image', 'price', 'product_category_id'];
    protected $hidden  = ['created_at', 'updated_at', 'deleted_at', 'status'];

    public function productCategories() {
        return $this->belongsTo(ProductsCategory::class, 'product_category_id');
    }
}
