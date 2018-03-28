<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BaseSeed::class);  //基本信息， 上线时更改后再seed
        $this->call(FakerSeed::class); //模拟信息， 上线时不需要。
    }
}
