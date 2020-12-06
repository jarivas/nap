<?php

if (php_sapi_name() !== 'cli') {
    die;
}

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CORE_DIR', ROOT_DIR . 'Core' . DIRECTORY_SEPARATOR);

require CORE_DIR .'autoload.php';

use Core\Configuration;
use Core\Db\Persistence;
use Core\Sanitize;

/* START */

if (!$argc) {

    die('No json passed'. PHP_EOL);
}
$body = $argv[1];

if (!strlen($body)) {

    die('Empty body'. PHP_EOL);
}

$request = getRequestData($body);

$iniFile = ROOT_DIR .'config/config.ini';

Configuration::init($iniFile);

$className = getModuleAction($request);

$result = $className(getParameters($request), Persistence::getPersistence());

exit(json_encode($result));
/* END */

/* FUNCTIONS */

/**
 * 
 * @param string $body
 * @return array
 */
function getRequestData(string &$body): array {

    $request = json_decode($body, true);

    if (!$request) {

        die('JSON not well formed'. PHP_EOL);
    }

    if (empty($request['module'])) {

        die('Module is required'. PHP_EOL);
    }

    if (empty($request['action'])) {

        die('Action is required'. PHP_EOL);
    }

    if (empty($request['parameters'])) {

        $request['parameters'] = [];
    }

    return $request;
}

/**
 * 
 * @param array $request json decoded argument[0]
 * @return string
 */
function getModuleAction(array &$request): string {

    $module = $request['module'];
    $action = $request['action'];

    if (!Configuration::isCli($module, $action)) {
        die('This action can not be executed from console' . PHP_EOL);
    }

    $module = ucfirst($module);
    $action = ucfirst($action);

    return "Api\\Modules\\$module\\$action::process";
}

/**
 * 
 * @param array $request json decoded argument[0]
 * @return array
 */
function getParameters(array &$request): array {
    $result = $request['parameters'];

    list($ok, $msg) = Sanitize::process($request['module'], $request['action'], $result);

    if (!$ok) {
        die($msg);
    }

    return $result;
}

/* END FUNCTIONS */