<?php

set_include_path(implode(PATH_SEPARATOR, [
	realpath(__DIR__ . '/../library'),
	get_include_path(),
]));

if (!defined('APPLICATION_PATH')) {
	define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
}

if (!defined('APPLICATION_ENV')) {
	define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
}

require_once __DIR__ . '/../vendor/autoload.php';

use Tracy\Debugger;
Debugger::enable(Debugger::DETECT, __DIR__ . '/../log');

$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$application->run();
