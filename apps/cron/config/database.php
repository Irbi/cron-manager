<?php

return new \Phalcon\Config([
    'database' => [
		'dbMaster' => [
            'adapter'  => 'Mysql',
            'host'     => 'localhost',
            'username' => 'user',
            'password' => 'password',
            'name'     => 'dbname',
            'charset'  => 'utf8'
		],
    ]
]);
