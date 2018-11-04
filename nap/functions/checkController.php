<?php
use Nap\Response;

$urlParams = array_slice(explode('/', $_SERVER['PATH_INFO']), 1);
$controller = $urlParams[0];
$action = $urlParams[1];
unset($urlParams);

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
    case 'GET' : $params = isset($_GET['criteria']) ? json_decode($_GET['criteria'], true) : [];
        break;
    case 'PUT':
        $criteria = json_decode($_GET['criteria'], true);
        $params = json_decode(file_get_contents('php://input'), true);
        
        if(empty($criteria) || empty($params)) 
            throw new Exception('criteria or params is missing', Response::WARNING_TYPE_BAD_REQUEST);
            
        $params = ['criteria' => $criteria, 'params' => $params];

        break;
    case 'DELETE':
        $params = json_decode($_GET['criteria'], true);
        
        if(empty($params))
            throw new Exception('criteria is missing', Response::WARNING_TYPE_BAD_REQUEST);
        break;
}
