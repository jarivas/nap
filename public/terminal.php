<?php

if (php_sapi_name() !== 'cli') {
    die;
}

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('API_DIR', ROOT_DIR . 'api' . DIRECTORY_SEPARATOR);

require API_DIR . 'autoload.php';

use Core\Configuration;

/* START */
$body = $argv[0];

if (!strlen($body)) {

    die('Empty body');
}

$request = getRequestData(&$body);

$className = getModuleAction($module, $action);


/* FUNCTIONS */

/**
 * 
 * @param string $body
 * @return array
 */
function getRequestData(string &$body): array {

    $request = json_decode($body, true);

    if (!$request) {

        die('JSON not well formed');
    }

    if (empty($request['module'])) {

        die('Module is required');
    }

    if (empty($request['action'])) {

        die('Action is required');
    }

    if (empty($request['parameters'])) {

        $request['parameters'] = [];
    }

    return $request;
}

/**
 * 
 * @return string
 */
function getModuleAction(string $module, string $action): string {

    if (!Configuration::validateModuleAction($module, $action)) {
        die('Wrong Module and/or Action');
    }

    if (!Configuration::isCli($module, $action)) {
        die('This action can not be executed from console');
    }

    $module = ucfirst($module);
    $action = ucfirst($action);

    return "Modules\\$module\\$action::process";
}
