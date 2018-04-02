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
use App\User;

class UserDao extends CommonDao
{
    public function fetchPageWithScForRelation($request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);
        $users = User::where(function ($query) use ($sc){
            if(!empty($sc['user_name'])){
                $query->where('user_name', 'like',"%".$sc['user_name']."%");
            }
        })->orderBy('user_id', 'desc');
        $data = $users->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $users->count();
        return [$data, $count];
    }
}