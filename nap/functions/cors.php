<?php
use Nap\Response;
use \Exception;


if(isset($appConfig['cors'])) {
    //Allowed method
    $allowedMethods = &$appConfig['cors']['allowed-methods'];
    
    if(stripos($allowedMethods, $method) === false)
        throw new Exception('Method not allowed', Response::WARNING_TYPE_METHOD_NOT_ALLOWED);
    
    //Allowed origin
    $allowedOrigin = &$appConfig['cors']['allowed-origin'];
    $corsOK = function() use($allowedMethods, $method) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');
        header("Access-Control-Allow-Methods: $allowedMethods, OPTIONS");
        
        if($method == 'OPTIONS')
            Respose::okEmpty(Respose::OK_TYPE_NO_CONTENT);
    };
        
    if($allowedOrigin == '*') {
        $corsOK();
    } else  {
        $url = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['REMOTE_HOST'];
        
        if($allowedOrigin == $url)
            $corsOK();
        else 
            throw new Exception('CORS not enabled', Response::WARNING_TYPE_BAD_REQUEST);

        unset($url);
    }

    unset($allowedOrigin);
    unset($allowedMethods);
    unset($corsOK);
}
