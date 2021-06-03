<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('name')->nullable();
            $table->string('sub_name')->default('');
            $table->string('slug')->nullable();
            $table->string('price')->default(0);
            $table->longtext('description')->nullable();
            $table->longtext('farbrics')->nullable();
            $table->longtext('detail')->nullable();
            $table->tinyInteger('is_hot')->default(0);
            $table->string('img_thumb')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->timestamps();
        });

        Schema::create('category_images', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('url')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->timestamps();
        });

        Schema::create('item_images', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('url')->nullable();
            $table->bigInteger('item_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_images');
        Schema::dropIfExists('item_images');
        Schema::dropIfExists('item');
    }
}
