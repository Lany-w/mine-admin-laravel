<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 09:19
 */

return [
    /*
    |--------------------------------------------------------------------------
    |route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [
        'prefix' => env('ADMIN_ROUTE_PREFIX', 'system'),
        'middleware' => []
    ],
    /*
    |--------------------------------------------------------------------------
    | auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all admin pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [
        'guard' => 'system',

        'guards' => [
            'system' => [
                'driver'   => 'jwt',
                'provider' => 'system_user',
            ],
        ],

        'providers' => [
            'system_user' => [
                'driver' => 'eloquent',
                'model'  => \Lany\MineAdmin\Model\SystemUser::class,
            ],
        ],
    ],
];