<?php

namespace App\Model\Perf;

use App\Model\Actor\Actor;
use App\Model\Common\Addr;
use App\Model\Common\Troupe;
use App\Model\Common\Type;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $primaryKey = "perf_id";
    protected $guarded = [];

    /**
     * 演出的三个文字详情
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function perfDetail()
    {
        return $this->hasOne(PerformanceDetail::class, 'perf_id', 'perf_id');
    }

    /**
     * 演出的演员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function perfActors()
    {
        return $this->belongsToMany(Actor::class, 'performance_actors', 'perf_id', 'actor_id');
    }

    /**
     * 演出剧种
     */
    public function perfType()
    {
        return $this->hasOne(Type::class, 'type_id', 'perf_type')
                     ->select(['type_id', 'type_name']);
    }

    /**
     * 演出地点
     */
    public function perfAddr()
    {
        return $this->hasOne(Addr::class, 'addr_id', 'perf_addr')
                     ->select(['addr_id', 'addr_name']);
    }

    /**
     * 演出剧团
     */
    public function perfTroupe()
    {
        return $this->hasOne(Troupe::class, 'troupe_id', 'perf_troupe')
                     ->select(['troupe_id', 'troupe_name']);
    }
}
