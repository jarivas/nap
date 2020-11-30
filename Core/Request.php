<?php

namespace Core;

use Core\Db\Persistence;
use Api\Authentication;

class Request {

    /**
     *
     * @var array
     */
    private static $data;
    
    /**
     *
     * @var Persistence
     */
    private static $persistence;

    public static function init() {
        $body = file_get_contents('php://input');
        $callable = '';
        $parameters = [];

        if (!strlen($body)) {

            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Empty body');
        }

        self::setRequestData($body);

        $callable = self::getModuleAction();
        
        $parameters = self::getParameters();
        
        // here middleware that modifies modifies parameters
        
        $response = $callable($parameters, self::getPersistence());
        
        // here middleware that modifies response

        Response::ok($response);
    }

    private static function setRequestData(string &$body) {

        $request = json_decode($body, true);

        if (!$request) {

            Logger::info($body);

            self::sendWarning(Response::WARNING_BAD_REQUEST, 'JSON not well formed');
        }

        if (empty($request['module'])) {

            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Module is required');
        }

        if (empty($request['action'])) {

            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Action is required');
        }

        if (empty($request['parameters'])) {

            $request['parameters'] = [];
        }

        self::$data = &$request;
    }

    private static function getModuleAction(): string {

        $module = self::$data['module'];
        $action = self::$data['action'];

        if (!Configuration::validateModuleAction($module, $action)) {

            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Wrong Module and/or Action');
        }

        if (Configuration::shouldAuth($module, $action)) {

            if (!Authentication::isValid(self::$data['parameters'], self::$persistence)) {

                self::sendWarning(Response::WARNING_UNAUTHORIZED, 'Wrong login credentials');
            }
        }

        $module = ucfirst($module);
        $action = ucfirst($action);

        return "Api\\Modules\\$module\\$action::process";
    }

    private static function getParameters(): array {
        $result = self::$data['parameters'];

        list($ok, $msg) = Sanitize::process(self::$data['module'], self::$data['action'], $result);

        if (!$ok) {
            Logger::warning($msg);
            Response::warning(Response::WARNING_BAD_REQUEST, $msg);
        }

        return $result;
    }

    /**
     * 
     * @return Persistence
     */
    public static function getPersistence(): Persistence {
        if (self::$persistence) {
            return self::$persistence;
        }
        
        $db = Configuration::getData('db');
        $dbType = $db['type'];
        $callable = '';
        
        switch ($dbType) {
            case 'sleek':
                $callable = "Core\\Db\\NoSQLEmbed::getInstance";
                break;
        }

        return self::$persistence = $callable(Configuration::getData($dbType));
    }

    private static function sendWarning(int $type, string $msg) {
        Logger::warning($msg);
        Response::warning($type, $msg);
    }

}
