<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db;

use MSBios\Db\Adapter\AdapterServiceFactory;
use Zend\Db\Adapter\Adapter;

return [

    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=;host=',
        'username' => null,
        'password' => null,
        'driver_options' => [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ],
    ],

    'service_manager' => [
        'factories' => [
            Adapter::class =>
                AdapterServiceFactory::class,
        ]
    ]
];
