<?php
return [

    /* ------------------------------------------------------------------------------------------------
    |  Model settings
    | ------------------------------------------------------------------------------------------------
    */
    'models' => [
        'namespace' => 'App\\Models\\',
        'user' => App\Models\Users\User::class,
        'role' => App\Models\Users\UserRole::class,
        'permission' => App\Models\Users\UserPermission::class,
        'setting' => App\Models\Setting::class
    ],


    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the Paclage routes.
    |
    */
    'backEnd' => [
        'baseRoute' => 'admin',
        'assetsPath' => 'assets\\geo-sot\\base-admin',
        'extraCss' => [],
        'extraJs' => [],
        //Set Views options
        'layout' => 'baseAdmin::admin.layout',
    ],


    'site' => [
        'baseRoute' => '',
        'extraCss' => [],
        'extraJs' => [],
        //Set Views options
        'layout' => 'baseAdmin::site.layout',
    ],


    /* ------------------------------------------------------------------------------------------------
	 |  Cache settings (minutes)
	 | ------------------------------------------------------------------------------------------------
	 */
    'cacheSettings' => [
        'enable' => true,
        'time' => 15,//  cache time in minutes
    ],


    'translatables' => [
        /*
        | Locales
        |
        | Contains an array with the applications available locales.
        |
        */
        'locales' => [
            'en',
            'el',
        ],

        'enable-TranslatableFields-OnModel' => true,
        'input-locale-attribute' => 'data-language',
        'form-group-class' => 'form-group-translation',
        'label-locale-indicator' => '<span>%label%</span> <span class="ml-2 badge badge-pill badge-light">%locale%</span>'
    ],

];
