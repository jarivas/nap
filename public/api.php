<?php

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SRC_DIR', ROOT_DIR . 'src' . DIRECTORY_SEPARATOR);

require SRC_DIR . 'autoload.php';

use Core\Logger;
use Core\Configuration;
use Core\Response;
use Core\Authentication;
use Core\Request;

if (!Logger::canLog()) {
    Response::error('Fatal error on the server');
}

Logger::setRequestId(uniqid('', true));

Configuration::init();

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $cors = Configuration::getData('cors');

    header("Access-Control-Allow-Origin: {$cors['allowed-origins']}");
    header("Access-Control-Allow-Headers: {$cors['allowed-headers']}");
    header("Access-Control-Allow-Methods: POST OPTIONS");

    Response::okEmpty(Response::OK_NO_CONTENT);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::warning(Response::WARNING_METHOD_NOT_ALLOWED, 'Invalid method');
}

Request::init();
