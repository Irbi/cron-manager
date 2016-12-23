#!/usr/local/bin/php
<?php
chdir(__DIR__);
error_reporting(E_ALL);
define('PHALCON_VERSION_REQUIRED', '1.2.0');
define('PHP_VERSION_REQUIRED', '5.4.0');
define('DS', DIRECTORY_SEPARATOR);
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
}
if (!defined('VENDOR_PATH')) {
    define('VENDOR_PATH', dirname(dirname(__FILE__)).'/vendor');
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(__FILE__));
}
if (!defined('DOCUMENT_ROOT')) {
    define('DOCUMENT_ROOT', dirname(__FILE__));
}
if (!defined('ENGINE_PATH')) {
    define('ENGINE_PATH', VENDOR_PATH.'/temafey/phalcon-engine/Engine');
}
if (!defined('CRON_PATH')) {
    define('CRON_PATH', ROOT_PATH.'/apps/cron');
}
require_once '../vendor/autoload.php';

$di = new Phalcon\DI\FactoryDefault\CLI();

$app = new Phalcon\CLI\Console();
$app->setDI($di);
$app->registerModules([
    'cron' => [
        'className' => 'Cron\Module',
        'path'      => '../apps/cron/Module.php',
    ]
]);

array_shift($argv);
$count = count($argv);
if ($count < 2) {
	if ($count == 0) {
		throw new \Exception('CLI router arguments not have task and action');
	} 
	if ($count == 1) {
		throw new \Exception('CLI router arguments not have action');
	}
}
$params = ['module' => 'cron'];
$params['task'] = array_shift($argv);
$params['action'] = array_shift($argv);
if ($argv) {
	$params = array_merge($params, $argv);
}
$app->handle($params);
