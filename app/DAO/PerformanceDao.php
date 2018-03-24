<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 22:31
 */

namespace App\DAO;


class PerformanceDao
{
    //todo 更新演员
    public function updateActors($perf, $request)
    {
        return $perf->perfActors()->sync($request->perf_actors);
    }

    //todo 更新演出内容、接收记录、输出记录
    public function updateDetail($perf, $request)
    {
        $details = [
            'perf_content' => $request->perf_content,
            'perf_receive' => $request->perf_receive,
            'perf_output' => $request->perf_output
        ];
        return $perf->perfDetail()->update($details);
    }

    //todo 更新performance表
    public function updateSelf($perf, $request)
    {
        $request = $request->except(['perf_actors', 'perf_content', 'perf_receive', 'perf_output']);
        return $perf->update($request);
    }
}