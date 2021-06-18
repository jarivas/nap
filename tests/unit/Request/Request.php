<?php


namespace Nap\Tests\Request;


class Request extends \Nap\Request
{
    public static function setModuleAction(string $authClass, array $headers): ?array
    {
        return parent::setModuleAction($authClass, $headers);
    }

    public static function setRequestHelper(string $method): ?array
    {
        return parent::setRequestHelper($method);
    }

    public static function setRequestByJson(string $body): ?array
    {
        return parent::setRequestByJson($body);
    }

    public static function initDateTime(): ?array
    {
        return parent::initDateTime();
    }

    public static function sanitize(): ?array
    {
        return parent::sanitize();
    }

    /**
     * @return string
     */
    public static function getModule(): string
    {
        return self::$module;
    }

    /**
     * @return string
     */
    public static function getAction(): string
    {
        return self::$action;
    }

    public static function getRequest(): array
    {
        return parent::$request;
    }

    public static function getResponseClassName(): string
    {
        return parent::getResponseClassName();
    }
}