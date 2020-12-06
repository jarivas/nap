<?php

namespace Core;

use Exception;

class Sanitize {

    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param string $module
     * @param string $action
     * @param array $parameters
     * @return array
     */
    public static function process(string $module, string $action, array &$parameters): array {
        $preKey = "{$module}_{$action}_";
        $rules = self::getRules($preKey);
        $parameterName = '';
        $errors = [];
        $value = null;

        foreach ($rules as $key => $filters) {
            $parameterName = str_replace($preKey, '', $key);

            self::applyFilters($filters, $parameterName, $parameters, $errors);
        }

        if (count($errors)) {
            $msg = self::getErrorMsg($errors);

            return [false, $msg];
        }

        return [true, ""];
    }

    protected static function getRules(string $preKey) {
        $sanitize = Configuration::getData('sanitize');

        $result = [];
        $length = strlen($preKey);

        foreach ($sanitize as $key => $value) {
            if (substr($key, 0, $length) === $preKey) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    protected static function applyFilters(string $filters, string $parameterName, array &$parameters, array &$errors) {
        $filtersError = [];

        $value = $parameters[$parameterName];
        
        foreach (explode('+', $filters) as $filter) {
            self::applyFilter($filter, $filtersError, $value);
        }

        if (count($filtersError)) {
            $errors[$parameterName] = $filtersError;
        } else {
            $parameters[$parameterName] = $value;
        }
    }

    protected static function applyFilter(string $filter, array &$filtersError, &$value) {        
        $pieces = explode('_', $filter);

        switch ($pieces[0]) {
            case 'DEFAULT':
                if (!$value || !strlen($value)) {
                    $value = $pieces[1];
                }
                break;
            case 'REQUIRED':
                if (!$value || !strlen($value)) {
                    $filtersError[] = 'Required';
                }
                break;
            case 'DATETIME':
                if (!$value || !strlen($value)) {
                    return;
                }
                
                $value = \DateTime::createFromFormat(self::DATETIME_FORMAT, $value);

                if (!$value) {
                    $filtersError[] = 'Wrong format, the right one is: ' . self::DATETIME_FORMAT;
                }
                break;
            case 'FILTER':
                if (!$value || !strlen($value)) {
                    return;
                }
                
                $flag = self::getFilterFlag($filter);
                
                if (!count($flag)) {
                    $value = filter_var($value, constant($filter));
                } else {
                    $value = filter_var($value, constant($flag[0]), constant($flag[1]));                    
                }

                if (!$value) {
                    throw new Exception("Invalid filter : $value", Response::FATAL_INTERNAL_ERROR);
                }
                break;
        }
    }
    
    protected static function getFilterFlag(string $filter): array {
        
        if (strpos($filter, '-') === false) {
            return [];
        }
        
        return explode('-', $filter);
    }

    protected static function getErrorMsg(array &$error): string {
        $result = '';

        foreach ($error as $param => $errors) {
            $result .= $param . ': ' . implode(', ', $errors) . ' | ';
        }

        return $result;
    }

}
