<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO\utils;


use App\DAO\CommonDao;
use App\Model\Common\Baktype;
use Illuminate\Http\Request;
class BakTypeDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);

        $baktypes = Baktype::where(function ($query) use ($sc){
                                if(!empty($sc['baktype_name'])){
                                    $query->where('baktype_name', 'like',"%".$sc['baktype_name']."%");
                                }
                            })->orderBy('baktype_id', 'desc');
        $data = $baktypes->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $baktypes->count();
        return [$data, $count];
    }
}