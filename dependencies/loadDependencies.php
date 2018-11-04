<?php

//DB
$persistence = null;
$db = &$appConfig['db'];

switch ($db['type']) {
    case 'mongo': require DEPENCIES_DIR . 'MongoDbPersistance.php';
        $persistence = MongoDbPersistance::setDb($db);
        break;
    case 'sleek': require DEPENCIES_DIR  . 'sleek' . DIRECTORY_SEPARATOR . 'SleekDB.php';
        require DEPENCIES_DIR . 'SleekDbPersistance.php';
        $persistence = SleekDbPersistance::setDb($db);
        break;
}

//Customizable controller
require DEPENCIES_DIR . 'BasicController.php';