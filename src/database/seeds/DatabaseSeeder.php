<?php

namespace GeoSot\BaseAdmin\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment(['production', 'live'])) {
            $this->command->info('Seeder is DISABLED on "' . app()->environment() . '" Environment');

            return;
        }
        Schema::disableForeignKeyConstraints();

        $this->call(PermissionsAndRolesSeeder::class);

        $this->call(UsersSeeder::class);


        Schema::enableForeignKeyConstraints();

    }
}
