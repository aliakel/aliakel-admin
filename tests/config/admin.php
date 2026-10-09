<?php

return [

    /*
     * AliAkel Admin name.
     */
    'name' => 'AliAkel Admin',

    /*
     * Logo in admin panel header.
     */
    'logo' => '<b>AliAkel</b> Admin',

    /*
     * Mini-logo in admin panel header.
     */
    'logo-mini' => '<b>AA</b>',

    /*
     * Route configuration.
     */
    'route' => [

        'prefix' => 'admin',

        'namespace' => 'App\\Admin\\Controllers',

        'middleware' => ['web', 'admin'],
    ],

    /*
     * AliAkel Admin install directory.
     */
    'directory' => app_path('Admin'),

    /*
     * AliAkel Admin html title.
     */
    'title' => 'Admin',

    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * AliAkel Admin auth setting.
     */
    'auth' => [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => AliAkel\Admin\Auth\Database\Administrator::class,
            ],
        ],
    ],

    /*
     * AliAkel Admin upload setting.
     */
    'upload' => [

        'disk' => 'admin',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
     * AliAkel Admin database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => AliAkel\Admin\Auth\Database\Administrator::class,

        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => AliAkel\Admin\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'admin_permissions',
        'permissions_model' => AliAkel\Admin\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => AliAkel\Admin\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
    ],

    /*
     * By setting this option to open or close operation log in AliAkel Admin.
     */
    'operation_log' => [

        'enable' => true,

        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs
         */
        'except' => [
            'admin/auth/logs*',
        ],
    ],

    /*
     * @see https://adminlte.io/docs/2.4/layout
     */
    'skin' => 'skin-blue-light',

    'menu_active_color' => '',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
     * Version displayed in footer.
     */
    'version' => '1.5.x-dev',

    /*
     * Settings for extensions.
     */
    'extensions' => [

    ],
];
