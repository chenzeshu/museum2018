<?php

use Faker\Generator as Faker;
use App\Utils\Scope;

//用户mock表
$factory->define(App\User::class, function (Faker $faker) {
    $scopes = [Scope::normal, Scope::manager];
    return [
        'user_nickname' => $faker->name,
        'user_email' => $faker->unique()->safeEmail,
        'user_phone' => $faker->unique()->phoneNumber,
        'user_pass' => bcrypt(666666),
        'user_openid' => \App\Utils\MockUtil::generate_password(32),
        'user_scope' => $faker->randomElement($scopes)
    ];
});

//演员mock表
$factory->define(\App\Model\Actor\Actor::class, function (Faker $faker){
    return [
        'actor_name' => $faker->name,
        'actor_sex' => $faker->randomElement(['男','女']),
        'actor_age' => rand(1, 50),
        'actor_record' => $faker->text(50)
    ];
});

//演出mock表
$factory->define(App\Model\Perf\Performance::class, function (Faker $faker){
    return [
        'perf_code' => \App\Utils\MockUtil::mockGetPerfCode('2018'),
        'perf_date' => $faker->dateTime(),
        'perf_type' => rand(1, 10),
        'perf_troupe' => rand(1, 10),
        'perf_address' => rand(1, 10),
        'perf_duration' => rand(50, 1000),
        'perf_size' => rand(100, 2000)
    ];
});

//演出细节表
$factory->define(\App\Model\Perf\PerformanceDetail::class, function (Faker $faker){
    return [
      'perf_content' => $faker->text(30),
      'perf_receive' => $faker->text(30),
      'perf_output' => $faker->text(30),
    ];
});

//演出的演员表
$factory->define(\App\Model\Perf\PerformanceActor::class, function (Faker $faker){
   return [
      'actor_id' => rand(1, 50)
   ] ;
});

//地址表
$factory->define(\App\Model\Common\Addr::class, function(Faker $faker){
    return [
      'addr_name' => $faker->address
    ];
});

//剧团
$factory->define(\App\Model\Common\Troupe::class, function (Faker $faker){
   return [
       'troupe_name' => $faker->city.'xx团'
   ];
});

//剧种
$factory->define(\App\Model\Common\Type::class, function (Faker $faker){
    return [
        'type_name' => $faker->company
    ] ;
});


