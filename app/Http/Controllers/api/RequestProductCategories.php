<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 26/11/2017
 * Time: 17:01
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\ProductsCategory;

class RequestProductCategories extends Controller
{
    public function index() {
        $categories = ProductsCategory::all();
        return response()->json($categories);

    }

}