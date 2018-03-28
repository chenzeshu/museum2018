<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BaseSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //用户
        DB::table('users')->insert([
            'user_id' => 1,
            'user_nickname' => '管理员1号',
            'user_phone' => '18500005555',
            'user_pass' => bcrypt(666666),
            'user_email' => '1@1.com',
            'user_openid' => \App\Utils\MockUtil::generate_password(32),
            'user_scope' => \App\Utils\Scope::manager
        ]);

        //备份类型
        DB::table('baktypes')->insert([
            ['baktype_name' => '存储器'],
            ['baktype_name' => '硬盘']
        ]);

        DB::table('records')->insert([
            'record_type'=>1,
            'record_number'=>0,
        ]);
    }
}
