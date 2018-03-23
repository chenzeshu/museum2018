<?php

use Illuminate\Database\Seeder;

class FakerSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //todo 创建额外10个用户
        factory(\App\User::class, 10)->create();
        //todo 创建100个演出
        factory(\App\Model\Perf\Performance::class, 100)->create()->each(function ($perf){
           //todo 每个演出塞一个详情
            factory(\App\Model\Perf\PerformanceDetail::class, 1)->make()->each(function ($detail) use ($perf){
                $perf->perfDetail()->save($detail);
            });
            //todo 每个演出塞5-10个演员
            factory(\App\Model\Perf\PerformanceActor::class, rand(5,10))->make()->each(function ($pactor) use ($perf){
               $perf->perfActors()->save($pactor);
            });
        });
        //todo 创建50个演员
        factory(\App\Model\Actor\Actor::class, rand(1,10))->create();
        //todo 创建10个剧团
        factory(\App\Model\Common\Troupe::class, 10)->create();
        //todo 创建10个剧种
        factory(\App\Model\Common\Type::class, 10)->create();
        //todo 创建10个地址
        factory(\App\Model\Common\Addr::class, 10)->create();
    }
}
