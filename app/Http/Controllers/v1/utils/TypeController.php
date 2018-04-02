<?php

namespace App\Http\Controllers\v1\utils;

use App\DAO\utils\TypeDao;
use App\Http\Controllers\v1\CommonController;
use App\Model\Common\Type;
use Illuminate\Http\Request;

class TypeController extends CommonController
{
    protected $dao;
    public function __construct(TypeDao $dao)
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

        return $this->resSuccess('获取剧种信息成功', $data);
    }

    public function store(Request $request)
    {
        Type::create($request->all());

        return $this->resSuccess('新增成功', []);
    }

    public function update(Request $request)
    {
        Type::findOrFail($request->type_id)->update([
            'type_name' => $request->type_name
        ]);

        return $this->resSuccess('更新成功', []);
    }

    public function delete($type_id)
    {
        Type::destroy($type_id);
        return $this->resSuccess('删除成功', []);
    }
}
