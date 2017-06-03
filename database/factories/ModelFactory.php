<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
/*$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});*/
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    static $uid=10;
    $pow=$uid==10?3:$faker->numberBetween(0,3);
    return [
        'id'=> $uid++,
//        'name' => $faker->name,
        'tel' => ($faker->unique()->phoneNumber),
        'active_point'=>500,
        'contribute_point'=>500,
        'power'=>$pow,
        'salt'=>'',
        'theme'=> 'yayin',
        'admin_name'=>($pow>0)?$faker->unique()->name:'',
        'disable'=>false,
        'password' => Hash::make('secret'),
        'no_disturb'=>false,
        'token'=>$faker->unique()->phoneNumber
//        'remember_token' => str_random(10),
    ];
});
