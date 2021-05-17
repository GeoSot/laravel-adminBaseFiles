<?php

namespace GeoSot\BaseAdmin\App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MakeAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baseAdmin:makePermissionsForModel {name : The name of the Model / or PermissionName }
                                                           {--side=admin : admin|site }
                                                           {--isPermName= : flag it as true is you want to make only one permission from parsed from string}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create The additional Permissions On Admin Section For the given model';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $nameInput = lcfirst($this->argument('name'));
        $permNameMode = lcfirst($this->option('isPermName'));

        if ($permNameMode == 'true') {
            $this->createPermissionFromString($nameInput);
        } elseif ($nameInput == 'all') {
            $this->makeAllPermissions();
        } else {
            $this->createPermissionsForModel($nameInput);
        }

        $this->assignAllPermissionsToGodRole();
    }

    /**
     * @return array
     */
    protected function getPermissionsMapping()
    {
        $admin = [
            'admin.index',
            'admin.create',
            'admin.edit',
            'admin.update',
            'admin.delete',
            'admin.forceDelete',
            'admin.restore',
        ];
        $site = [
            'site.index',
            'site.create',
            'site.edit',
            'site.update',
            'site.delete',
        ];
        $side = lcfirst($this->option('side'));
        return $side === 'site' ? $site : $admin;
    }

    protected function createPermissionsForAllAdminModels()
    {

        $adminRoutes = config('baseAdmin.main.routes');
        foreach ($adminRoutes as $parentRoute => $node) {
            $this->createPermissionsForModel($parentRoute);

            if (!isset($node['menus'])) {
                continue;
            }
            foreach ($node['menus'] as $name) {
                if ($name == $parentRoute) {
                    continue;
                }
                $this->createPermissionsForModel($parentRoute . ucfirst($name));
            }

        }
    }

    /**
     * @param $nameInput
     */
    protected function createPermissionsForModel($nameInput)
    {
        $this->info('');
        foreach ($this->getPermissionsMapping() as $el) {

            $friendlyName = ucwords(str_replace('.', ' ', $el)) . ' ' . ucfirst(Str::plural($nameInput));
            $name = $el . '-' . $nameInput;
            $permission = $this->updateOrCreatePermissionModel($name, $friendlyName);
        }

    }

    protected function createPermissionFromString(string $permName)
    {
        $this->info('');

        $friendlyName = ucwords(str_replace(['.', '-'], ' ', $permName));
        $permission = $this->updateOrCreatePermissionModel($permName, $friendlyName);

    }

    protected function assignAllPermissionsToGodRole(): void
    {
        $permissions = config('baseAdmin.config.models.permission')::all()->pluck('id');
        config('baseAdmin.config.models.role')::whereName('god')->first()->syncPermissions($permissions);
        $this->info('');
        $this->info('All Permissions were Assigned to "God" Role ');
        $this->info('');
    }

    /**
     * @param string $name
     * @param string $friendlyName
     *
     * @return mixed
     */
    protected function updateOrCreatePermissionModel(string $name, string $friendlyName)
    {


        $permission = config('baseAdmin.config.models.permission')::updateOrCreate(['name' => $name], [
            'display_name' => $friendlyName,
            'description' => $friendlyName,
            //                'permission_group_id'
        ]);

        $this->info(($permission->wasRecentlyCreated ? 'Creating' : 'Existing') . '  Permission ' . $permission->name);


        return $permission;

    }

    protected function makeAllPermissions(): void
    {
        $this->createPermissionsForAllAdminModels();

        foreach (config('baseAdmin.main.extraPermissions', []) as $name => $permissions) {
            array_map(function ($permission) use ($name) {
                ($permission == 'all') ? $this->createPermissionsForModel($name) : $this->createPermissionFromString("admin.{$permission}-{$name}");
            }, Arr::wrap($permissions));
        }
        foreach (config('baseAdmin.main.customMenuItems', []) as $parentRoute => $node) {
            $this->createPermissionFromString('admin.index-' . $parentRoute);
        }
    }

}
