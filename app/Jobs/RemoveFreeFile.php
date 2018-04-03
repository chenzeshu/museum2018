<?php

namespace App\Jobs;

use App\Model\Common\File;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $actor_files = DB::select("select a.file_id, a.file_path from files a where a.file_id not in (select file_id from actor_files) ");
        $perf_files = DB::select("select file_id, file_path from files where file_id not in (select file_id from performance_files) ");

        $paths = $file_ids = [];
        foreach ($actor_files as $actor_file){
            foreach ($perf_files as $perf_file){
                if($actor_file->file_id == $perf_file->file_id){
                    $paths[] = $actor_file->file_path;
                    $file_ids[] = $actor_file->file_id;
                }
            }
        }

        Storage::delete($paths);
        File::destroy($file_ids);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


    }
}
