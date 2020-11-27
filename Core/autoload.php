<?php

spl_autoload_register(function ($class_name) {
    $classPath = (substr($class_name, 0, 4) == 'Core') ? VENDOR_DIR : API_DIR;

    require($classPath . str_replace("\\", '/', $class_name) . '.php');
});
