<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 26/11/2017
 * Time: 15:51
 */
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Products;
use App\ProductsCategory;
use Illuminate\Http\Request;

class RequestProducts extends Controller
{
    public function index()
    {
        $products = Products::with('productCategories')->get();
        return response()->json($products);
    }

    public function create(Request $request, ProductsCategory $productsCategory) {
        $message = &$request;

        if ($data = $request->all()) {
            $productCategory->find($data['product_categories_id'])
                ->products()
                ->create($data);
            $message = 'Saved!';
        }

        return response()->json(['message' => $message]);
    }
    public function upload($request, $field, array $paths)
    {
        if ($request->hasFile($field)) {
            $image  = $request->file($field);
            $uid    = uniqid();

            $fileName = md5($image . $image->getFilename());

            $image->storeAs($paths['folder'], $fileName, $paths['disk']);

            $message = ['file' => $paths['disk'] . '/' . $paths['folder'] . '/' . $fileName];

            return $message;
        }
    }
    public function uploadImage(Request $request)
    {
        $message = 'Not found!';
        $this->getPath('products/images', 'public_path');
        $message = $this->upload($request, 'image', ['folder' => 'images', 'disk' => 'products']);

        return response()->json($message);
    }
    private function getPath($path, $basePath)
    {
        if (strpos($path, '/') !== false) {
            $matches = explode('/', $path);
            $fullPath = '';
            foreach($matches as $match) {
                $fullPath .= $match . '/';
                if (!is_dir($basePath($fullPath))) mkdir($basePath($fullPath), 0777);
            }
        } else {
            if (!is_dir($basePath($path))) mkdir($basePath($path), 0777);
        }
    }



}