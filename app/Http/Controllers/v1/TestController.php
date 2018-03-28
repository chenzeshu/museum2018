<?php

namespace App\Http\Controllers\v1;

use App\Model\Common\File;
use App\Model\Perf\Performance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        File::create([
           'file_name' => 123,
           'file_path' => 321,
           'file_type' => 'photo'
        ]);
    }
}
