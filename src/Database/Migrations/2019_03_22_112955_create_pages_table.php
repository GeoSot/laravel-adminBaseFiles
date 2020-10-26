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
            $table->id('id');
            $table->string('slug')->unique();
            $table->text('notes')->nullable();
            $table->text('css')->nullable();
            $table->text('javascript')->nullable();
            $table->unsignedInteger('order')->nullable();

            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('meta_tags')->nullable();

            //--Defaults
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            //--Foreign keys
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');
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
        Schema::dropIfExists('pages');
        Schema::enableForeignKeyConstraints();
    }
}
