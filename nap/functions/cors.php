<?php
use Nap\Response;


if(isset($appConfig['cors'])) {
    //Allowed method
    $isOptions = ($method == 'OPTIONS');
    $allowedMethods = &$appConfig['cors']['allowed-methods'];
    
    if(!$isOptions && stripos($allowedMethods, $method) === false)
        throw new Exception('Method not allowed', Response::WARNING_TYPE_METHOD_NOT_ALLOWED);
    
    //Allowed origin
    $allowedOrigin = &$appConfig['cors']['allowed-origin'];
    $corsOK = function() use($allowedMethods, $isOptions) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization');
        header("Access-Control-Allow-Methods: $allowedMethods");
        
        if($isOptions)
            Response::okEmpty(Response::OK_TYPE_NO_CONTENT);
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
