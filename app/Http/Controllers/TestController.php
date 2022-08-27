<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function test()
    {
        //app();

        //compose require guzzlehttp/http :伪造http请求的工具包
        dump(Auth::guard('web'));
    }

    public function index() {
        echo strlen('你好'); //中文和文件编码有关：utf8编码，一个汉字3个字节；gbk编码，一个汉字2个字节pwd

        echo 'laravel-app Test Controller index action';
    }

    public function getSize() {
        echo 'laravel-app Test Controller getSize action';
    }

    public function getToken() {
        echo csrf_token();
    }
}
