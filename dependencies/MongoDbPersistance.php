<?php

class MongoDbPersistance extends Nap\Persistence {

    public function __construct(string $datasetName) {
        $this->dataset = self::$database->selectCollection($datasetName);
    }

    public static function setDb(array &$db) {
        $uriOptions = empty($db['uriOptions'])?[] : $db['uriOptions'];
        $driverOptions = empty($db['driverOptions'])?[] : $db['driverOptions'];
        $client = new \MongoDB\Client($db['host'], $uriOptions, $driverOptions);
        
        self::$database = $client->selectDatabase($db['dbName']);
        
        return self;
    }

    public function create(array $item) {
    }

    public function read(array $criteria) {
    }

    public function update(array $criteria, array $item) {
    }

    public function delete(array $criteria) {
    }
}
