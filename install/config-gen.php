<?php
include 'composer.php';

function configUser(){
    echoLine('Configuring login');
    $username = readConsole('username: ');
    $password = readConsole('password: ');
    $key = uniqid(microtime(true), true);

    return [password_hash("{$username}{$password}{$key}", PASSWORD_DEFAULT, ["cost" => 12]), $key];
}

define('DB_TYPE', ['mysql', 'embedNoSQL', 'mongo']);

$selectedDBType = generateMenu('SELECT DB TYPE', DB_TYPE);
$selectedDBType = DB_TYPE[$selectedDBType];

include sprintf('config-gen-%s.php', $selectedDBType);

