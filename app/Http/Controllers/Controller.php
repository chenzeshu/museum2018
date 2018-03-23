<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 封装了成功响应的JSON格式
     * @param array ...$params  ['httpCode', 'msg', (array)'data']
     * @return \Illuminate\Http\JsonResponse
     */
    public function resSuccess(...$params)
    {
        if(count($params) == 1){
            return response()->json([
                'httpCode' => 200,
                'msg' => '响应成功',
                'data' => $params[0]
            ]);
        }else if(count($params) == 0){
            return response()->json([
                'httpCode' => 200,
                'msg' => '响应成功'
            ]);
        }else{
            return response()->json([
                'httpCode' => $params[0],
                'msg' => $params[1],
                'data' => $params[2]
            ]);
        }
    }
}
