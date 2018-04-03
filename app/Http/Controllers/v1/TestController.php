<?php

namespace App\Http\Controllers\v1;

use App\Jobs\RemoveFreeFile;
use App\Model\Actor\Actor;
use App\Model\Common\File;
use App\Model\Perf\Performance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestController extends CommonController
{
    public function test()
    {

    }
}
