<?php

namespace Core\Db;

abstract class Persistence {

    const CRITERIA_EQUAL = '=';

    protected static $instance = null;

    /**
     * 
     * @param array $db the info contained in the configuration
     * @return \self
     */
    public static function getInstance(array $db): self {
        if (!static::$instance) {
            static::$instance = new static($db);
        }

        return static::$instance;
    }

    /**
     * 
     * @param array $db the info contained in the configuration
     */
    protected function __construct(array $db) {
        
    }

    /**
     * 
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName aka table or Document
     * @param array $options limit, skip, orderBy
     * @return array
     */
    abstract public function create(array $item, ?string $storeName = null): bool;

    /**
     * 
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName aka table or Document
     * @param array $options limit, skip, orderBy
     * @return array|null
     */
    abstract public function read(array $criteria, ?string $storeName = null, array $options = []): ?array;

    /**
     * 
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName aka table or Document
     * @param array $options limit, skip, orderBy
     * @return array|null
     */
    abstract public function readOne(array $criteria, ?string $storeName = null, array $options = []): ?array;

    /**
     * 
     * @param array $criteria to filter query results (where)
     * @param array $item subject to update
     * @param string|null $storeName aka table or Document
     * @return bool
     */
    abstract public function update(array $criteria, array $item, ?string $storeName = null): bool;

    /**
     * 
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName
     * @return bool
     */
    abstract public function delete(array $criteria, ?string $storeName = null): bool;
}
