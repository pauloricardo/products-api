<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    protected $prefix = 'products';
    public function index() {
        return view($this->prefix . '.index');
    }
    public function add() {
        return view($this->prefix . '.add');

    }
    public function edit() {
        return view($this->prefix . '.edit');
    }

    public function delete() {
        return view($this->prefix . '.delete');
    }
}
