<?php

namespace App\Http\Controllers\v1;

use App\Model\Perf\Performance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        $begin = 1;
        $pageSize = 10;
        $sc = array (
            'perf_troupe' => 2,
            'perf_type' => 3,
            'perf_addr' => 2,
            'perf_actors' =>
                array (
                    0 => 2,
                ),
            'perf_date' =>
                array (
                    0 => '2018/03/01',
                    1 => '2018/04/03',
                ),
        );
        $data = Performance::whereBetween('perf_date', $sc['perf_date'])
            ->where(function ($query)use($sc){
                if(!empty($sc['perf_troupe'])){
                    $query->where('perf_troupe', $sc['perf_troupe']);
                }
            })
            ->where(function ($query)use($sc){
                if(!empty($sc['perf_addr'])){
                    $query->where('perf_addr', $sc['perf_addr']);
                }
            })
            ->where(function ($query)use($sc){
                if(!empty($sc['perf_type'])){
                    $query->where('perf_type', $sc['perf_type']);
                }
            })
            ->offset($begin)
            ->limit($pageSize)
            ->with(['perfActors'=>function($query)use($sc){
                if(!empty($sc['perf_actors'])){
                    $query->whereIn('actor_id', $sc['perf_actors']);
                }
            }, 'perfDetail', 'perfType', 'perfAddr', 'perfTroupe'])
            ->orderBy('perf_id', 'desc')
            ->get();
        return $data;
    }
}
