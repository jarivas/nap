<?php

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('DATA_DIR', ROOT_DIR . 'data' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR . 'config' . DIRECTORY_SEPARATOR);

require 'basic.php';

/* START */

procesDataFolder();

procesConfig();

createRequest();

/* END */

/* FUNCTIONS */

function procesDataFolder() {
    if (!file_exists(DATA_DIR)) {
        run('mkdir -p ' . DATA_DIR);
        run('chmod -R 777 ' . DATA_DIR);
    }    
}

function procesConfig() {
    $iniFile = ROOT_DIR . "config/config.ini";
    $exampleFile = "{$iniFile}.example";
    
    if (!file_exists($exampleFile)) {
        die("Example config file not present: $exampleFile \n");
    }
    
    $iniContent = file_get_contents($exampleFile);
    
    if (!$iniContent) {
        die("Problem gettting content from example file \n");
    }
    
    $iniContent = str_replace('%datadir%', DATA_DIR, $iniContent);

    if (!file_put_contents($iniFile, $iniContent)) {
        die("Error creating config file: $iniFile \n");
    }
}

function readUser() {
    $user = readConsole('Enter user name: ');
    $pwd = readConsole('Enter user password: ');
    $pwd2 = readConsole('repeat password: ');

    if ($pwd == $pwd2)
        return [$user, $pwd];
    else
        return readUser();
}

function createRequest() {
    list($username, $pwd) = readUser();

    $user = [
        'username' => $username,
        'password' => password_hash($pwd, PASSWORD_DEFAULT)
    ];
    
    $request = json_encode([
        'module' => 'user',
        'action' => 'create',
        'parameters' => $user
    ]);
    
    $result = run("php ../public/cli.php '$request'");
    
    var_dump($result);
    die;
}

/* END FUNCTIONS */