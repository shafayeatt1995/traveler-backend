<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
            'slug' => 'admin',
            'role_id' => '1',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'social_profile' => '{"instagram":"","facebook":"","twitter":"","whatsapp":""}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. Guide',
            'slug' => 'guide',
            'role_id' => '2',
            'email' => 'guide@guide.com',
            'password' => bcrypt('123456'),
            'social_profile' => '{"instagram":"","facebook":"","twitter":"","whatsapp":""}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. User',
            'slug' => 'user',
            'role_id' => '3',
            'email' => 'user@user.com',
            'password' => bcrypt('123456'),
            'social_profile' => '{"instagram":"","facebook":"","twitter":"","whatsapp":""}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
