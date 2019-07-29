<?php

namespace Nap;

class SleekDbPersistence extends NoSQLPersistence {

    protected static $path;
    protected static $conf = [
        'auto_cache' => false,
        'timeout' => 5
    ];

    public static function setDb(array &$db): string {
        self::$path = $db['host'] . DIRECTORY_SEPARATOR . $db['dbName'];

        return __CLASS__;
    }

    public function __construct($datasetName) {
        $this->dataset = \SleekDB\SleekDB::store($datasetName, self::$path, self::$conf);
    }

    public function create(array $item): bool {
        $result = $this->dataset->insert($item);

        return ($result) ? true : false;
    }

    public function read(array $criteria, array $options = []): array {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, NoSQLPersistence::CRITERIA_EQUAL, $value);

        if (isset($options['limit']) && is_int($options['limit']))
            $this->dataset->limit($options['limit']);

        if (isset($options['skip']) && is_int($options['skip']))
            $this->dataset->skip($options['skip']);

        if (isset($options['orderBy']) && is_array($options['orderBy'])) {
            $o = $options['orderBy'];
            $this->dataset->orderBy($o['order'], $o['field']);
        }

        return $this->dataset->fetch();
    }

    public function update(array $criteria, array $item): bool {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, NoSQLPersistence::CRITERIA_EQUAL, $value);

        return $this->dataset->update($item);
    }

    public function delete(array $criteria): bool {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, NoSQLPersistence::CRITERIA_EQUAL, $value);

        return $this->dataset->delete();
    }

}
