<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO;

use App\Model\Common\Addr;
use App\Model\Common\Troupe;
use Illuminate\Http\Request;

class AddrDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);

        $troupes = Addr::where(function ($query) use ($sc){
                                if(!empty($sc['addr_name'])){
                                    $query->where('addr_name', 'like',"%".$sc['addr_name']."%");
                                }
                            })->orderBy('addr_id', 'desc');
        $data = $troupes->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $troupes->count();
        return [$data, $count];
    }
}