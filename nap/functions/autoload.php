<?php
//autoload
define('NAP_CORE_DIR', NAP_DIR . 'Core');
define('SRC_DIR', ROOT_DIR . 'src' . DIRECTORY_SEPARATOR);

$loader = require ROOT_DIR . 'vendor'. DIRECTORY_SEPARATOR . 'autoload.php';

$loader->addPsr4('Nap\\', [NAP_CORE_DIR]);
$loader->addPsr4('App\\', [SRC_DIR]);

//configuration
require FUNCTIONS_DIR . 'loadConfig.php';

//DB
$persistence = null;
$db = &$appConfig['db'];

switch ($db['type']) {
    case 'mongo': $persistence = Nap\MongoDbPersistence::setDb($db);
        break;
    case 'sleek': $persistence = Nap\SleekDbPersistence::setDb($db);
        break;
}
