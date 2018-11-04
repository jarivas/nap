<?php
//loading the framework
define('NAP_CORE_DIR', NAP_DIR . 'Core');
foreach(array_slice(scandir(NAP_CORE_DIR), 2) as $file)
    require NAP_CORE_DIR . DIRECTORY_SEPARATOR . $file;

//setting dependencies
define('DEPENCIES_DIR', ROOT_DIR . 'dependencies' . DIRECTORY_SEPARATOR);
require DEPENCIES_DIR . 'loadDependencies.php';

//autoload
define('SRC_DIR', ROOT_DIR . 'src' . DIRECTORY_SEPARATOR);

function auto_loader($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    
    //log_writer('info', $class);
    
    require SRC_DIR . $class;
}

spl_autoload_register('auto_loader');