<?php

namespace App\Http\Controllers\v1;

use App\Model\Actor\Actor;
use App\Model\Common\File;
use App\Model\Perf\Performance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends CommonController
{
    public function test()
    {
        $page = 1;
        $pageSize = 10;
        $begin = ( $page - 1 ) * $pageSize;


        $actors = Actor::with(['troupeHistory', 'actorFiles']);
        $data = $actors
            ->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();

        return $data;
        $count = $actors->count();

        $data = [
            'actors' => $data,
            'count' => $count
        ];

        return $this->resSuccess('获取演员成功', $data);
    }
}
