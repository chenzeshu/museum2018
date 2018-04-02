<?php

namespace App\Http\Controllers\v1\utils;


use App\DAO\utils\BakTypeDao;
use App\Http\Controllers\v1\CommonController;
use App\Model\Common\Baktype;
use Illuminate\Http\Request;

class BakTypeController extends CommonController
{
    protected $dao;
    public function __construct(BakTypeDao $dao)
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

        return $this->resSuccess('获取备份列表成功', $data);
    }

    public function store(Request $request)
    {
        Baktype::create($request->all());

        return $this->resSuccess('新增成功', []);
    }

    public function update(Request $request)
    {
        Baktype::findOrFail($request->baktype_id)->update([
            'baktype_name' => $request->baktype_name
        ]);

        return $this->resSuccess('更新成功', []);
    }

    public function delete($baktype_id)
    {
        Baktype::destroy($baktype_id);
        return $this->resSuccess('删除成功', []);
    }
}
