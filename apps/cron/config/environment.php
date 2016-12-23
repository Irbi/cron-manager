<?php

return new \Phalcon\Config([
    'environment' => [
	 	'develop' => [
	            'database' => 'dbMaster',
	            'messageCenter' => 'develop',
	            'mailCenter' => 'develop',
	            'elastic'  => 'develop'
		],
	]
]);
