<?php

spl_autoload_register(function ($class_name) {
    //$classPath = (substr($class_name, 0, 4) == 'Core') ? CORE_DIR : API_DIR;

    require(ROOT_DIR . str_replace("\\", '/', $class_name) . '.php');
});
