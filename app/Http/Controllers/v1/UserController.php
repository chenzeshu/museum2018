<?php

namespace App\Http\Controllers\v1;

use App\User;

class UserController extends CommonController
{
    /**
     * 展现用户列表
     */
    public function index()
    {
        $data = User::all()->toArray();
        return response()->json(['code' => 10000, 'msg'=>'获取用户信息成功', 'data' => $data]);
    }
}
