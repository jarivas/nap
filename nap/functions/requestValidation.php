<?php

$method = $_SERVER['REQUEST_METHOD'];

//CORS
require FUNCTIONS_DIR . 'cors.php';

//check the controller
require FUNCTIONS_DIR . 'checkController.php';