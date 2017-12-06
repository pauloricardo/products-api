<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 26/11/2017
 * Time: 15:51
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Requests\CreateProductRequest;
use App\Products;
use App\ProductsCategory;
use App\Traits\StatusTrait;
use Illuminate\Http\Request;
use Mockery\Exception;
use phpDocumentor\Reflection\Types\Static_;


class RequestProducts extends Controller
{
    use StatusTrait;

    public function index()
    {
        $products = Products::with('productCategories')->get();
        return response()->json($products);
    }

    public function create(CreateProductRequest $request, ProductsCategory $productsCategory)
    {
        $message = ['message' => 'Product successfully created!', 'status' => StatusTrait::$status['success']];
        if ($data = $request->all()) {
            try {
                $productsCategory->find($data['product_category_id'])
                    ->products()
                    ->create($data);
            } catch (\Exception $e) {
                $message['message'] = $e->getMessage();
                $message['status'] = StatusTrait::$status['error'];
            }
        }
        return response()->json($message)->setStatusCode($message['status']);
    }

    public function find($product)
    {

        return response()->json(Products::find($product));
    }

    public function upload(Request $request, $field, array $paths)
    {
        $message = ['status' => StatusTrait::$status['success']];
        if (!$request->hasFile($field)) {
            return ['message' => false, 'status' => StatusTrait::$status['error']];
        } else {
            try{
                $image = $request->file($field);
                $fileName = $this->buildFileName($image);
                $image->storeAs($paths['folder'], $fileName, ['disk' => $paths['disk']]);
                $message['message'] = $fileName;
            } catch( \Exception $e) {
                $message['message'] = 'Invalid format!';
                $message['status'] = StatusTrait::$status['error'];
            }
            return $message;
        }
    }

    public function uploadImage(Request $request)
    {
        $this->getPath('products/images', 'public_path');
        $message = $this->upload($request,'image', ['folder' => 'images', 'disk' => 'products']);
        return response()->json($message)->setStatusCode($message['status']);
    }

    private function getPath($path, $basePath)
    {
        if (strpos($path, '/')) {
            $matches = explode('/', $path);
            $fullPath = '';
            foreach ($matches as $match) {
                $fullPath .= $match . '/';
                if (!is_dir($basePath($fullPath))) mkdir($basePath($fullPath), 0777);
            }
        } else {
            if (!is_dir($basePath($path))) mkdir($basePath($path), 0777);
        }
    }

    protected function edit($id, Request $request)
    {
        $message = ['result' => true, 'message' => 'Product successfully updated!', 'status' => StatusTrait::$status['success']];
        try {
            Products::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            $message['result'] = false;
            $message['status'] = StatusTrait::$status['error'];
            $message['message'] = $e->getMessage();
        }
        return response()->json($message)->setStatusCode($message['status']);
    }

    protected function destroy($id)
    {
        $message = ['result' => true, 'message' => 'Product successfully deleted!', 'status' => StatusTrait::$status['success']];
        try {
            Products::findOrFail($id)->delete();
        } catch (\Exception $e) {
            $message['result'] = false;
            $message['message'] = $e->getMessage();
            $message['status'] = StatusTrait::$status['error'];
        }
        return response()->json($message)->setStatusCode($message['status']);
    }

    protected function uploadCsv(Request $request)
    {
        $message = ['result' => true, 'message' => 'CSV successfully uploaded!', 'status' => StatusTrait::$status['success']];
        $this->getPath('csv/files', 'storage_path');
        $message = $this->upload($request,'csv', ['folder' => 'files', 'disk' => 'csv']);
        return response()->json($message)->setStatusCode($message['status']);
    }
    private function buildFileName($file)
    {
        $fileName = md5(uniqid() . '.' .
                $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        return $fileName;
    }
}