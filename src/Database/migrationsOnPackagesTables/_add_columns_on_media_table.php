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

            $table->string('title')->nullable();
            $table->string('alt_attribute')->nullable();
            $table->string('keywords')->nullable();
            //--Foreign keys
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
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
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['the_file_exists', 'title', 'alt_attribute', 'keywords','user_id','modified_by']);
        });


    }
}
