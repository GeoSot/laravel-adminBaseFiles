<?php
return [
    'permissionsCheckOnSideBar' => true,

    'routes' => [
        'user' => [
            'menus' => ['user', 'role', 'permission',],
            'icon' => ['class' => 'fa  fa-group', 'style' => 'color:#0fefb3',],
            'order' => 20,
            'makeFiles' => false,
            'translatable' => ['customer', 'contact'],
        ],
//        'customer' => [
//            'menus' => ['customer', 'contact'],
//            'icon' => ['class' => 'fa  fa-address-book', 'style' => 'color:#52c1dc',],
//            'order' => 30,
//            'makeFiles' => false,
//            'separatorAfter' => false
//        ],
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
//        'domainContract' => [
//            'menus' => ['domainContract', 'category',],
//            'icon' => ['class' => 'fa  fa-sitemap', 'style' => 'color:#d552dc',],
//            'order' => 70,
//            'makeFiles' => false
//        ],
//
//        'hostingContract' => [
//            'menus' => ['hostingContract', 'category',],
//            'icon' => ['class' => 'fa  fa-sitemap', 'style' => 'color:#ccdc52',],
//            'order' => 80,
//            'makeFiles' => false
//        ],
//
//        'supportContract' => [
//            'menus' => ['supportContract', 'category',],
//            'icon' => ['class' => 'fa  fa-life-ring', 'style' => 'color:#73f3a2',],
//            'order' => 90,
//            'makeFiles' => false,
//        ],
//
//        'updateContract' => [
//            'menus' => ['updateContract', 'category',],
//            'icon' => ['class' => 'fa  fa-wrench', 'style' => 'color:#e9d7a2'],
//            'order' => 100,
//            'makeFiles' => false,
//            'separatorAfter' => true
//        ],
//        'project' => [
//            'menus' => ['project', 'fieldType', 'fieldGroup'],
//            'icon' => ['class' => 'fa  fa-product-hunt', 'style' => 'color:#52c1dc',],
//            'order' => 120,
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['user', 'emailAccount',],
//        ],
//        'server' => [
//            'icon' => ['class' => 'fa  fa-tasks', 'style' => 'color:#8bafe6',],
//            'order' => 130,
//            'makeFiles' => false,
//            'separatorAfter' => true
//        ],
//
//        'blacklistMail' => [
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['blacklistMail']
//        ],
//        'notificationTemplate' => [
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['notificationTemplate']
//        ],
//        'contractTask' => [
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['contractTask']
//        ],
//        'setting' => [
//            'makeFiles' => false,
//            'excludeFromSideBar' => ['setting']
//        ],

    ],

    'customMenuItems' => [
        'development' => [
            'menus' => [
                'setting' => [
                    'trans' => 'settings/setting.general.menuTitle',
                    'route' => 'settings.index'
                    //'url'=>'www.klkk'
                ],
                'blacklistMail' => [
                    'trans' => 'blacklistMails/blacklistMail.general.menuTitle',
                    'route' => 'blacklistMails.index'
                ],
                'notificationTemplate' => [
                    'trans' => 'notificationTemplates/notificationTemplate.general.menuTitle',
                    'route' => 'notificationTemplates.index'
                ],
                'dotenveditor' => [
                    'trans' => 'generic.sideMenuCustom.dotenveditor',
                   // 'url' => '/admin/configurations/enveditor',
                    'route'=>'env-editor.index'

                ],
                'translation' => [
                    'trans' => 'generic.sideMenuCustom.translation',
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
            'trans' => 'generic.sideMenuCustom.configurations'
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
        'delete-file',
        'index-dotenveditor',
        'index-job',
        'retry-job',
        'flush-job',
        'index-log',
        'delete-log',
    ]
];

