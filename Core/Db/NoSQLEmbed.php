<?php

namespace Core\Db;

use Core\Db\SleekDB\SleekDB;
use Core\Response;

class NoSQLEmbed extends Persistence
{
    protected $dataset;
    protected $path;
    protected $conf = [
        'auto_cache' => false,
        'timeout' => 5
    ];

    /**
     *
     * @param array $db the info contained in the configuration
     */
    protected function __construct(array $db)
    {
        if (empty($db['data_folder']) || empty($db['name'])
                || !is_string($db['data_folder']) || !is_string($db['name'])) {
            
            throw new \Exception('Required DB configuration not present or invalid: ', Response::FATAL_INTERNAL_ERROR);
        }
        
        $this->path = ROOT_DIR . $db['data_folder'] . DIRECTORY_SEPARATOR . $db['name'];

        $this->dataset = [];
    }

    /**
     *
     * @param string|null $storeName aka table or Document
     * @return SleekDB
     */
    protected function initDataset(?string $storeName = 'default'): SleekDB
    {
        if (empty($this->dataset[$storeName])) {
            $this->dataset[$storeName] = SleekDB::store($storeName, $this->path, $this->conf);
        }

        return $this->dataset[$storeName];
    }

    /**
     *
     * @param array $item subject to insert
     * @param string|null $storeName aka table or Document
     * @return bool
     */
    public function create(array $item, ?string $storeName = 'default'): bool
    {
        $store = $this->initDataset($storeName);

        $result = $store->insert($item);

        return ($result) ? true : false;
    }

    /**
     *
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName aka table or Document
     * @param array $options limit, skip, orderBy
     * @return array|null
     */
    public function read(array $criteria, ?string $storeName = 'default', array $options = []): ?array
    {
        $store = $this->initDataset($storeName);
        $result = [];

        $this->setWhere($store, $criteria);

        if (isset($options['limit']) && is_int($options['limit'])) {
            $store->limit($options['limit']);
        }

        if (isset($options['skip']) && is_int($options['skip'])) {
            $store->skip($options['skip']);
        }

        if (isset($options['orderBy']) && is_array($options['orderBy'])) {
            $o = $options['orderBy'];
            $store->orderBy($o['order'], $o['field']);
        }

        $result = $store->fetch();

        return count($result) ? $result : null;
    }

    /**
     *
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName aka table or Document
     * @param array $options limit, skip, orderBy
     * @return array|null
     */
    public function readOne(array $criteria, ?string $storeName = 'default', array $options = []): ?array
    {
        $options['limit'] = 1;

        $result = $this->read($criteria, $storeName, $options);
        
        return ($result) ? $result[0] : null;
    }

    /**
     *
     * @param array $criteria to filter query results (where)
     * @param array $item subject to update
     * @param string|null $storeName aka table or Document
     * @return bool
     */
    public function update(array $criteria, array $item, ?string $storeName = 'default'): bool
    {
        $store = $this->initDataset($storeName);

        $this->setWhere($store, $criteria);

        return $store->update($item);
    }

    /**
     *
     * @param SleekDB $store
     * @param array $criteria
     */
    protected function setWhere(SleekDB $store, array &$criteria)
    {
        foreach ($criteria as $fieldName => $c) {
            if ($c[0] === self::CRITERIA_AND) {
                $store->where($fieldName, $c[1], $c[2]);
            } else if ($c[0] === self::CRITERIA_OR) {
                $store->whereOr($fieldName, $c[1], $c[2]);
            }
        }
    }

    /**
     *
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName
     * @return bool
     */
    public function delete(array $criteria, ?string $storeName = 'default'): bool
    {
        $store = $this->initDataset($storeName);

        $this->setWhere($store, $criteria);

        return $store->delete();
    }
}
