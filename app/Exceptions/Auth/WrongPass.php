<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;
class WrongPass extends BaseException
{
    public $httpCode = 500;
    public $msg = "密码错误";
    public $code = -10002; //自编通用类型码
}
