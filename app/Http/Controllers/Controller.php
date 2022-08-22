<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 封装一个成功的数据返回方法
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return false|string
     */
    public function success(string $msg='',array $data=array()) {
        $arr = array(
            'code' => 0,
            'msg'  => $msg,
        );
        if(!empty($data))
            $arr['data'] = $data;

        return json_encode($arr);
    }

    /**
     * 定义一个失败的数据返回方法
     * @param int $code
     * @param string $msg
     * @return false|string
     */
    public function fail(int $code, string $msg='') {
        $arr = array(
            'code' => $code,
            'msg'  => $msg
        );
        return json_encode($arr);
    }
}
