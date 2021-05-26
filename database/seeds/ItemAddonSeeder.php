<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemAddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizeData = [
            [
                'name' => 37
            ],
            [
                'name' => 38
            ],
            [
                'name' => 39
            ],
            [
                'name' => 40
            ],
            [
                'name' => 41
            ],
            [
                'name' => 'L'
            ],
            [
                'name' => 'M'
            ],
            [
                'name' => 'S'
            ]
        ];
        DB::table('size')->insert($sizeData);

        $materialData = [
            [
                'name' => 'Cotton'
            ],
            [
                'name' => 'Leather'
            ],
            [
                'name' => 'Linen'
            ],
            [
                'name' => 'Silk'
            ],
            [
                'name' => 'Denim'
            ]
        ];
        DB::table('material')->insert($materialData);

        $colorlData = [
            [
                'name' => 'Red',
                'color_code' => '#FF0000'
            ],
            [
                'name' => 'Cyan',
                'color_code' => '#00FFFF'
            ],
            [
                'name' => 'Blue',
                'color_code' => '#0000FF'
            ],
            [
                'name' => 'DarkBlue',
                'color_code' => '#0000A0'
            ],
            [
                'name' => 'LightBlue',
                'color_code' => '#ADD8E6'
            ],
            [
                'name' => 'Purple',
                'color_code' => '#800080'
            ],
            [
                'name' => 'Yellow',
                'color_code' => '#FFFF00'
            ],
            [
                'name' => 'Lime',
                'color_code' => '#00FF00'
            ],
            [
                'name' => 'Magenta',
                'color_code' => '#FF00FF'
            ],
            [
                'name' => 'Black',
                'color_code' => '#000000'
            ],
            [
                'name' => 'Orange',
                'color_code' => '#FFA500'
            ]
        ];
        DB::table('color')->insert($colorlData);
    }
}