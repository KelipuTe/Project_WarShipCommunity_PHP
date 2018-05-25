<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;

/**
 * Class ApiController [ api controller 基类 ]
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    protected $status_code = 200; // 状态码

    /**
     * 获取状态码
     * @return int
     */
    public function getStatusCode(){
        return $this->status_code;
    }

    /**
     * 设置状态码
     * @param $status_code
     * @return $this
     */
    public function setStatusCode($status_code){
        $this->status_code = $status_code;
        return $this; // 链式操作需要返回 $this
    }

    /**
     * 请求失败
     * @param $message
     * @return mixed
     */
    private function responseError($message)
    {
        return $this->response([
            'status' => 'failed',
            'error' => [
                'status_code' => $this->getStatusCode(),
                'message' => $message
            ]
        ]);
    }

    /**
     * 请求失败 404 错误
     * @param string $message
     * @return mixed
     */
    public function responseNotFound($message = 'Not Found'){
        // 链式操作需要在前面执行的函数返回 $this
        return $this->setStatusCode(404)->responseError($message);
    }

    /**
     * 返回结果
     * @param $data
     * @return mixed
     */
    public function response($data){
        return Response::json($data);
    }
}
