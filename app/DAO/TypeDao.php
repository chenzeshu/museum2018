<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:59
 */

namespace App\DAO;


use App\Model\Common\Troupe;
use App\Model\Common\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TypeDao extends CommonDao
{
    public function fetchPageWithScForRelation(Request $request, $sc)
    {
        list($pageSize, $begin) = $this->calBeginPage($request);
        $troupes = Type::where(function ($query) use ($sc){
                                if(!empty($sc['type_name'])){
                                    $query->where('type_name', 'like',"%".$sc['type_name']."%");
                                }
                            })->orderBy('type_id', 'desc');
        $data = $troupes->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $troupes->count();
        return [$data, $count];
    }
}