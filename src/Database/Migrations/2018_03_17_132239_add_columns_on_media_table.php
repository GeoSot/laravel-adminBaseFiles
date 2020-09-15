<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->boolean('the_file_exists')->default(true);
            $table->string('thumb')->nullable();

            $table->text('custom_properties')->nullable();

            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('alt_attribute')->nullable();
            $table->string('keywords')->nullable();

            //--Default
            $table->foreignId('modified_by')->nullable();

        });


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['the_file_exists', 'thumb', 'custom_properties', 'title', 'description', 'alt_attribute', 'keywords', 'modified_by']);

        });


    }
}
