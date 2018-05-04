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
use Illuminate\Http\Request;

class PerformanceDao extends CommonDao
{
    /**
     * 根据条件对relation们筛选行为
     * @param $perf
     * @param $sc   搜索条件
     */
    public function fetchPageWithScForRelation(Request $request, $perf, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);

        $data = $perf->offset($begin)
                    ->limit($pageSize)
                    ->with([
                        'perfActors' => function ($query) use ($sc) {
                            if($sc['perf_actors']){
                                $query->whereIn('performance_actors.actor_id', $sc['perf_actors']);
                            }
                         },
                        'perfFiles' => function ($query) {
                            $query->where('file_type', 'photo');
                        },
                        'perfDetail', 'perfType', 'perfAddr', 'perfTroupe', 'perfBaktypes'])
                    ->orderBy('perf_id', 'desc')
                    ->get()
                    ->toArray();
        $count = $perf->count();

        if(!empty($_data = $this->filterPerfActors($data, $sc))){
            $data = $_data;
        }

        return [
            'data' => $data,
            'count' => $count
        ];
    }

    /**
     * 如果不做这个判断过滤，会将$SC['perf_actors']为空的数据也包括进来
     * @param $data
     * @param $sc
     * @return array
     */
    private function filterPerfActors($data, $sc){
        $newArray = array();
        if(!empty($sc['perf_actors'])){
            foreach ($data as $key => $value) {
                if(!empty($data[$key]['perf_actors'])){
                    $newArray[] = $data[$key];
                }
            }
        }
        return $newArray;
    }

    /**
     * 新增performance的演员
     * @param {stdClass} $perf Perfomance表本体
     * @param $request
     */
    public function addPerfActors($perf, Request $request)
    {
        $actors = $request->perf_actors;
        $perf->perfActors()->attach($actors);
    }

    /**
     * 新增perfomanceDetail
     * @param {stdClass} $perf Perfomance表本体
     * @param $request
     */
    public function addPerfDetail($perf, Request $request)
    {
        $details = $this->pakDetailsArr($request);
        $perf->perfDetail()->create($details);
    }

    /**
     * 新增备份记录映射
     * @param $perf
     * @param Request $request
     */
    public function addPerfBakType($perf, Request $request)
    {
        $perf_baktypes = $request->perf_baktypes;
        $perf->perfBaktypes()->attach($perf_baktypes);
    }

    /**
     * 新增一个performance
     */
    public function addPerfSelf(Request $request)
    {
        //todo 剔除这一步用不到的字段
        $self = $this->exceptRequest($request);
            //todo 生产编号
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
    public function updatePerfActors($perf, Request $request)
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
    public function updatePerfDetail($perf, Request $request)
    {
        $details = $this->pakDetailsArr($request);
        return $perf->perfDetail()->update($details);
    }

    /**
     * 更新备份记录映射
     * @param $perf
     * @param Request $request
     */
    public function updatePerfBakType($perf, Request $request)
    {
        $perf_baktypes = $request->perf_baktypes;
        $perf->perfBaktypes()->sync($perf_baktypes);
    }

    //todo 更新performance表
    public function updatePerfSelf($perf, Request $request)
    {
        $request = $this->exceptRequest($request);
        return $perf->update($request);
    }

    /**
     * 为更新主表，去除request的多余项
     * @param Request $request
     * @return array
     */
    private function exceptRequest(Request $request){
        $exceptions = ['perf_actors', 'perf_content', 'perf_receive', 'perf_output', 'perf_remark', 'perf_baktypes'];
        return $request->except($exceptions);
    }

    /**
     * 组装$details详情表的数组
     * @param Request $request
     * @return array
     */
    private function pakDetailsArr(Request $request){
        return [
            'perf_content' => $request->perf_content,
            'perf_receive' => $request->perf_receive,
            'perf_output' => $request->perf_output,
            'perf_remark' => $request->perf_output
        ];
    }
}