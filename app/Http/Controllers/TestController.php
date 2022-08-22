<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index() {
        echo 'laravel-app Test Controller index action';
    }

    public function getSize() {
        echo 'laravel-app Test Controller getSize action';
    }

    public function getToken() {
        echo csrf_token();
    }
}
