<?php
define('NO_ORM_URL', 'https://github.com/jarivas/no-orm/archive/1.0.zip');
define('NO_ORM_FILE', 'no-orm.zip');
define('NO_ORM_FOLDER', 'no-orm-1.0');

composerAddPSR('psr-4', ['Entity\\' => 'nap/Entity/']);

composerInstall();

echoLine('Configuring db');

$host = readConsole('host: ');
$dbName = readConsole('db name: ');
$username = readConsole('username: ');
$password = readConsole('password: ');


list($hash , $key) = configUser();

$configIni = <<<EOT
[system]
debug = true

[cors]
allowed-methods = GET, POST, PUT, DELETE, OPTIONS
allowed-headers = Content-Type, Accept, Origin

[auth]
hash = $hash
key = $key

[db]
type = mysql

[user]
actions = login,logout
requireAuth = logout
EOT;

if(file_exists('config'))
    run('rm -rf config');

run('mkdir config');

file_put_contents('config/config.ini', $configIni);

changeDir('vendor');

if(!file_exists(NO_ORM_FOLDER)) {
    $content = file_get_contents(NO_ORM_URL);
    file_put_contents(NO_ORM_FILE, $content);

    run('unzip '. NO_ORM_FILE);

    run('rm '. NO_ORM_FILE);
}

changeDir(NO_ORM_FOLDER);

$configIni = <<<EOT
[db]
host = $host
dbname = $dbName
username = $username
password = $password

[output]
folder = Entity
EOT;

file_put_contents('config.ini', $configIni);

run('php run.php');

if(file_exists("{$rootDir}/nap/Entity"))
    run("rm -rf {$rootDir}/nap/Entity");

run("mv Entity {$rootDir}/nap");

changeDir($rootDir);
