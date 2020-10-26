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
            $table->id('id');
            $table->string('slug')->unique();
            $table->string('layout')->nullable();
            $table->string('background_color')->nullable();
            $table->boolean('has_multiple_images')->default(0);
            $table->string('css_class')->nullable();
            $table->unsignedInteger('order')->nullable();

            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('notes')->nullable();

            //--Defaults
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            //$table->softDeletes();

            //--Foreign keys
            $table->foreignId('page_area_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
            $table->foreign('page_area_id')->references('id')->on('page_areas')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null');
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
