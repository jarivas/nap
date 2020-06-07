<?php


namespace Core\Db;


abstract class Persistence
{
    const CRITERIA_EQUAL = '=';

    private static $instance = null;

    public static function getInstance(array $db): self
    {
        if(!static::$instance) {
            static::$instance = new static($db);
        }

        return static::$instance;
    }

    private function __construct(array $db)
    {}

    abstract public function create(array $item): bool;

    abstract public function read(array $criteria, array $options = []): array;

    abstract public function update(array $criteria, array $item): bool;

    abstract public function delete(array $criteria): bool;
}
