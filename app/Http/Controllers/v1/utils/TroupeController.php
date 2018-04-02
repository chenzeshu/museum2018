<?php

namespace App\Http\Controllers\v1\utils;

use App\DAO\TroupeDao;
use App\Http\Controllers\v1\CommonController;
use App\Model\Common\Troupe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TroupeController extends CommonController
{
    protected $dao;
    public function __construct(TroupeDao $dao)
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

        return $this->resSuccess('获取剧团信息成功', $data);
    }

    public function store(Request $request)
    {
        Troupe::create($request->all());

        return $this->resSuccess('新增成功', []);
    }

    public function update(Request $request)
    {
        Troupe::findOrFail($request->troupe_id)->update([
            'troupe_name' => $request->troupe_name
        ]);

        return $this->resSuccess('更新成功', []);
    }

    public function delete($troupe_id)
    {
        Troupe::destroy($troupe_id);
        return $this->resSuccess('删除成功', []);
    }
}
