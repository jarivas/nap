<?php
//autoload
require ROOT_DIR . 'vendor'. DIRECTORY_SEPARATOR . 'autoload.php';

//configuration
require FUNCTIONS_DIR . 'loadConfig.php';

//DB
$persistence = null;
$db = &$appConfig['db'];

switch ($db['type']) {
    case 'mysql': $persistence = null;
        break;
    case 'embedNoSQL': $persistence = Nap\SleekDbPersistence::setDb($db);
        break;
    case 'mongo': $persistence = Nap\MongoDbPersistence::setDb($db);
        break;
}
