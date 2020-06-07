<?php

namespace Core;


trait DummyValidator
{
    public static function isValid(array $params, array $headers){
        return true;
    }
}
