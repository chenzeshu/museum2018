<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Model\Perf\Performance;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function page(Request $request)
    {
        $page = $request->page;
        $pageSize = $request->pageSize;
        $begin = ($page - 1) * $pageSize;
        $data = Performance::offset($begin)
            ->limit($pageSize)
            ->with(['perfActors', 'perfDetail', 'perfType', 'perfAddr', 'perfTroupe'])
            ->get()
            ->toArray();
        $count = Performance::count();
        $data = [
            'data' => $data,
            'count' => $count
        ];

        return response()->json(['code'=> 200, 'msg'=>'获取演出信息成功', 'data' => $data]);
    }
}
