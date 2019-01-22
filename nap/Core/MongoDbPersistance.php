<?php
namespace Nap;

use MongoDB\Client;
use MongoDB\InsertOneResult;
use MongoDB\Driver\Cursor;
use MongoDB\DeleteResult;

class MongoDbPersistance extends Persistence {
    public static function setDb(array &$db): string {
        $uriOptions = empty($db['uriOptions'])?[] : $db['uriOptions'];
        $driverOptions = empty($db['driverOptions'])?[] : $db['driverOptions'];
        $client = new Client($db['host'], $uriOptions, $driverOptions);
        
        self::$database = $client->selectDatabase($db['dbName']);
        
        return __CLASS__;
    }

    public function __construct(string $datasetName) {
        $this->dataset = self::$database->selectCollection($datasetName);
    }

    public function create(array $item): bool {
        /** @var InsertOneResult  **/
        $result = $this->dataset->insertOne($item);
        
        return ($result->getInsertedCount()) ? true : false;
    }

    public function read(array $criteria): array {
        /** @var Cursor  **/
        $result = $this->dataset->find($criteria);
        
        return ($result) ? $result->toArray() : [];
    }

    public function update(array $criteria, array $item): bool {
        $result = $this->dataset->findOneAndUpdate($criteria, $item);
        
        return ($result) ? true : false;
    }

    public function delete(array $criteria): bool {
        /** @var DeleteResult  **/
        $result = $this->dataset->deleteOne($criteria);
        
        return ($result->getDeletedCount()) ? true : false;
    }
}
