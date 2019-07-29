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
        $params = (!empty($urlParams[2])) ? json_decode(urldecode($urlParams[2]), true) : $_GET;
        break;
    //UPDATE
    case 'PUT':
        if (empty($urlParams[2]) && !count($_GET))
            throw new Exception('criteria is missing', Response::WARNING_TYPE_BAD_REQUEST);

        $criteria = (!empty($urlParams[2])) ? json_decode(urldecode($urlParams[2]), true) : $_GET;

        $params = json_decode(file_get_contents('php://input'), true);

        if (empty($params))
            throw new Exception('params is missing', Response::WARNING_TYPE_BAD_REQUEST);

        $params = ['criteria' => $criteria, 'params' => $params];
        break;
    //DELETE
    case 'DELETE':
        $params = json_decode(file_get_contents('php://input'), true);

        if (empty($params))
            throw new Exception('params is missing', Response::WARNING_TYPE_BAD_REQUEST);

        break;
}
$controller = 'App\\' . ucfirst($controller) . 'Controller';
