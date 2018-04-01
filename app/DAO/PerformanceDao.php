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
     * 根据条件对relation们筛选行为
     * @param $perf
     * @param $sc   搜索条件
     */
    public function fetchPageWithScForRelation($request, $perf, $sc)
    {
        $pageSize = $request->pageSize;
        $begin = ($request->page - 1) * $pageSize;
        $data = $perf->offset($begin)
                    ->limit($pageSize)
                    ->with([
                        'perfActors'=>function($query)use($sc){
                            if(!empty($sc['perf_actors'])){
                                $query->whereIn('actor_id', $sc['perf_actors']);
                            }
                         },
                        'perfFiles'=>function($query){
                            $query->where('file_type', 'photo');
                        },
                        'perfDetail', 'perfType', 'perfAddr', 'perfTroupe', 'perfBaktype'])
                    ->orderBy('perf_id', 'desc')
                    ->get()
                    ->toArray();;
        $count = $perf->count();

        return [
            'data' => $data,
            'count' => $count
        ];
    }
    
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
        $number = $this->padZero($number);          //左补零
        $record->increment('record_number');        //todo 自增1
        return intval($year.$number);
    }

    /**
     * 补0函数
     */
    private function padZero($number){
        $adder = "";
        switch (strlen($number)){
            case 1:
                $adder = "00";
                break;
            case 2:
                $adder = "0";
                break;
            default:
                break;
        }
        return $adder.$number;
    }

    /**todo 更新演员
     * $request->perf_actors 是个数组
     */
    public function updatePerfActors($perf, $request)
    {
        $actors = collect($request->perf_actors)->toArray();
        $newActors = [];
        //参照文档，蛋疼的格式
        foreach ($actors as $k => $actor){
            $newActors[$actors[$k]] = ['updated_at'=>date('Y-m-d H:i:s', time())];
        }
        return $perf->perfActors()->sync($newActors);
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