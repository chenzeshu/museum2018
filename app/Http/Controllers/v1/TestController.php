<?php

namespace App\Http\Controllers\v1;

use App\Model\Perf\Performance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        $data = config("app.record.performance");
        return $data;
    }
}
