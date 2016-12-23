<?php

return new \Phalcon\Config([
    'application' => [
        'controllersDir' => realpath(__DIR__ . '/../../cron/controllers/'),
        'modelsDir'      => realpath(__DIR__ . '/../../cron/models/'),
        'libraryDir'     => realpath(__DIR__ . '/../../cron/library/'),
        'dataDir'                => realpath(__DIR__ . '/../../cron/data/'),
        'appDir'                 => realpath(__DIR__ . '/../../cron/'),
        'rootDir'                => realpath(__DIR__ . '/../../../'),
        'documentRoot'   => realpath(__DIR__ . '/../../../cli/'),
        'baseUri'        => 'base.uri',
    ],


    'models' => [
        'metadata' => [
            'adapter' => 'Memory'
        ]
    ],

    'rabbitmq' => [
        'host' => 'localhost',
        'port' => 1234,
        'username' => 'user',
        'password' => 'password',
        'vhost' => '/',
        'exchangeType' => 'topic',
        'queuePrefix' => 'prefix_'
    ],

    'daemon' => [
        'socket' => 'unix:///tmp/cron.manager.sock',
        'log' => '/tmp/cron.manager.log',
        'error' => '/tmp/cron.manager.error.log',
        'pid' => '/tmp/cron.manager.pid',
        'lock' => '/tmp/cron.manager.lock',
        'settings' => [
            'type' => 'model',
            'model' => '\CronManager\Queue\Model\Settings',
            'environment' => 'develop'
        ]
    ]
]);
