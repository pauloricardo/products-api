<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    protected $prefix = 'products';
    public function __construct()
    {
        $this->middleware('auth');
    }
}
