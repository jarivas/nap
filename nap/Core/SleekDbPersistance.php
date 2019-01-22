<?php

namespace Nap;

class SleekDbPersistance extends Persistence {

    protected $path;

    public static function setDb(array &$db): string {
        $path = $db['host'] . DIRECTORY_SEPARATOR . $db['dbName'];

        self::$database = new \SleekDB\SleekDB($path);

        return __CLASS__;
    }

    public function __construct($datasetName) {
        $this->dataset = self::$database->store($datasetName, self::$database->dataDirectory);
    }

    public function create(array $item): bool {
        $result = $this->dataset->insert($item);
        
        return ($result) ? true : false;
    }

    public function read(array $criteria): array {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, Persistence::CRITERIA_EQUAL, $value);

        return $this->dataset->fetch();
    }

    public function update(array $criteria, array $item): bool {
        $this->dataset->where('_id', Persistence::CRITERIA_EQUAL, $criteria['_id']);

        return $this->dataset->update($item);
    }

    public function delete(array $criteria): bool {
        $this->dataset->where('_id', Persistence::CRITERIA_EQUAL, $criteria['_id']);

        return $this->dataset->delete();
    }

}
