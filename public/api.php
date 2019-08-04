<?php

//A php file cache system is recommended like OPcache

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'nap' . DIRECTORY_SEPARATOR . 'load.php';

require FUNCTIONS_DIR . 'log_writer.php';

//error handling
require FUNCTIONS_DIR . 'errorHandler.php';

//basic request validation
require FUNCTIONS_DIR . 'requestValidation.php';

//start
if($persistence)
    $result = call_user_func_array([$controller, $action], [$params, $persistence]);
else
    $result = call_user_func_array([$controller, $action], $params);
Nap\Response::ok($result);
