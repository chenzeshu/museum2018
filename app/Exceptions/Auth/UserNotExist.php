<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class UserNotExist extends BaseException
{
    public $httpCode = 404;
    public $msg = "用户不存在";
    public $code = -10001; //自编通用类型码
}
