<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO\utils;

use App\DAO\CommonDao;
use App\Model\Common\Addr;
use Illuminate\Http\Request;

class AddrDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);

        $addrs = Addr::where(function ($query) use ($sc){
                                if(!empty($sc['addr_name'])){
                                    $query->where('addr_name', 'like',"%".$sc['addr_name']."%");
                                }
                            })->orderBy('addr_id', 'desc');
        $data = $addrs->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $addrs->count();
        return [$data, $count];
    }
}