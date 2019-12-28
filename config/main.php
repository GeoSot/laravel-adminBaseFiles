<?php
return [
    'permissionsCheckOnSideBar' => false,

    'routes' => [
        'user' => [
            'menus' => ['user', 'role', 'permission',],
            'icon' => ['class' => 'fa  fa-group', 'style' => 'color:#0fefb3',],
            'order' => 20,
            'makeFiles' => false,
            'translatable' => ['customer', 'contact'],
        ],
        'page' => [
            'menus' => ['page', 'block', 'area'],
            'icon' => ['class' => 'fa  fa-file-text-o', 'style' => 'color:#52c1dc',],
            'order' => 30,
            'makeFiles' => true,
            'translatable' => ['page', 'block', 'area']
        ],
//        'payment' => [
//            'menus' => ['payment', 'status'],
//            'icon' => ['class' => 'fa  fa-money', 'style' => 'color:#73f3a2',],
//            'order' => 40,
//            'makeFiles' => false,
//            'separatorAfter' => true
//        ],
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

    ],

    'customMenuItems' => [
        'media' => [
            'menus' => [
                'image' => [
                    'trans' => 'mediaModels/imageModel.general.menuTitle',
                    'route' => 'images.index'
                ],
                'file' => [
                    'trans' => 'mediaModels/fileModel.general.menuTitle',
                    'route' => 'files.index'
                ],
            ],
            'icon' => ['class' => 'fa  fa-picture-o', 'style' => 'color:rgb(124, 255, 130)',],
            'order' => 140,
            'trans' => 'sideMenu.custom.media'
        ],
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
                'job' => [
                    'trans' => 'queues/queue.general.menuTitle',
                    'route' => 'queues.index'
                ],
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
        'setting' => ['all'],
        'fileModel' => ['all'],
        'imageModel' => ['all'],
    ]
];

