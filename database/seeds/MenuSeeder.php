<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuData = [
            [
                'name' => 'Women',
                'url' => 'women'
            ],
            [
                'name' => 'Men',
                'url' => 'men'
            ],
            [
                'name' => 'Category',
                'url' => 'category'
            ]
        ];

        DB::table('menu')->insert($menuData);

        $categoryData = [
            [
                'name' => 'Dress',
                'url' => 'dress',
                'menu_id' => 1,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Jean',
                'url' => 'jean',
                'menu_id' => 1,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Short Jean',
                'url' => 'short-jean',
                'menu_id' => 1,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Shirt',
                'url' => 'shirt',
                'menu_id' => 1,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Jean',
                'url' => 'jean',
                'menu_id' => 2,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Short Jean',
                'url' => 'short-jean',
                'menu_id' => 2,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Shirt',
                'url' => 'shirt',
                'menu_id' => 2,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Ear Ring',
                'url' => 'ear-ring',
                'menu_id' => 3,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Glass',
                'url' => 'glass',
                'menu_id' => 3,
                'type_id' => 1,
                'video' => '',
            ],
            [
                'name' => 'Shoe',
                'url' => 'shoe',
                'menu_id' => 3,
                'type_id' => 1,
                'video' => '',
            ]
        ];

        // DB::table('category')->insert($categoryData);
    }
}