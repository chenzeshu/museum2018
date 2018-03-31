<?php

namespace App\Http\Controllers\v1;

use App\DAO\ActorDao;
use App\Model\Actor\Actor;
use App\Model\Common\Troupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActorController extends CommonController
{
    protected $dao;
    public function __construct(ActorDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 分页与筛选
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function page(Request $request)
    {
        $sc = $request->searchCondition;
        $actors = Actor::fetchdata($sc);    //也需要用到条件，对自身表的字段进行筛选
        $data = $this->dao->fetchPageWithScForRelation($request, $actors, $sc);

        return $this->resSuccess('获取演员成功', $data);
    }

    /**
     * 获取基本信息
     * 剧团
     */
    public function fetchBaseData()
    {
        $troupes = Troupe::all(['troupe_id', 'troupe_name'])->toArray();
        return $this->resSuccess('获取基本信息成功', ['troupes' => $troupes]);
    }

    /**
     * 新增演员
     * fixme：没有做表单校验
     */
    public function store(Request $request)
    {
        $troupeNow = $request->actor_troupeNow;

        $actor = Actor::create($request->except(['actor_troupeNow']));

        $actor->actorTroupeHistories()->attach($troupeNow['troupe_id']);

        return $this->resSuccess('新增演员成功', []);
    }

    /**
     * 新增暂时不提供个人剧团历史的增删改查：因为数据量还小
     * 后面升级为类似套餐的那个样子
     * @param Request $request
     */
    public function update(Request $request)
    {
        $actor = Actor::findOrFail($request->actor_id);

        $this->dao->updateTroupeOrNot($request, $actor);

        $actor->update($request->except(['actor_troupeNow', 'same']));

        return $this->resSuccess('修改演员成功', []);
    }

    /**
     * 删除自身及相关
     * @param $actor_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($actor_id)
    {
        $actor = Actor::findOrFail($actor_id);
        $actor->actorFiles()->detach();             //todo 删除相关文件关系
        $actor->actorTroupeHistories()->detach();   //todo 删除待过的剧团历史
        $actor->delete();                           //todo 删除自身

        return $this->resSuccess('删除成功', []);
    }
    
    /**
     * 获取演员
     * 注意：由于在windows下，用不了es，所以只能用mysql低效率低命中地检索了
     * @param Request $request
     */
    public function searchActors(Request $request)
    {
        $query = $request->post('query');

        $data = Actor::where('actor_name', "like", "%".$query."%")->get()->toArray();

        return $this->resSuccess($data);
    }

    /**
     * 其实是文件关联
     */
    public function upload(Request $request)
    {
        Actor::findOrFail($request->actor_id)->actorFiles()->sync($request->photoList, ['updated_at'=>date('Y-m-d H:i:s', time())]);
        return $this->resSuccess('文件上传成功', []);
    }
}
