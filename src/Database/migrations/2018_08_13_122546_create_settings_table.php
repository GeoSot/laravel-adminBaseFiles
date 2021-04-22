<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id('id');
            $table->string('slug')->unique();
            $table->string('key');
            $table->string('group')->nullable();
            $table->string('sub_group')->nullable();
            $table->text('value')->nullable();
            $table->string('type');
            $table->text('notes')->nullable();
            $table->nullableMorphs('model');


            //--Defaults
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            //--Foreign keys

            $table->foreignId('modified_by')->nullable();

            //--Foreign keys RULEs

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
        Schema::dropIfExists('settings');
        Schema::enableForeignKeyConstraints();
    }
}
