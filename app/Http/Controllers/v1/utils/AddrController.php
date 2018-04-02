<?php

namespace App\Http\Controllers\v1\utils;

use App\DAO\utils\AddrDao;
use App\Http\Controllers\v1\CommonController;
use App\Model\Common\Addr;
use Illuminate\Http\Request;

class AddrController extends CommonController
{
    protected $dao;
    public function __construct(AddrDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 根据条件获取分页信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function page(Request $request)
    {
        $sc = $request->searchCondition;
        list($data, $count) = $this->dao->fetchPageWithScForRelation($request, $sc);

        $data = [
            'data' => $data,
            'count' => $count
        ];

        return $this->resSuccess('获取演出地点信息成功', $data);
    }

    public function store(Request $request)
    {
        Addr::create($request->all());

        return $this->resSuccess('新增成功', []);
    }

    public function update(Request $request)
    {
        Addr::findOrFail($request->addr_id)->update([
            'addr_name' => $request->addr_name
        ]);

        return $this->resSuccess('更新成功', []);
    }

    public function delete($addr_id)
    {
        Addr::destroy($addr_id);
        return $this->resSuccess('删除成功', []);
    }
}
