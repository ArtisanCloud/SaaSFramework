<?php


return [


    'default' => env('DB_CONNECTION', 'pgsql'),

    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'polymer'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]
    ],

    'OPEN_API_DOMAIN' => env('APP_OPEN_API_URL'),
    'WHITE_LIST_DOMAIN' => env('APP_WHITE_LIST_URL'),
    'methodAll' => ['options', 'get', 'post', 'put', 'delete'],
    'methodGet' => ['options', 'get'],
    'methodPost' => ['options', 'post'],
    'methodPut' => ['options', 'put'],
    'methodDelete' => ['options', 'delete'],
    'API_VERSION' => 'v1',
    'API_VERSION_PATH' => 'api_' . $_API_VERSION,
];
