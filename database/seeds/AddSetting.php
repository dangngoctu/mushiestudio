<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingData = [
            [
                'key' => 'FILE',
                'value' => '/assets/app/page/user/images/184280271_467031527861915_2274740756541267454_n.jpg'
            ],
            [
                'key' => 'CONTENT',
                'value' => 'Vietnam fashion brand cultivated on the principles of confidence, femininity, and individuality.'
            ],
            [
                'key' => 'TITLE',
                'value' => 'MUSHIE STUDIO'
            ]
        ];

        DB::table('setting')->insert($settingData);

    }
}