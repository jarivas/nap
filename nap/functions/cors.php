<?php

use Nap\Response;

function handleCors($allowedHeaders, $allowedMethods, $method) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: $allowedHeaders");
    header("Access-Control-Allow-Methods: $allowedMethods");

    if ($method == 'OPTIONS')
        Response::okEmpty(Response::OK_TYPE_NO_CONTENT);
}

if (isset($appConfig['cors'])) {
    $cors = &$appConfig['cors'];
    $allowedHeaders = $cors['allowed-headers'];
    $allowedMethods = $cors['allowed-methods'];

    if (stripos($allowedMethods, $method) === false)
        throw new Exception('Method not allowed', Response::WARNING_TYPE_METHOD_NOT_ALLOWED);

    handleCors($allowedHeaders, $allowedMethods, $method);
    
    unset($allowedHeaders);
    unset($allowedMethods);
    unset($corsOK);
    unset($url);
}
