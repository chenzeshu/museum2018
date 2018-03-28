<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RemoveFreeFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //todo 凌晨1点-凌晨4点 or 其他时间 or 按钮触发队列 ， 因为前期不是服务器，是个人PC啊！
        //todo 遍历文件表的每个文件，如果无法在演出-文件 or 演员-文件表找到对应的， 就删除
        //todo 缺点是没有之前的求差集+前端删改直接删改文件那样负担小，但是非常适合小项目。
    }
}
