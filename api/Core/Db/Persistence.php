<?php


namespace Core\Db;


abstract class Persistence
{
    const CRITERIA_EQUAL = '=';

    protected static $instance = null;

    public static function getInstance(array $db): self
    {
        if(!static::$instance) {
            static::$instance = new static($db);
        }

        return static::$instance;
    }

    protected function __construct(array $db)
    {}

    abstract public function create(array $item, ?string $storeName = null): bool;

    abstract public function read(array $criteria, array $options = [], ?string $storeName = null): array;

    abstract public function update(array $criteria, array $item, ?string $storeName = null): bool;

    abstract public function delete(array $criteria, ?string $storeName = null): bool;
}
