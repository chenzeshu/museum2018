<?php

namespace App\Http\Controllers\v1;

use App\Model\Actor\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActorController extends CommonController
{
    public function page(Request $request)
    {
        $page = $request->page;
        $pageSize = $request->pageSize;
        $begin = ( $page - 1 ) * $pageSize;

        $sc = $request->searchCondition;
        $actors = Actor::with(['troupeHistory', 'actorFiles']);
        $data = $actors
            ->offset($begin)
            ->limit($pageSize)
            ->get()
            ->toArray();
        $count = $actors->count();


        $data = [
            'actors' => $actors,
            'count' => $count
        ];

        return $this->resSuccess('获取演员成功', $data);
    }

    /**
     * 获取演员
     * 注意：由于在windows下，用不了es，所以只能用mysql低效率低命中地检索了
     * @param Request $request
     */
    public function searchActors(Request $request)
    {
        $query = $request->post('query');
        Log::info($query);
        $data = Actor::where('actor_name', "like", "%".$query."%")->get()->toArray();
        return $this->resSuccess($data);
    }
}
