<?php

return new \Phalcon\Config([
    'backup' => [
        'database' => [
            'db' => [
                'adapter' => 'db',
                'path' => realpath(__DIR__.'/../../cron/data/').'/backup/database',
                'databases' => [
                    'apppicker_production' => [
                        'database' => 'dbMaster',
                    ]
                ]
            ]
        ],
    ],

    'search' => [
		'develop' => [
            'env' => 'develop',
            'grids' => [
                [
                    'grid' => '\Event\Grid\Search\Event',
                    'type' => 'event'
                ]
            ]
        ]

    ],

    'defaultQueue' => [
        'prod' => []
    ]
]);
