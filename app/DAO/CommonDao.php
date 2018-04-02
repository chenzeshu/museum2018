<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 19:58
 */

namespace App\DAO;


use Illuminate\Http\Request;

class CommonDao
{
    public function calBeginPage(Request $request)
    {
        $page = $request->page;
        $pageSize = $request->pageSize;
        $begin = ( $page - 1 ) * $pageSize;

        return [$pageSize, $begin];
    }
}