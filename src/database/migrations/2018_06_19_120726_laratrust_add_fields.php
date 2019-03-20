<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaratrustAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(config('laratrust.tables.roles'), function (Blueprint $table) {
            $table->boolean('front_users_can_see')->default(false);
            $table->boolean('front_users_can_choose')->default(false);
            $table->boolean('is_protected')->default(false);
            $table->softDeletes();
        });

        Schema::table(config('laratrust.tables.permissions'), function (Blueprint $table) {
            $table->unsignedInteger('permission_group_id')->nullable();

            $table->foreign('permission_group_id')->references('id')->on('permission_groups')->onDelete('set null');
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

        Schema::table(config('laratrust.tables.roles'), function (Blueprint $table) {
            $table->dropColumn('front_users_can_see');
            $table->dropColumn('front_users_can_choose');
            $table->dropColumn('is_protected');
            $table->dropSoftDeletes();
        });

        Schema::table(config('laratrust.tables.permissions'), function (Blueprint $table) {
            $table->dropForeign(['permission_group_id']);
            $table->dropColumn('permission_group_id');
        });

        Schema::enableForeignKeyConstraints();
    }
}
