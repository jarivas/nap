<?php

define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR . 'config' . DIRECTORY_SEPARATOR);

require 'basic.php';

/* START */

if (!file_exists(ROOT_DIR . 'data') || !file_exists(ROOT_DIR . 'log')) {
    die('Please run the unit test before this' . PHP_EOL);
}

procesConfig();

createRequest();


/* END */

/* FUNCTIONS */

function procesFolders(array $config) {
    $db = $config['db'];
    $db = $config[$db['type']];
    
    $folder = ROOT_DIR . $db['data_folder'] . DIRECTORY_SEPARATOR . $db['name'];
    
    processFolder($folder);
    
    $folder = ROOT_DIR . 'log';
    
    processFolder($folder);
}

function processFolder($folder) {
    if (!file_exists($folder)) {
        run('mkdir -p ' . $folder);
    }
    
    echoLine('Run this command: chmod -R 777 ' .  $folder);
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

    if (!file_put_contents($iniFile, $iniContent)) {
        die("Error creating config file: $iniFile \n");
    }
    
    //return parse_ini_string($iniContent, true, INI_SCANNER_TYPED);
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