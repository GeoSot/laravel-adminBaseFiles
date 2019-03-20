<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->morphs('model');
//            $table->string('title')->nullable();
            $table->string('collection_name');
            $table->string('file');
            $table->text('disk')->nullable();
            $table->boolean('the_file_exists')->default(true);
            $table->string('thumb')->nullable();
            $table->decimal('size_mb', 13, 4)->nullable();
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->unsignedInteger('order')->nullable();
            $table->text('custom_properties')->nullable();

            //--Default
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
            $table->integer('modified_by')->unsigned()->nullable();
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
        Schema::dropIfExists('images');
        Schema::enableForeignKeyConstraints();
    }
}
