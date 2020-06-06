<?php
define('COMPOSER_LOCAL_FILE', 'composer.phar');

function checkComposerLocal(){
    echoLine('Checking composer local');

    if (file_exists(COMPOSER_LOCAL_FILE)){
        echoLine('Composer local is installed');
        return true;
    }

    echoLine('Composer local does not exist');
    return false;
}

function runComposerLocal(){
    return run('php ' . COMPOSER_LOCAL_FILE . ' install');
}

function runComposerRequireLocal($package) {
    return run('php ' . COMPOSER_LOCAL_FILE . ' require ' . $package);
}

function checkComposerGlobal(){
    if(run('which composer', false)) {
        echoLine('Composer global is installed');
        return true;
    }

    echoLine('Composer global does not exist');
    return false;
}

function runComposerGlobal() {
    return run('composer install');
}

function runComposerRequireGlobal($package) {
    return run("composer require $package");
}

function composerInstall() {
    global $rootDir;

    changeDir($rootDir);

    if(checkComposerLocal()) return runComposerLocal();
    if(checkComposerGlobal()) return runComposerGlobal();
}

function composerRequire($package) {
    global $rootDir;

    changeDir($rootDir);

    if(checkComposerLocal()) return runComposerRequireLocal($package);
    if(checkComposerGlobal()) return runComposerRequireGlobal($package);
}

function composerAddPSR($type, $namespaces) {
    global $rootDir;

    changeDir($rootDir);

    $data = json_decode(file_get_contents('composer.json'), true);
    $psr = &$data['autoload'][$type];

    foreach ($namespaces as $key => $value) {
        $psr[$key] = $value;
    }

    $result = file_put_contents('composer.json', json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}
