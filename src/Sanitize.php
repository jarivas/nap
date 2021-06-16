<?php

namespace Nap;

use Nap\Configuration\ConfigHelper;

class Sanitize
{
    use ConfigHelper;

    /**
     * @param string $module
     * @param string $action
     * @param array $request
     * @return null|array
     */
    public static function process(string $module, string $action, array &$request): ?array
    {
        $preKey = "{$module}_{$action}_";
        $rules = self::getRules($preKey);
        $parameterName = '';
        $errors = [];

        foreach ($rules as $key => $filters) {
            $parameterName = str_replace($preKey, '', $key);

            self::applyFilters($filters, $parameterName, $request, $errors);
        }

        if (count($errors)) {
            return [self::getErrorMsg($errors), Response::WARNING_BAD_REQUEST];
        }

        return null;
    }

    protected static function getRules(string $preKey): array
    {
        $result = [];
        $length = strlen($preKey);

        foreach (self::$config as $key => $value) {
            if (substr($key, 0, $length) === $preKey) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    protected static function applyFilters(string $filters, string $parameterName, array &$parameters, array &$errors)
    {
        $filtersError = [];

        $value = (empty($parameters[$parameterName])) ? null : $parameters[$parameterName];

        foreach (explode('+', $filters) as $filter) {
            self::applyFilter($filter, $filtersError, $value);
        }

        if (count($filtersError)) {
            $errors[$parameterName] = $filtersError;
        } else {
            $parameters[$parameterName] = $value;
        }
    }

    protected static function applyFilter(string $filter, array &$filtersError, &$value)
    {
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

                $value = DTHelper::getDate($value);

                if (!$value) {
                    $filtersError[] = 'Wrong format, the right one is: ' . DTHelper::getDateTimeFormat();
                }
                break;
            case 'JSON':
                if (!$value || !strlen($value)) {
                    return;
                }

                $value = json_decode($value, true);

                if (!$value) {
                    $filtersError[] = 'Invalid JSON';
                }
                break;
            case 'FILTER':
                if (!$value || !strlen($value)) {
                    return;
                }

                $flag = self::getFilterFlag($filter);
                $value = empty($flag) ? filter_var($value, constant($filter)) :
                    filter_var($value, constant($flag[0]), constant($flag[1]));

                if (!$value) {
                    $filtersError[] = "Invalid value for 'FILTER': $value";
                }
                break;
        }
    }

    protected static function getFilterFlag(string $filter): array
    {
        if (strpos($filter, '-') === false) {
            return [];
        }

        return explode('-', $filter);
    }

    protected static function getErrorMsg(array &$error): string
    {
        $result = '';

        foreach ($error as $param => $errors) {
            $result .= $param . ': ' . implode(', ', $errors) . ' | ';
        }

        return $result;
    }
}
