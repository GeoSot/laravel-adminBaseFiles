<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_translations',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('locale')->index();
                $table->integer('file_id')->unsigned();

                $table->string('title')->nullable();
                $table->string('description')->nullable();
                $table->string('alt_attribute')->nullable();
                $table->string('keywords')->nullable();
                $table->unique(['file_id', 'locale']);
                $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media__file_translations');
    }
}
