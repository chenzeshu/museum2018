<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 22:31
 */

namespace App\DAO;


use App\Model\Common\Record;
use App\Model\Perf\Performance;

class PerformanceDao
{
    /**
     * 新增performance的演员
     * @param {stdClass} $perf Perfomance表本体
     * @param $request
     */
    public function addPerfActors($perf, $request)
    {
        $actors = $request->perf_actors;
        $perf->perfActors()->attach($actors);
    }

    /**
     * 新增perfomanceDetail
     * @param {stdClass} $perf Perfomance表本体
     * @param $request
     */
    public function addPerfDetail($perf, $request)
    {
        $details = [
            'perf_content' => $request->perf_content,
            'perf_receive' => $request->perf_receive,
            'perf_output' => $request->perf_output
        ];
        $perf->perfDetail()->create($details);
    }

    /**
     * 新增一个performance
     */
    public function addPerfSelf($request)
    {
        $self = $request->except(['perf_actors', 'perf_content', 'perf_receive', 'perf_output']);
        $perf_code = $this->producePerfCode();
        $self = Performance::create(array_merge($self, ['perf_code' => $perf_code]));
        return $self;
    }

    /**
     * 制造演出序号
     * @return string
     */
    private function producePerfCode(){
        $year = date('Y', time());          //todo 当年年份全写
        $record = Record::findOrFail(1);            //todo 得到序号记录
        $number = ++ $record->record_number;
        $record->increment('record_number');        //todo 自增1
        return intval($year.$number);
    }


    //todo 更新演员
    public function updatePerfActors($perf, $request)
    {
        return $perf->perfActors()->sync($request->perf_actors);
    }

    //todo 更新演出内容、接收记录、输出记录
    public function updatePerfDetail($perf, $request)
    {
        $details = [
            'perf_content' => $request->perf_content,
            'perf_receive' => $request->perf_receive,
            'perf_output' => $request->perf_output
        ];
        return $perf->perfDetail()->update($details);
    }

    //todo 更新performance表
    public function updatePerfSelf($perf, $request)
    {
        $request = $request->except(['perf_actors', 'perf_content', 'perf_receive', 'perf_output']);
        return $perf->update($request);
    }
}