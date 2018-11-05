<?php
use Nap\Response;
use \Exception;

$urlParams = array_slice(explode('/', $_SERVER['REQUEST_URI']), 1);
$controller = $urlParams[0];
$action = $urlParams[1];

//present check
if(empty($controller) || empty($action) )
    throw new Exception('controller or action is missing', Response::WARNING_TYPE_BAD_REQUEST);
    
//existing action
if( empty($appConfig[$controller]) || (!in_array($action, $appConfig[$controller]['actions'])) )
    throw new Exception("controller $controller has no $action", Response::WARNING_TYPE_FORBIDEN);

//authorization check customizable
if(in_array($action, $appConfig[$controller]['requireAuth']))
    require FUNCTIONS_DIR . 'checkAuth.php';

switch($method){
    case 'POST': $params = json_decode(file_get_contents('php://input'), true);
        break;
    case 'GET' : $params = isset($urlParams[2]) ? json_decode(urldecode($urlParams[2]), true) : [];
        break;
    case 'PUT':
        if(empty($urlParams[2]))
            throw new Exception('criteria is missing', Response::WARNING_TYPE_BAD_REQUEST);
            
        $criteria = json_decode(urldecode($urlParams[2]), true);
        $params = json_decode(file_get_contents('php://input'), true);
        
        if(empty($params) || empty($params)) 
            throw new Exception('criteria or params is missing', Response::WARNING_TYPE_BAD_REQUEST);
            
        $params = ['criteria' => $criteria, 'params' => $params];

        break;
    case 'DELETE':
        if(empty($urlParams[2]))
            throw new Exception('criteriais missing', Response::WARNING_TYPE_BAD_REQUEST);
            
        $params = json_decode(urldecode($urlParams[2]), true);
        
        if(empty($params))
            throw new Exception('criteria is missing', Response::WARNING_TYPE_BAD_REQUEST);
        break;
}
$controller = 'App\\' . ucfirst($controller) . 'Controller';