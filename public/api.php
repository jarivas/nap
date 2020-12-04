<?php

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CORE_DIR', ROOT_DIR . 'Core' . DIRECTORY_SEPARATOR);

require CORE_DIR . 'autoload.php';

use Core\Logger;
use Core\Configuration;
use Core\Response;
use Core\Request;

if (!Logger::canLog()) {
    die('Fatal error on the server');
}

Logger::setRequestId(uniqid('', true));

$iniFile = ROOT_DIR .'config/config.ini';

list($ok, $msg) = Configuration::init($iniFile);

if (!$ok) {
    die($msg);
}

require CORE_DIR . 'error_handler.php';

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
    $cors = Configuration::getData('cors');

    header("Access-Control-Allow-Origin: {$cors['allowed-origins']}");
    header("Access-Control-Allow-Headers: {$cors['allowed-headers']}");
    header("Access-Control-Allow-Methods: POST OPTIONS");

    Response::okEmpty(Response::OK_NO_CONTENT);
}

if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    $msg = 'Invalid method';

    Logger::warning($msg);
    Response::warning(Response::WARNING_METHOD_NOT_ALLOWED, $msg);
}

$response = Request::getResponse();
        
Response::ok($response);