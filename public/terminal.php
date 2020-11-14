<?php

if (php_sapi_name() !== 'cli') {
    die;
}

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('API_DIR', ROOT_DIR . 'api' . DIRECTORY_SEPARATOR);

require API_DIR . 'autoload.php';

use Core\Configuration;

/* START */



/* FUNCTIONS */
/**
 * 
 * @return string
 */
function getModuleAction(): string {
    
    $module = $argv[0];
    $action = $argv[1];

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
