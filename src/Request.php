<?php

namespace Nap;

use Nap\Configuration\Configuration;

class Request
{
    /**
     * @var string
     */
    protected static string $module;

    /**
     * @var string
     */
    protected static string $moduleConfig;

    /**
     * @var string
     */
    protected static string $action;

    /**
     *
     * @var array
     */
    protected static array $request;

    /**
     *
     * @var array
     */
    protected static array $headers;

    public static function setRequest(string $method): ?array
    {
        $authClass = '';
        $result = null;

        if (!Authentication::setConfig(Configuration::getData('authentication'))) {
            return ['authentication not in config', Response::FATAL_INTERNAL_ERROR];
        }

        if(!$authClass = Authentication::getProcessor()) {
            return ['authentication class not in config', Response::FATAL_INTERNAL_ERROR];
        }

        if ($result = self::setModuleAction($authClass, self::getHeaders())) {
            return $result;
        }

        return self::setRequestHelper($method);
    }

    public static function getResponse(): array
    {
        if ($error = self::initDateTime()) {
            return ['warning', $error[0], $error[1]];
        }

        if ($error = self::sanitize()) {
            return ['warning', $error[0], $error[1]];
        }

        $className = self::getResponseClassName();

        $className::setConfig(Configuration::getModuleData(self::$moduleConfig));

        return $className::process(self::$request);
    }

    public static function getHeaders(): array
    {
        if (empty(self::$headers)) {
            $headers = [];

            foreach ($_SERVER as $k => $v) {
                if (substr($k, 0, 5) == 'HTTP_') {
                    $k = str_replace('HTTP_', '', $k);
                }

                $headers[$k] = $v;
            }

            self::$headers = $headers;
        }

        return self::$headers;
    }

    protected static function setModuleAction(string $authClass, array $headers): ?array
    {
        $urlParams = explode('?', $_SERVER['REQUEST_URI']);
        $urlParams = array_slice(explode('/', $urlParams[0]), 1);

        if (count($urlParams) != 2) {
            return ['Url problems', Response::WARNING_BAD_REQUEST];
        }

        if (empty($urlParams[0])) {
            return ['Module is required', Response::WARNING_BAD_REQUEST];
        }

        if (empty($urlParams[1])) {
            return ['Action is required', Response::WARNING_BAD_REQUEST];
        }

        if (!Configuration::validateModuleAction($urlParams[0], $urlParams[1])) {
            return ['Wrong Module and/or Action', Response::WARNING_BAD_REQUEST];
        }

        if (Configuration::shouldAuth($urlParams[0], $urlParams[1])
            && (!$authClass::isValid($headers))) {
            return ['Wrong login credentials', Response::WARNING_UNAUTHORIZED];
        }

        self::$moduleConfig = $urlParams[0];
        self::$module = ucfirst($urlParams[0]);
        self::$action = ucfirst($urlParams[1]);

        return null;
    }

    protected static function setRequestHelper(string $method): ?array
    {
        $result = null;

        self::$request = is_array($_GET) ? $_GET : [];

        switch (strtoupper($method)) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $result = self::setRequestByJson('php://input');
                break;
            default:
                $result = ['Wrong method', Response::WARNING_BAD_REQUEST];
                break;
        }

        return $result;
    }

    protected static function setRequestByJson(string $body): ?array
    {
        if (empty($body)) {
            return['Empty body', Response::WARNING_BAD_REQUEST];
        }

        $request = json_decode($body, true);

        if (!$request) {
            return['JSON not well formed', Response::WARNING_BAD_REQUEST];
        }

        if(empty(self::$request)) {
            self::$request = [];
        }

        self::$request = array_merge(self::$request, $request);

        return null;
    }

    protected static function initDateTime(): ?array
    {
        if (!DTHelper::setConfig(Configuration::getData('datetime'))) {
            return ['Problems on datetime::init', Response::FATAL_INTERNAL_ERROR];
        }

        return DTHelper::init();
    }

    protected static function sanitize(): ?array
    {
        if (!Sanitize::setConfig(Configuration::getData('sanitize'))) {
            return ['Problems on Sanitize::init', Response::FATAL_INTERNAL_ERROR];
        }

        return Sanitize::process(self::$module, self::$action, self::$request);
    }

    protected static function getResponseClassName(): string
    {
        return sprintf("Action\\%s\\%s", self::$module, self::$action);
    }
}
