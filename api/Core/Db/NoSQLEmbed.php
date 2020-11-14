<?php


namespace Core\Db;

use Core\Db\SleekDB\SleekDB;


class NoSQLEmbed extends Persistence {

    private $dataset;
    private $path;
    private $conf = [
        'auto_cache' => false,
        'timeout' => 5
    ];

    protected function __construct(array $db) {
        parent::__construct($db);
        
        $this->path = ROOT_DIR . $db['data_folder'] . DIRECTORY_SEPARATOR . $db['name'];

        $this->dataset = [];
    }

    private function initDataset(?string $storeName = 'default'): SleekDB
    {
        if(empty($this->dataset[$storeName])) {
            $this->dataset[$storeName] = SleekDB::store($storeName, $this->path, $this->conf);
        }

        return $this->dataset[$storeName];
    }

    public function create(array $item, ?string $storeName = 'default'): bool {
        $store = $this->initDataset($storeName);

        $result = $store->insert($item);

        return ($result) ? true : false;
    }

    public function read(array $criteria, ?string $storeName = 'default', array $options = []): array {
        $store = $this->initDataset($storeName);

        foreach ($criteria as $fieldName => $value){
            $store->where($fieldName, self::CRITERIA_EQUAL, $value);
        }

        if (isset($options['limit']) && is_int($options['limit'])){
            $store->limit($options['limit']);
        }

        if (isset($options['skip']) && is_int($options['skip'])){
            $store->skip($options['skip']);
        }

        if (isset($options['orderBy']) && is_array($options['orderBy'])) {
            $o = $options['orderBy'];
            $store->orderBy($o['order'], $o['field']);
        }

        return $store->fetch();
    }

    public function readOne(array $criteria, ?string $storeName = 'default', array $options = []): array{
        $options['limit'] = 1;

        $result = $this->read($criteria, $storeName, $options);

        return (count($result) == 1) ? $result[0] : $result;
    }

    public function update(array $criteria, array $item, ?string $storeName = 'default'): bool {
        $store = $this->initDataset($storeName);

        $this->setWhere($store, $criteria);

        return $store->update($item);
    }

    private function setWhere(SleekDB $store, array &$criteria)
    {
        foreach ($criteria as $fieldName => $value){
            $store->where($fieldName, self::CRITERIA_EQUAL, $value);
        }
    }

    public function delete(array $criteria, ?string $storeName = 'default'): bool {
        $store = $this->initDataset($storeName);

        $this->setWhere($store, $criteria);

        return $store->delete();
    }

}
