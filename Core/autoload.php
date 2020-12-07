<?php

spl_autoload_register(function ($class_name) {
    require(ROOT_DIR . str_replace("\\", '/', $class_name) . '.php');
});
