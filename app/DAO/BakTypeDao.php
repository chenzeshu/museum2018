<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO;


use App\Model\Common\Baktype;
use App\Model\Common\Troupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BakTypeDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);

        $troupes = Baktype::where(function ($query) use ($sc){
                                if(!empty($sc['baktype_name'])){
                                    $query->where('baktype_name', 'like',"%".$sc['baktype_name']."%");
                                }
                            })->orderBy('baktype_id', 'desc');
        $data = $troupes->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $troupes->count();
        return [$data, $count];
    }
}