<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO\utils;

use App\DAO\CommonDao;
use App\Model\Common\Type;
use Illuminate\Http\Request;

class TypeDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);
        $types = Type::where(function ($query) use ($sc){
                                if(!empty($sc['type_name'])){
                                    $query->where('type_name', 'like',"%".$sc['type_name']."%");
                                }
                            })->orderBy('type_id', 'desc');
        $data = $types->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $types->count();
        return [$data, $count];
    }
}