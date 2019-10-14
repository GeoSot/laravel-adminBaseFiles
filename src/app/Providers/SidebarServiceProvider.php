<?php

namespace GeoSot\BaseAdmin\App\Providers;


use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SidebarServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view) {
            //
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        dd($this->ff());
        //
    }

    protected function ff()
    {
        $routes = collect(config('baseAdmin.main.routes'))->sortBy('order');
        $customMenuItems = collect(config('baseAdmin.main.customMenuItems'))->sortBy('order');

        //MERGE THE TWO COLLECTION So WE CAN PUT THEM IN ORDER
        //**************
        $mergeCollection = collect([]);

        $routes->each(function (array $item, string $key) use ($mergeCollection) {
            $mergeCollection->put($key, $this->getMenuItemData($key, $item, false));
        });
        $customMenuItems->each(function (array $item, string $key) use ($mergeCollection) {
            $mergeCollection->put($key, $this->getMenuItemData($key, $item, true));
        });

        return $mergeCollection->sortBy('order');
    }

    protected function getMenuItemData(string $keyName, array $item, bool $isCustomMenu): array
    {
        $data = [
            'isCustomMenu' => $isCustomMenu,
            'order' => data_get($item, 'order', 9999),
            'parentPermission' => "admin.index-{$keyName}",
            'parentPlural' => Arr::get($item, 'plural', Str::plural($keyName)),
            'hasInnerMenus' => Arr::has($item, 'menus'),
            'isExcludedFromConfFile' => in_array($keyName, Arr::get($item, 'excludeFromSideBar', []))
        ];

        $canSeeMenu = (auth()->user()->can($data['parentPermission']) and !$data['isExcludedFromConfFile']);
        if ($data['hasInnerMenus']) {
            $children = $isCustomMenu ? array_keys($item['menus']) : array_diff($item['menus'], [$keyName]);
            $childPermissions = array_map(function ($child) use ($keyName) {
                return 'admin.index-'.$keyName.ucfirst($child);
            }, $children);

            $canSeeMenu = auth()->user()->can(array_merge([$data['parentPermission']], $childPermissions));
        }
        return array_merge([$item, $data, ['canSeeMenu' => $canSeeMenu]]);
    }

    public function compose(View $view)
    {
        $view->with('ViewComposerTestVariable', "Calling with View Composer Provider");
    }
}
