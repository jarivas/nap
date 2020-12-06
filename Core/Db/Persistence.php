<?php

namespace Core\Db;

use Core\Response;
use Core\Configuration;
use Exception;

abstract class Persistence
{
    
    const CRITERIA_AND = 'and';
    const CRITERIA_OR = 'or';
    const CRITERIA_EQUAL = '=';
    const CRITERIA_NOT_EQUAL = '!=';
    const CRITERIA_GREATER = '>';
    const CRITERIA_GREATER_EQUAL = '>=';
    const CRITERIA_LESS = '<';
    const CRITERIA_LESS_EQUAL = '<=';

    protected static $instance = null;
    

    /**
     *
     * @return self
     */
    public static function getPersistence(): self
    {
        if (self::$instance) {
            return self::$instance;
        }
        
        $db = Configuration::getData('db');
        
        if (!$db || empty($db['type'])) {
            
            throw new Exception('DB config does not exists or invalid', Response::FATAL_INTERNAL_ERROR);
        }
        
        $dbType = $db['type'];
        $callable = '';
        
        switch ($dbType) {
            case 'sleek':
                $callable = "Core\\Db\\NoSQLEmbed";
                break;
        }
        
        $db = Configuration::getData($dbType);

        return self::$instance = new $callable($db);
    }

    /**
     *
     * @param array $db the info contained in the configuration
     */
    abstract protected function __construct(array $db);
            
    /**
     *
     * @param array $criteria to filter query results (where)
     * @param string|null $storeName aka table or Document
     * @param array $options limit, skip, orderBy
     * @return bool
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
