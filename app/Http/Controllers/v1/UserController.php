<?php

namespace App\Http\Controllers\v1;

use App\DAO\UserDao;
use App\User;
use Illuminate\Http\Request;

class UserController extends CommonController
{
    protected $dao;
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 根据展现用户列表
     */
    public function page(Request $request)
    {
        $sc = $request->searchCondition;
        list($data, $count) = $this->dao->fetchPageWithScForRelation($request, $sc);

        $data = [
            'data' => $data,
            'count' => $count
        ];

        return $this->resSuccess('获取用户列表成功', $data);
    }

    public function store(Request $request)
    {
        User::create($request->all());

        return $this->resSuccess('新增成功', []);
    }

    public function update(Request $request)
    {
        User::findOrFail($request->user_id)->update([
            'user_name' => $request->user_name,
            'user_pass' => bcrypt($request->user_pass)
        ]);

        return $this->resSuccess('更新成功', []);
    }

    public function delete($user_id)
    {
        User::destroy($user_id);
        return $this->resSuccess('删除成功', []);
    }


}
