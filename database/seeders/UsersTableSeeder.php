<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'Md. Admin',
            'role_id' => '1',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. Author',
            'role_id' => '2',
            'email' => 'user@user.com',
            'password' => bcrypt('123'),
        ]);
    }
}
