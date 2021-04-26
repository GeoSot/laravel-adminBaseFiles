`<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePageAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_areas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('slug')->unique();
            $table->string('css_class')->nullable();
            $table->string('background_color')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('order')->nullable();

            $table->text('title')->nullable();
            $table->text('sub_title')->nullable();


            //--Defaults
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
            //$table->softDeletes();

            //--Foreign keys
            $table->foreignId('page_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
            $table->foreign('page_id')->references('id')->on('pages')->nullOnDelete();
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
        Schema::dropIfExists('page_areas');
        Schema::enableForeignKeyConstraints();
    }
}
