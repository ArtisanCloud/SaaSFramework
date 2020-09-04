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

    'domain' => [
        'land' => env('DOMAIN_LAND', 'artisancloud.com'),
        'landlord' => env('DOMAIN_LANDLORD', 'admin.productman.com'),
        'tenant' => env('DOMAIN_TENANT', 'productman.com'),
    ],
    
    'api_version' => 'v1',
    'api_version_path' => 'api_v1',

    'router' => [
        'open_api_domain' => env('APP_OPEN_API_URL'),
        'white_list_domain' => env('APP_WHITE_LIST_URL'),
        'methodAll' => ['options', 'get', 'post', 'put', 'delete'],
        'methodGet' => ['options', 'get'],
        'methodPost' => ['options', 'post'],
        'methodPut' => ['options', 'put'],
        'methodDelete' => ['options', 'delete'],
        'namespaceAPI' => 'API',
    ],

    'invitation_code_channel' => env('INVITATION_CODE_CHANNEL','api'),
    'verify_code_channel' => env('VERIFY_CODE_CHANNEL','sms'),
];
