<?php

namespace GeoSot\BaseAdmin\Database\Seeds;
use App\Models\Users\UserRole;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class PermissionsAndRolesSeeder extends BaseSeeder
{

    protected $class = UserRole::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function seedData()
    {
        $static = new $this->class();
        foreach ($this->data() as $index => $el) {
            $nameParts = preg_split('/(?=[A-Z])/', $index, -1, PREG_SPLIT_NO_EMPTY);
            $role = $static->create([
                'name'                   => $index,
                'display_name'           => ucwords(implode(' ', $nameParts)),
                'description'            => ucwords(implode(' ', $nameParts)),
                'front_users_can_see'    => Arr::get($el, 'front_users_can_see', false),
                'front_users_can_choose' => Arr::get($el, 'front_users_can_choose', false),
                'is_protected'           => Arr::get($el, 'is_protected', false),
            ]);

            $this->creatingDataMsg(ucfirst($index));
        }


        $this->seedPermissions();

    }

    /**
     * @return array
     */
    protected function data()
    {
        return [
            'god'                => [
                'is_protected' => true,
            ],
            'invoicesManager'    => [
                'front_users_can_see' => true,
                'is_protected'        => true,
            ],
            'firstLineSupporter' => [
                'front_users_can_see' => true,
                'is_protected'        => true,
            ],
            'supporter'          => [
                'front_users_can_see' => true,
                'is_protected'        => true,
            ],
            'user'               => [
                'front_users_can_see'    => true,
                'front_users_can_choose' => true,
                'is_protected'           => true,
            ],
        ];

    }

    protected function seedPermissions()
    {
        Artisan::call('baseAdmin:makePermissionsForModel', [
            'name' => 'all'
        ]);
        $this->command->line(Artisan::output());
        $this->command->info('Permissions table seeded ');
    }

    /**
     * Truncates all the model  tables
     *
     * @return    void
     */
    public function truncateTables()
    {
        $this->command->info('Truncating ' . (new $this->class ())->getTable() . ' Table');
        $this->command->info('Truncating Permissions Table');
        $this->command->info('Truncating  Tables ' . config('laratrust.tables.permission_role') . ' ' . config('laratrust.tables.permission_user') . ' ' . config('laratrust.tables.role_user'));
        Schema::disableForeignKeyConstraints();
        DB::table(config('laratrust.tables.permission_role'))->truncate();
        DB::table(config('laratrust.tables.permission_user'))->truncate();
        DB::table(config('laratrust.tables.role_user'))->truncate();
        config('baseAdmin.config.models.role')::truncate();
        config('baseAdmin.config.models.permission')::truncate();
        Schema::enableForeignKeyConstraints();
    }


}
