<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 26/11/2017
 * Time: 16:06
 */

namespace App\Http\Controllers;


class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        return view('dashboard.index');
    }
}