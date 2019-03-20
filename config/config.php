<?php
return [

    /* ------------------------------------------------------------------------------------------------
    |  Model settings
    | ------------------------------------------------------------------------------------------------
    */
    'models'  => [
        'namespace'  => 'App\\Models\\',
        'user'       => App\Models\Users\User::class,
        'role'       => App\Models\Users\UserRole::class,
        'permission' => App\Models\Users\UserPermission::class,
        'setting'    => App\Models\Setting::class
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
        //Set Views options
        'layout'    => 'baseAdmin::admin.layout',
    ],


    'site' => [
        'baseRoute' => '',
        //Set Views options
        'layout'    => 'baseAdmin::site.layout',
    ],
    /*
    |--------------------------------------------------------------------------
    | Path to the Voyager Assets
    |--------------------------------------------------------------------------
    |
    | Here you can specify the location of the voyager assets path
    |
    */

    'assets'        => [
        'path'    => 'assets',
        'version' => '?n9'
    ],


    /* ------------------------------------------------------------------------------------------------
	 |  Cache settings (minutes)
	 | ------------------------------------------------------------------------------------------------
	 */
    'cacheSettings' => [
        'enable' => false,
        'time'   => 15,//  cache time in minutes
    ],


    'translatables' => [
        'enable-TranslatableFields-OnModel' => true,
        'input-locale-attribute'            => 'data-language',
        'form-group-class'                  => 'form-group-translation',
        'label-locale-indicator'            => '<span>%label%</span> <span class="ml-2 badge badge-pill badge-light">%locale%</span>'
    ],

];
