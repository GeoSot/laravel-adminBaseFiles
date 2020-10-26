<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediumGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_galleries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('id');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('notes')->nullable();
            $table->text('show_details')->nullable();
            $table->nullableMorphs('related');

            //--Defaults
            $table->boolean('enabled')->default(1);
            $table->timestamps();

            //--Foreign keys
            $table->foreignId('user_id')->nullable();
            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs
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
        Schema::dropIfExists('media_galleries');
        Schema::enableForeignKeyConstraints();
    }
}
