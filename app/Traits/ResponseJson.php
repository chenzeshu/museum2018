<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 15:07
 */

namespace App\Traits;


trait ResponseJson
{
    /**
     * 封装了成功响应的JSON格式
     * @param array ...$params  ['httpCode', 'msg', (array)'data']
     * @return \Illuminate\Http\JsonResponse
     */
    public function resSuccess(...$params)
    {
        $count = count($params);
        $httpCode = 200; $msg = "响应成功"; $data = [];
        switch ($count){
            case 0:
                $data = null;
                break;
            case 1:
                $data = $params[0];
                break;
            case 2:
                $msg = $params[0];
                $data = $params[1];
                break;
            default:
                $httpCode = $params[0];
                $msg = $params[1];
                $data = $params[2];
                break;
        }
        return response()->json([
            'httpCode' => $httpCode,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    public function resError()
    {
        return response()->json(['msg'=>'失败']);
    }
}