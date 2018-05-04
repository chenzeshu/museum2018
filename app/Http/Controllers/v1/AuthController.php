<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\Auth\UserNotExist;
use App\Exceptions\Auth\WrongPass;
use App\Jobs\RemoveFreeFile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $phone = $request->phone;
        $password = $request->password;
        if($user = User::where('user_phone', $phone)->first()){
            if(Hash::check($password, $user->user_pass)){
                $token = JWTAuth::fromUser($user);
                //清理无用文件
                new RemoveFreeFile();
                //将token存放到header里
                return response(json_encode(['httpCode'=>200, 'msg' => '登陆成功'], JSON_UNESCAPED_UNICODE))->header('authorization', $token);
            }else{
                throw new WrongPass();
            }
        }else{
            throw new UserNotExist();
        }

    }
}
