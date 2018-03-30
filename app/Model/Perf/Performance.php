<?php

namespace App\Model\Perf;

use App\Model\Actor\Actor;
use App\Model\Common\Addr;
use App\Model\Common\Baktype;
use App\Model\Common\File;
use App\Model\Common\Troupe;
use App\Model\Common\Type;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $primaryKey = "perf_id";
    protected $guarded = [];

    /**
     * 关系--演出的三个文字详情
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function perfDetail()
    {
        return $this->hasOne(PerformanceDetail::class, 'perf_id', 'perf_id');
    }

    /**
     * 关系--演出的演员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function perfActors()
    {
        return $this->belongsToMany(Actor::class, 'performance_actors', 'perf_id', 'actor_id');
    }

    /**
     * 关系--演出的文件
     */
    public function perfFiles()
    {
        return $this->belongsToMany(File::class, 'performance_files', 'perf_id', 'file_id');
    }

    /**
     * 关系--演出剧种
     */
    public function perfType()
    {
        return $this->hasOne(Type::class, 'type_id', 'perf_type')
                     ->select(['type_id', 'type_name']);
    }

    /**
     * 关系--演出地点
     */
    public function perfAddr()
    {
        return $this->hasOne(Addr::class, 'addr_id', 'perf_addr')
                     ->select(['addr_id', 'addr_name']);
    }

    /**
     * 关系--演出剧团
     */
    public function perfTroupe()
    {
        return $this->hasOne(Troupe::class, 'troupe_id', 'perf_troupe')
                     ->select(['troupe_id', 'troupe_name']);
    }

    /**
     * 关系--备份类型
     */
    public function perfBaktype()
    {
        return $this->hasOne(Baktype::class, 'baktype_id', 'perf_baktype');
    }

    /**
     * scope 定义要大写，但是注意使用时全部要小写
     * @param $sc 检索条件
     */
    public function scopeFetchData($query, $sc)
    {
        return $query->where(function ($query)use($sc){
                if(!empty($sc['perf_date'])){
                    $query->whereBetween('perf_date', $sc['perf_date']);
                }
            })
            ->where(function ($query)use($sc){
                if(!empty($sc['perf_troupe'])){
                    $query->where('perf_troupe', $sc['perf_troupe']);
                }
            })
            ->where(function ($query)use($sc){
                if(!empty($sc['perf_addr'])){
                    $query->where('perf_addr', $sc['perf_addr']);
                }
            })
            ->where(function ($query)use($sc){
                if(!empty($sc['perf_type'])){
                    $query->where('perf_type', $sc['perf_type']);
                }
            });
    }
}
