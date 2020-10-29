<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'mobile' => '13888888888',
            'password' => Hash::make('aa123456'),
            'nickname' => \Illuminate\Support\Str::random(10),
            'avatar' => '',
            'gender' => 0,
            'created_at' => time()
        ]);
    }
}
