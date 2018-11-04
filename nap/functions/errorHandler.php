<?php
use Nap\Logger;
use Nap\Response;

function error_handler($errno, $errstr, $errfile, $errline) {
    $message = sprintf('%s %s:%s', $errstr, $errfile, $errline);
    
    switch($errno) {
        case E_WARNING : Logger::warning($message);
            Response::error($message);
        break;
        case E_NOTICE : Logger::notice($message);
            Response::error($message);
        break;
        default : Logger::critical($message);
            Response::error($message);
        break;
    }
    
    /* Don't execute PHP internal error handler */
    return true;
}

function exception_handler($exception) {
    $code = $exception->getCode();
    $message = $exception->getMessage();
    $file = $exception->getFile();
    $line = $exception->getLine();
    
    if($code){
        $message = sprintf('%s %s:%s', $message, $file, $line);
        Logger::info($message);
        Response::warning($code, $message);
    } else {
        error_handler($code, $message, $file, $line);
    }
}

function shotdown_function() {
    $error = error_get_last();
    
    if($error && ($error['type'] == E_ERROR))
        error_handler($error['type'], $error['message'], $error['file'], $error['line']);
}

set_error_handler('error_handler');

set_exception_handler('exception_handler');

register_shutdown_function('shotdown_function');
