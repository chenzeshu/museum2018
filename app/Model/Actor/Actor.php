<?php

namespace App\Model\Actor;

use App\Model\Common\File;
use App\Model\Common\Troupe;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $primaryKey = "actor_id";
    protected $guarded = [];

    /**
     * 拿到演员现在在的剧团及剧团历史
     * 这是一个中间表关系
     */
    public function actorTroupeHistories()
    {
       return $this->belongsToMany(Troupe::class, 'actor_troupe_histories', 'actor_id', 'troupe_id')
           ->select(
               'troupes.troupe_id as troupe_id',
               'troupes.troupe_name as troupe_name',
               'actor_troupe_histories.updated_at as history_time'
           );
    }

    /**
     * 演员--文件
     * 这是一个中间表关系
     */
    public function actorFiles()
    {
       return $this->belongsToMany(File::class, 'actor_files', 'actor_id', 'file_id');
    }

 /**** scope ****/

    /**
     * 根据条件筛选
     * @param $query
     * @param $sc   条件
     */
    public function scopeFetchData($query, $sc)
    {
        return $query->where(function ($query) use ($sc){
                        if(!empty($sc['actor_age'])){
                            $query->whereBetween('actor_age', $sc['actor_age']);
                        }
                })->where(function ($query) use ($sc){
                        if(!empty($sc['actor_sex'])){
                            $query->where('actor_sex', $sc['actor_sex']);
                        }
                })->where(function ($query) use ($sc){
                        if(!empty($sc['actor_name'])){
                            $query->where('actor_name', 'like', "%".$sc['actor_name']."%");
                        }
                });
    }

}
