<?php


namespace Core\Db;

use Core\Db\SleekDB;


class NoSQLEmbed extends Persistence {

    protected static $conf = [
        'auto_cache' => false,
        'timeout' => 5
    ];

    protected $dataset;

    private function __construct(array $db) {
        $path = ROOT_DIR . $db['data_folder'];

        $this->dataset = SleekDB\SleekDB::store($db['name'], $path, self::$conf);
    }

    public function create(array $item): bool {
        $result = $this->dataset->insert($item);

        return ($result) ? true : false;
    }

    public function read(array $criteria, array $options = []): array {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, self::CRITERIA_EQUAL, $value);

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

    public function readOne(array $criteria, array $options = []): array{
        $options['limit'] = 1;
        $result = $this->read($criteria, $options);

        if(count($result) == 1)
            $result = $result[0];

        return $result;
    }

    public function update(array $criteria, array $item): bool {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, self::CRITERIA_EQUAL, $value);

        return $this->dataset->update($item);
    }

    public function delete(array $criteria): bool {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, self::CRITERIA_EQUAL, $value);

        return $this->dataset->delete();
    }

}
