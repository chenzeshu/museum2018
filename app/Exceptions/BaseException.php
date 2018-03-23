<?php

namespace App\Exceptions;

class BaseException extends \RuntimeException
{
    public $httpCode = 200;
    public $msg = "一切正常";
    public $code = 10000; //自编通用类型码
}
