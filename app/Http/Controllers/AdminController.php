<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function admin()
    {
        $data['api'] = env("APP_URL", "") . 'api/v1/';
        $data = (object) $data;
        return view('admin')->with('data', $data);
    }
}
