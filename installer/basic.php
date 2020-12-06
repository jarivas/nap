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

function run(string $command, bool $stopOnError = true) {
    $output = [];
    $return_var = 0;

    echoLine($command);
    exec($command, $output, $return_var);

    if ($return_var) {
        if($stopOnError) {
            print_r($output);
            var_dump($return_var);
            stop('Error on command');
        }
        return false;
    } else {
        return $output;
    }
}

function readConsole(string $prompt) {
    $value = readline($prompt);

    if (strlen($value))
        return $value;

    return readConsole();
}

function readMenu(string $prompt, int $maxOptions){
    $value = intval(readline($prompt));

    if($value && $value <= $maxOptions)
        return $value - 1;

    return readMenu($prompt, $maxOptions);
}

function generateMenu(string $title, array $options) {
    $prompt = $title . PHP_EOL;
    $maxOptions = 0;

    foreach ($options as $option)
        $prompt .= sprintf("%d - %s \n", ++$maxOptions, $option);

    return readMenu($prompt, $maxOptions);
}

