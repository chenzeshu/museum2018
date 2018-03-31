<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/31 0031
 * Time: 17:11
 */

namespace App\DAO;


class ActorDao
{
    public function fetchPageWithScForRelation($request, $actors, $sc)
    {
        $page = $request->page;
        $pageSize = $request->pageSize;
        $begin = ( $page - 1 ) * $pageSize;

        $data = $actors->with(['actorTroupeHistories',
                               'actorFiles' => function($query) use ($sc){
                                    $query->where('file_type', 'photo');
                                }])
                        ->orderBy('actor_id', 'desc')
                        ->offset($begin)
                        ->limit($pageSize)
                        ->get()
                        ->toArray();
        $count = $actors->count();

        return ['data' => $data, 'count' => $count];
    }

    /**
     * 根据前端传来的布尔值same决定是否更新剧团历史
     * @param $request
     * @param $actor
     */
    public function updateTroupeOrNot($request, $actor)
    {
        $same = $request->same;
        $troupeNow = $request->actor_troupeNow;
        if(!$same){
            //注意：由于是历史，所以即使是更新，也是attach（例如!same时又回到了老地方）
            $actor->actorTroupeHistories()->attach($troupeNow['troupe_id'], ['updated_at'=>date('Y-m-d H:i:s', time())]);
        }
    }
}