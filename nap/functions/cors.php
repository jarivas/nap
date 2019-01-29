<?php

use Nap\Response;

function handleCors($allowedOrigin, $allowedHeaders, $allowedMethods, $method) {
    header("Access-Control-Allow-Origin: $allowedOrigin");
    header("Access-Control-Allow-Headers: $allowedHeaders");
    header("Access-Control-Allow-Methods: $allowedMethods");

    if ($method == 'OPTIONS')
        Response::okEmpty(Response::OK_TYPE_NO_CONTENT);
}

if (isset($appConfig['cors'])) {
    $cors = &$appConfig['cors'];
    $allowedHeaders = $cors['allowed-headers'];
    $allowedOrigin = $cors['allowed-origin'];
    $allowedMethods = $cors['allowed-methods'];
    $url = $_SERVER['HTTP_ORIGIN'];

    if (stripos($allowedMethods, $method) === false)
        throw new Exception('Method not allowed', Response::WARNING_TYPE_METHOD_NOT_ALLOWED);

    if (($allowedOrigin == '*') || ($allowedOrigin == $url))
        handleCors($allowedOrigin, $allowedHeaders, $allowedMethods, $method);
    else
        throw new Exception('CORS not enabled', Response::WARNING_TYPE_BAD_REQUEST);

    unset($allowedOrigin);
    unset($allowedHeaders);
    unset($allowedMethods);
    unset($corsOK);
    unset($url);
}
