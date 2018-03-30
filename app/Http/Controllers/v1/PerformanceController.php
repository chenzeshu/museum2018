<?php

namespace App\Http\Controllers\v1;

use App\DAO\PerformanceDao;
use App\Model\Common\Addr;
use App\Model\Common\Baktype;
use App\Model\Common\Troupe;
use App\Model\Common\Type;
use App\Model\Perf\Performance;
use Illuminate\Http\Request;

class PerformanceController extends CommonController
{
    protected $dao;

    public function __construct(PerformanceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * (根据条件)分页获取数据
     */
    public function page(Request $request)
    {
        $sc = $request->searchCondition;
        $perfs = Performance::fetchdata($sc);
        $data = $this->dao->fetDataAndCountWithPage($request, $perfs, $sc);

        return $this->resSuccess('获取演出信息成功', $data);
    }

    /**
     * 获取演出的基本信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchBaseData()
    {
        $types = Type::all(['type_id','type_name'])->toArray();
        $troupes = Troupe::all(['troupe_id', 'troupe_name'])->toArray();
        $addrs = Addr::all(['addr_id', 'addr_name'])->toArray();
        $baktypes = Baktype::all(['baktype_id', 'baktype_name'])->toArray();
        return $this->resSuccess('获取基本信息成功',
            ['types'=>$types, 'troupes'=>$troupes, 'addrs'=>$addrs, "baktypes" => $baktypes]);
    }

    /**
     * 新增
     */
    public function store(Request $request)
    {
        $perf = $this->dao->addPerfSelf($request);
        $this->dao->addPerfActors($perf, $request);
        $this->dao->addPerfDetail($perf, $request);
        return $this->resSuccess('新增成功', []);
    }

    /**
     * 更新
     */
    public function update(Request $request)
    {
        $perf = Performance::findOrFail($request->perf_id);
        $re1 = $this->dao->updatePerfActors($perf, $request);
        $re2 = $this->dao->updatePerfDetail($perf, $request);
        $re = $this->dao->updatePerfSelf($perf, $request);
        return $re&&$re1&&$re2 ? $this->resSuccess() : $this->resError();
    }

    /**
     * 删除
     * fixme 还有删除文件没有做----只删关系
     */
    public function delete($perf_id)
    {
        $perf = Performance::findOrFail($perf_id);
        $perf->perfActors()->detach(); //todo 删除演员中间表关联数据
        $perf->perfDetail()->delete(); //todo 删除细节表
        $perf->perfFiles()->delete(); //todo 删除文件中间表关联数据
        $perf->delete(); //todo 删除自身
        return $this->resSuccess('删除成功', []);
    }

    /**
     * 其实是文件关联
     */
    public function upload(Request $request)
    {
        Performance::findOrFail($request->perf_id)->perfFiles()->sync($request->photoList);
        return $this->resSuccess('文件上传成功', []);
    }
}
