<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(){
        return view('admin.login');
    }
    public function do_login(){
        $data = \request()->except('_token');
        dd($data);
    }
}
