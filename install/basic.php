<?php

function echoLine($message) {
    echo $message . PHP_EOL;
}

function stop($message) {
    die($message . PHP_EOL);
}

function currentDir() {
    echoLine('Current directory: ' . getcwd());
}

function changeDir($dir) {
    if (chdir($dir))
        currentDir();
    else
        stop("Error changing dir to $dir");
}

function run(string $command) {
    $output = [];
    $return_var = 0;

    echoLine($command);
    exec($command, $output, $return_var);
    
    if ($return_var) {
        print_r($output);
        var_dump($return_var);
        stop('Error on command');
    }
}

function readConsole(string $prompt) {
    $value = readline($prompt);

    if (strlen($value))
        return $value;
    else
        return readConsole();
}
