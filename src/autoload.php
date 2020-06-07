<?php

spl_autoload_register(function ($class_name) {
    require(SRC_DIR . str_replace("\\", '/', $class_name) . '.php');
});
