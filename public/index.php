<?php

//A php file cache system is recommended like OPcache

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('NAP_DIR', ROOT_DIR . 'nap' . DIRECTORY_SEPARATOR);
define('FUNCTIONS_DIR', NAP_DIR  . 'functions' . DIRECTORY_SEPARATOR);

//log_writer allows to debug autoload
require FUNCTIONS_DIR . 'log_writer.php';

//configuration
require FUNCTIONS_DIR . 'loadConfig.php';

//autoload
require FUNCTIONS_DIR . 'autoload.php';

//error handling
require FUNCTIONS_DIR . 'errorHandler.php';

//basic request validation
require FUNCTIONS_DIR . 'requestValidation.php';

//start
$controller .= 'Controller';
$result = call_user_func_array([$controller, $action], [$params, $persistence]);
Nap\Response::ok($result);