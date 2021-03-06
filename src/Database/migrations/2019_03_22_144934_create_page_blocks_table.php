<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePageBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('slug')->unique();
            $table->string('layout')->nullable();
            $table->string('background_color')->nullable();
            $table->boolean('has_multiple_images')->default(0);
            $table->string('css_class')->nullable();
            $table->unsignedInteger('order')->nullable();

            $table->text('title')->nullable();
            $table->text('sub_title')->nullable();
            $table->text('notes')->nullable();


            //--Defaults
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
            //$table->softDeletes();

            //--Foreign keys
            $table->foreignId('page_area_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
            $table->foreign('page_area_id')->references('id')->on('page_areas')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('modified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('page_blocks');
        Schema::enableForeignKeyConstraints();
    }
}
