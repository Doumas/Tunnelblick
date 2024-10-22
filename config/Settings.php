<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      => '8889',
            'database'  => 'tunnelblick_rave_community',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix'    => '',
        ],
        'csrf' => [
            'secret' => 'your_csrf_secret_key',
            'prefix' => 'csrf_' // Das Prefix, das verwendet werden soll
        ]
    ]
    
];
