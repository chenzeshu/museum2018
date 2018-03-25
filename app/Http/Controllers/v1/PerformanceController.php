<?php

namespace App\Http\Controllers\v1;

use App\DAO\PerformanceDao;
use App\Http\Controllers\Controller;
use App\Model\Common\Addr;
use App\Model\Common\Troupe;
use App\Model\Common\Type;
use App\Model\Perf\Performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class PerformanceController extends CommonController
{
    protected $dao;

    public function __construct(PerformanceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 分页获取数据
     */
    public function page(Request $request)
    {
        $page = $request->page;
        $pageSize = $request->pageSize;
        $begin = ($page - 1) * $pageSize;
        $data = Performance::offset($begin)
            ->limit($pageSize)
            ->with(['perfActors', 'perfDetail', 'perfType', 'perfAddr', 'perfTroupe'])
            ->orderBy('perf_id', 'desc')
            ->get()
            ->toArray();
        $count = Performance::count();
        $data = [
            'data' => $data,
            'count' => $count
        ];

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

        return $this->resSuccess('获取基本信息成功',
            ['types'=>$types, 'troupes'=>$troupes, 'addrs'=>$addrs]);
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
     */
    public function delete($perf_id)
    {
        $perf = Performance::findOrFail($perf_id);
        $perf->perfActors()->detach(); //todo 删除中间表
        $perf->perfDetail()->delete(); //todo 删除细节表
        $perf->delete(); //todo 删除自身
        return $this->resSuccess('删除成功', []);
    }
}
