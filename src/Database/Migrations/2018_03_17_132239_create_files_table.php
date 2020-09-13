<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('id');
            $table->string('type');
            $table->nullableMorphs('model');
//            $table->string('title')->nullable();
            $table->string('collection_name');
            $table->string('file');
            $table->text('disk')->nullable();
            $table->boolean('the_file_exists')->default(true);
            $table->string('thumb')->nullable();
            $table->decimal('size_mb', 13, 4)->nullable();
            $table->string('mime_type');
            $table->string('extension')->nullable();
            $table->unsignedInteger('order')->nullable();
            $table->text('custom_properties')->nullable();

            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('alt_attribute')->nullable();
            $table->string('keywords')->nullable();

            //--Default
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
            $table->foreignId('modified_by')->nullable();

        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->foreignId('media_id')->index();
            $table->morphs('mediable');

            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
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
        Schema::dropIfExists('media');
        Schema::dropIfExists('mediables');
        Schema::enableForeignKeyConstraints();
    }
}
