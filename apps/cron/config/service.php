<?php

return new \Phalcon\Config([
    'messageCenter'=> [
        'develop' => [
            'adapter' => 'rabbit',
            'host' => '127.0.0.1',
            'port' => 1234,
            'username' => 'user',
            'password' => 'password',
            'vhost' => '/',
            'type' => 'lazy',
            'class' => 'MessageCenter',
            'exchangeType' => 'topic',
            'exchangePrefix' => 'prefix_exch',
            'queuePrefix' => 'prefix_queue',
        ],
    ],

    'elastic' => [
        'develop' => [
            'index' => 'devindex',
            'connections' => [
                [
                    'host' => '127.0.0.1',
                    'port' => 9999
                ]
            ]
        ],
    ]
]);


