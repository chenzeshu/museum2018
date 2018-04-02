<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO\utils;

use App\DAO\CommonDao;
use App\Model\Common\Troupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TroupeDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);
        $troupes = Troupe::where(function ($query) use ($sc){
                                if(!empty($sc['troupe_name'])){
                                    $query->where('troupe_name', 'like',"%".$sc['troupe_name']."%");
                                }
                            })->orderBy('troupe_id', 'desc');
        $data = $troupes->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $troupes->count();
        return [$data, $count];
    }
}