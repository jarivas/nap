<?php

use Nap\Response;

$urlParams = array_slice(explode('/', $_SERVER['REQUEST_URI']), 1);
$controller = $urlParams[0];
$action = $urlParams[1];
$params = [];

//present check
if (empty($controller) || empty($action))
    throw new Exception('controller or action is missing', Response::WARNING_TYPE_BAD_REQUEST);

//existing action
if (empty($appConfig[$controller]) || (!in_array($action, $appConfig[$controller]['actions'])))
    throw new Exception("controller $controller has no $action", Response::WARNING_TYPE_FORBIDEN);

//authorization check customizable
if (isset($appConfig[$controller]['requireAuth']) && in_array($action, $appConfig[$controller]['requireAuth']))
    require FUNCTIONS_DIR . 'checkAuth.php';

switch ($method) {
    //CREATE
    case 'POST':
        $params = json_decode(file_get_contents('php://input'), true);
        break;
    //READ
    case 'GET':
        if (!empty($urlParams[2])) {
            $params = json_decode(urldecode($urlParams[2]), true);
            
            if (!is_array($params))
                $params = ['_id' => $params];
        }
        break;
    //UPDATE
    case 'PUT':
        if (empty($urlParams[2]))
            throw new Exception('criteria is missing', Response::WARNING_TYPE_BAD_REQUEST);

        $criteria = json_decode(urldecode($urlParams[2]), true);

        if (is_array($criteria))
            throw new Exception('criteria is invalid', Response::WARNING_TYPE_BAD_REQUEST);
        else
            $criteria = ['_id' => $criteria];

        $params = json_decode(file_get_contents('php://input'), true);

        if (empty($params))
            throw new Exception('params is missing', Response::WARNING_TYPE_BAD_REQUEST);

        $params = ['criteria' => $criteria, 'params' => $params];
        break;
    //DELETE
    case 'DELETE':
        if (empty($urlParams[2]))
            throw new Exception('params is missing', Response::WARNING_TYPE_BAD_REQUEST);

        $params = json_decode(urldecode($urlParams[2]), true);

        if (is_array($params))
            throw new Exception('params is invalid', Response::WARNING_TYPE_BAD_REQUEST);

        $params = ['_id' => $params];
        break;
}
$controller = 'App\\' . ucfirst($controller) . 'Controller';
