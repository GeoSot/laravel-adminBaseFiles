<?php

return [

    /* ------------------------------------------------------------------------------------------------
    |  IF permissionsCheckOnSideBar = TRUE and user doesn't have the permission to see a menu item,
    |  then the menu item will not appear on sidebar
    | ------------------------------------------------------------------------------------------------
    */
    'permissionsCheckOnSideBar' => false,

    /*
    |--------------------------------------------------------------------------
    | Routes & Entities
    |--------------------------------------------------------------------------
    |
    | each route array creates created a set of routes (using a combination of the index (array key) and each menu item
    | Each menu item it is considered as a Model entity, in order to automatically create Models Admin Controllers and permissions
    |
    | menus: Each item inside it counts as a Model and an admin route
    | icon: can be filled with ['class' => 'fa  fa-group', 'style' => 'color:#0fefb3',],
    | order: defines the total order of the menu item on admin sidebar
    | makeFiles: if false doesn't create Admin files during "baseAdmin:autoCreateAll" command
    | excludeFromSideBar: excludes menu-items From admin Menu
    | separatorAfter: add a separator line after this menu
    */

    'routes' => [
        'user' => [
            'menus' => ['user', 'team', 'role', 'permission',],
            'icon' => ['class' => 'fa  fa-group', 'style' => 'color:#0fefb3',],
            'order' => 20,
            'makeFiles' => false,
        ],
        'page' => [
            'menus' => ['page', 'block', 'area'],
            'icon' => ['class' => 'fa  fa-file-text-o', 'style' => 'color:#52c1dc',],
            'order' => 30,
            'makeFiles' => false,
        ],
        'medium' => [
            'menus' => ['medium', 'gallery',],
            'icon' => ['class' => 'fa  fa-media', 'style' => 'color:#52c1dc',],
//            'excludeFromSideBar' => ['gallery'],
            'order' => 40,
        ],

//        'ticket' => [
//            'menus' => ['ticket', 'type', 'status', 'priority', 'message', 'messageNote', 'task',],
//            'icon' => ['class' => 'fa fa-ticket', 'style' => 'color:#fff',],
//            'order' => 50,
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['message', 'task', 'messageNote'],
//            'separatorAfter' => true
//        ],

//        'project' => [
//            'menus' => ['project', 'fieldType', 'fieldGroup'],
//            'icon' => ['class' => 'fa  fa-product-hunt', 'style' => 'color:#52c1dc',],
//            'order' => 120,
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['user', 'emailAccount',],
//        ],

//

        'setting' => [//I use it to autoRegister Routes
            'makeFiles' => false,
            'excludeFromSideBar' => ['setting']
        ],

    ],

    'customMenuItems' => [

        'development' => [
            'menus' => [
                'setting' => [
                    'trans' => 'settings/setting.general.menuTitle',
                    'route' => 'settings.index'
                    //'url'=>'www.klkk'
                ],
                'dotenveditor' => [
                    'trans' => 'sideMenu.custom.dotenveditor',
                    // 'url' => '/admin/configurations/enveditor',
                    'route' => 'env-editor.index'

                ],
                'translation' => [
                    'trans' => 'sideMenu.custom.translation',
                    'url' => '/admin/translations'

                ],
//                'job' => [//Enabled only if you use  Database driver for queues
//                    'trans' => 'queues/queue.general.menuTitle',
//                    'route' => 'queues.index'
//                ],
                'log' => [
                    'trans' => 'generic.sideMenuCustom.logs',
                    'route' => 'logs.index'
                ],
                //                'test'                 => [
                //                    'trans' => 'tests/test.menuTitle',
                //                    'url'   => 'test.gr'
                //                ],
            ],
            'icon' => ['class' => 'fa  fa-cog', 'style' => 'color:#ff887c',],
            'order' => 150,
            'trans' => 'sideMenu.custom.configurations'
        ],
        //        'testCustomMenu'      => [
        //            'trans' => 'settings/setting.general.menuTitle',
        //            'url' => 'www.settings.gr',
        //            'icon'  => ['class' => 'fa  fa-cog', 'style' => 'color:#73f3a2',],
        //        ],
        //        'testCustomMenu'      => [
        //            'trans' => 'settings/setting.general.menuTitle',
        //            'route' => 'settings.index',
        //            'icon'  => ['class' => 'fa  fa-cog', 'style' => 'color:#73f3a2',],
        //        ]
    ],


    //Listing of Extra  Permissions In Order To Be able to Create them from baseAdmin:makePermissionsForModel
    'extraPermissions' => [
        'dotenveditor' => ['index'],
        'job' => ['index', 'retry', 'flush'],
        'log' => ['index', 'delete'],
    ]
];

