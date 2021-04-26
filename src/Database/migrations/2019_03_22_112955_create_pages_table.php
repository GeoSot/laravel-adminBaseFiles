<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('slug')->unique();
            $table->text('notes')->nullable();
            $table->text('css')->nullable();
            $table->text('javascript')->nullable();
            $table->unsignedInteger('order')->nullable();


            $table->text('title')->nullable();
            $table->text('sub_title')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('meta_tags')->nullable();

            //--Defaults
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            //--Foreign keys
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
            $table->foreign('parent_id')->references('id')->on('pages')->nullOnDelete();
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
        Schema::dropIfExists('pages');
        Schema::enableForeignKeyConstraints();
    }
}
