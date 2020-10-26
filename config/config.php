<?php

return [

    /* ------------------------------------------------------------------------------------------------
     |  Model settings
     | ------------------------------------------------------------------------------------------------
     */
    'models' => [
        'user'       => App\Models\Users\User::class,
        'role'       => App\Models\Users\UserRole::class,
        'permission' => App\Models\Users\UserPermission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Backend Configs
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'backEnd' => [
        //base route for backend
        'baseRoute' => 'admin',
        //published assets path
        'assetsPath' => 'assets\\',
        //Add css scripts to back layout
        'extraCss' => [],
        //Add js scripts to back layout
        'extraJs' => [],
        //Set back layout
        'layout' => 'baseAdmin::admin.layout',
    ],

    'site' => [
        'baseRoute' => '',
        'extraCss'  => [],
        'extraJs'   => [],
        //Set Views options
        'layout' => 'baseAdmin::site.layout',
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Cache settings (minutes)
     | ------------------------------------------------------------------------------------------------
     */
    'cacheSettings' => [
        'enable' => true,
        'time'   => 15, //  cache time in minutes
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
        'input-locale-attribute'            => 'data-language',
        'form-group-class'                  => 'form-group-translation',
        'label-locale-indicator'            => '<span>%label%</span> <span class="ml-2 badge badge-pill badge-light">%locale%</span>',
    ],

];
