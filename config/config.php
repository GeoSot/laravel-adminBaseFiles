<?php

use GeoSot\BaseAdmin\Helpers\Base;

return [

    /* ------------------------------------------------------------------------------------------------
     |  Model settings
     | ------------------------------------------------------------------------------------------------
     */
    'models' => [
        'user' => App\Models\Users\User::class,
        'role' => App\Models\Users\UserRole::class,
        'permission' => App\Models\Users\UserPermission::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Backend Configs
    |--------------------------------------------------------------------------
    |
    |
    */
    'backEnd' => [
        //base route for backend
        'routePrefix' => 'admin',
        //published assets path
        'assetsPath' => 'vendor\baseAdmin\\',// public_path('vendor\baseAdmin\\'),
        //Add css scripts to back layout
        'extraCss' => [],
        //Add js scripts to back layout
        'extraJs' => [],
        //Set back layout
        'layout' => 'baseAdmin::admin.layout',
    ],

    /*
    |--------------------------------------------------------------------------
    | FrontEnd Configs
    |--------------------------------------------------------------------------
    |
    |
    */
    'site' => [
        'styles' => [
            Base::adminAssets("css/app.css")
        ],
        'scripts' => [
            Base::adminAssets("js/app.js")
        ],
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

    /*
    |--------------------------------------------------------------------------
    | Translation Options
    |--------------------------------------------------------------------------
    |
    |
    */
    'translatables' => [
        /*
        | Locales
        | an array with the applications available locales.
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
