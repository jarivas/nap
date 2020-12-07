<?php

namespace Core;

use Core\Db\Persistence;
use Api\Authentication;
use Exception;

class Request
{

    /**
     *
     * @var array
     */
    protected static $data;

    public static function getResponse(): array
    {
        $callable = '';
        $parameters = [];
        
        self::setRequestData(file_get_contents('php://input'));

        $callable = self::getModuleAction();
        
        $parameters = self::getParameters();
        
        return $callable($parameters, Persistence::getPersistence());
    }

    protected static function setRequestData(string $body)
    {

        if (!strlen($body)) {
            
            throw new Exception('Empty body', Response::WARNING_BAD_REQUEST);
        }
        
        $request = json_decode($body, true);

        if (!$request) {
            Logger::info($body);
            
            throw new Exception('JSON not well formed', Response::WARNING_BAD_REQUEST);
        }

        if (empty($request['module'])) {
            
            throw new Exception('Module is required', Response::WARNING_BAD_REQUEST);
        }

        if (empty($request['action'])) {
            
            throw new Exception('Action is required', Response::WARNING_BAD_REQUEST);
        }

        if (empty($request['auth'])) {
            $request['auth'] = [];
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
        $auth = self::$data['auth'];

        if (!Configuration::validateModuleAction($module, $action)) {
            
            throw new Exception('Wrong Module and/or Action', Response::WARNING_BAD_REQUEST);
        }

        if (Configuration::shouldAuth($module, $action)) {
            if (!Authentication::isValid($auth)) {
                
                throw new Exception('Wrong login credentials', Response::WARNING_UNAUTHORIZED);
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
            
            throw new Exception($msg, Response::WARNING_BAD_REQUEST);
        }

        return $result;
    }
}
