<?php

spl_autoload_register(function ($class_name) {
    require(API_DIR . str_replace("\\", '/', $class_name) . '.php');
});
