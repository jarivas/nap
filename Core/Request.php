<?php

namespace Core;

use Core\Db\Persistence;
use Api\Authentication;

class Request
{

    /**
     *
     * @var array
     */
    protected static $data;

    public static function init($body)
    {
        $callable = '';
        $parameters = [];

        if (!strlen($body)) {
            Response::sendWarning(Response::WARNING_BAD_REQUEST, 'Empty body');
        }

        self::setRequestData($body);

        $callable = self::getModuleAction();
        
        $parameters = self::getParameters();
        
        $response = $callable($parameters, Persistence::getPersistence());
        
        Response::ok($response);
    }

    protected static function setRequestData(string &$body)
    {
        $request = json_decode($body, true);

        if (!$request) {
            Logger::info($body);

            Response::sendWarning(Response::WARNING_BAD_REQUEST, 'JSON not well formed');
        }

        if (empty($request['module'])) {
            Response::sendWarning(Response::WARNING_BAD_REQUEST, 'Module is required');
        }

        if (empty($request['action'])) {
            Response::sendWarning(Response::WARNING_BAD_REQUEST, 'Action is required');
        }

        if (empty($request['parameters'])) {
            $request['parameters'] = [];
        }

        self::$data = &$request;
    }

    protected static function getModuleAction(): string
    {
        $module = self::$data['module'];
        $action = self::$data['action'];

        if (!Configuration::validateModuleAction($module, $action)) {
            Response::sendWarning(Response::WARNING_BAD_REQUEST, 'Wrong Module and/or Action');
        }

        if (Configuration::shouldAuth($module, $action)) {
            if (!Authentication::isValid(self::$data['parameters'])) {
                Response::sendWarning(Response::WARNING_UNAUTHORIZED, 'Wrong login credentials');
            }
        }

        $module = ucfirst($module);
        $action = ucfirst($action);

        return "Api\\Modules\\$module\\$action::process";
    }

    protected static function getParameters(): array
    {
        $result = self::$data['parameters'];

        list($ok, $msg) = Sanitize::process(self::$data['module'], self::$data['action'], $result);

        if (!$ok) {
            Response::sendWarning(Response::WARNING_BAD_REQUEST, $msg);
        }

        return $result;
    }
}
