<?php


namespace Core;


class Sanitize
{
    public static function process(string $module, string $action, array $parameters): array
    {
        $sanitize = Configuration::getData('sanitize');
        $preKey = "{$module}_{$action}_";
        $key = '';
        $result = [];

        foreach ($parameters as $name => $value) {
            $key = $preKey . $name;

            if (!empty($sanitize[$key])) {
                self::applyFilters(explode('|', $sanitize[$key]), $value);

                if($value) {
                    $result[$name] = $value;
                }
            }
        }

        return $result;
    }

    private static function applyFilters(array $filters, &$value)
    {

    }
}
