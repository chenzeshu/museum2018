<?php

namespace App\Http\Controllers\v1;

class TestController extends CommonController
{
    public function test()
    {
        return response()->json(['msg' => 123, ['haha'=>321]]);
    }
}
