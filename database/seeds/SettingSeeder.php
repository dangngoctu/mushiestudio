<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingSeeder extends Seeder
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
                'key' => 'ADDRESS',
                'value' => 'Tầng 18 Tòa nhà Sofic, số 10 đường Mai Chí Thọ, phường Thủ Thiêm, Quận 2, Tp. Hồ Chí Minh.'
            ],
            [
                'key' => 'PHONE',
                'value' => '+84-(0)8-39977.824'
            ],
            [
                'key' => 'TIME',
                'value' => '8:00~20:00'
            ],
            [
                'key' => 'EMAIL',
                'value' => 'mushie.info@mushie-studio.com'
            ],
            [
                'key' => 'WEBSITE',
                'value' => 'http://mushie-studio.com/'
            ],
            [
                'key' => 'URL_FACEBOOK',
                'value' => 'https://www.facebook.com/'
            ],
            [
                'key' => 'URL_INSTAGRAM',
                'value' => 'https://www.instagram.com/mushie-studio.vn/'
            ],
        ];

        DB::table('setting')->insert($settingData);
    }
}