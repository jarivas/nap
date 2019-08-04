<?php
include 'composer.php';

function configUser(){
    echoLine('Configuring login');
    $username = readConsole('username: ');
    $password = readConsole('password: ');
    $key = uniqid(microtime(true), true);

    return [password_hash("{$username}{$password}{$key}", PASSWORD_DEFAULT, ["cost" => 12]), $key];
}

function generateIni($dbType){
    global $rootDir;

    changeDir($rootDir);

    $configIni = <<<EOT
[system]
debug = true

[cors]
allowed-methods = GET, POST, PUT, DELETE, OPTIONS
allowed-headers = Content-Type, Accept, Origin

[db]
type = $dbType

EOT;

    if(file_exists('config'))
        run('rm -rf config');

    run('mkdir config');

    file_put_contents('config/config.ini', $configIni);
}

define('DB_TYPE', ['mysql', 'embedNoSQL', 'mongo']);

$selectedDBType = generateMenu('SELECT DB TYPE', DB_TYPE);
$selectedDBType = DB_TYPE[$selectedDBType];

include sprintf('config-gen-%s.php', $selectedDBType);

