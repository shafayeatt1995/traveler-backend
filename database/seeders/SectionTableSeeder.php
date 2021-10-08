<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->insert([
            'name' => 'header',
            'info' => '{"phone":"+8801234-567890","email":"traveler@traveler.com","image":"images\/layouts\/header\/header-logo-1630377127.png"}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sections')->insert([
            'name' => 'footer',
            'info' => '{"image":"images\/layouts\/footer\/footer-logo-1630376546.png","message":"Nulla quis lorem ut libero malesuada feugiat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.","newsletterMessage":"Curabitur aliquet quam id dui posuere blandit. Cras ultricies ligula sed magna dictum porta. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Vivamus suscipit tortor eget felis porttitor volutpat. Donec.","copyright":"Copyright 2021 & Design By AmiAnik","address":"Kirtipur Naogaon, Dhaka","social":{"facebook":"www.facebook.com","twitter":"www.twitter.com","instagram":"www.instagram.com","whatsapp":"+8801728293635"},"phone":["01234-567890","09876-543210"],"email":["traveler@traveler.com","help@traveler.com"]}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sections')->insert([
            'name' => 'homeSlider',
            'info' => '{"id":[]}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sections')->insert([
            'name' => 'breadcrumb',
            'info' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sections')->insert([
            'name' => 'achievement',
            'info' => '{"title":"","subTitle":"","achievements":[]}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sections')->insert([
            'name' => 'review',
            'info' => '{"title":"","subTitle":"","reviews":[]}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
