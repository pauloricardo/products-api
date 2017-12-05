<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 04/12/2017
 * Time: 15:14
 */

namespace App\Http\Controllers\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'product_category_id' => 'required',
            'image' => 'required'
        ];
    }

}