<?php

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('API_DIR', ROOT_DIR . 'api' . DIRECTORY_SEPARATOR);

require API_DIR . 'autoload.php';

use Core\Logger;
use Core\Configuration;
use Core\Response;
use Core\Request;

if (!Logger::canLog()) {
    Response::error('Fatal error on the server');
}

Logger::setRequestId(uniqid('', true));

Configuration::init();

require API_DIR . 'error_handler.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $cors = Configuration::getData('cors');

    header("Access-Control-Allow-Origin: {$cors['allowed-origins']}");
    header("Access-Control-Allow-Headers: {$cors['allowed-headers']}");
    header("Access-Control-Allow-Methods: POST OPTIONS");

    Response::okEmpty(Response::OK_NO_CONTENT);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $msg = 'Invalid method';

    Logger::warning($msg);
    Response::warning(Response::WARNING_METHOD_NOT_ALLOWED, $msg);
}

Request::init();
