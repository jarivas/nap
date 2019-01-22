<?php

namespace Nap;

class Crypto {

    protected static $cost = 12;
    protected static $algo = PASSWORD_BCRYPT;

    public static function hash(string $password): string {
        global $appConfig;
        
        $options = ['cost' => self::$cost];
        
        if( isset($appConfig['auth']) && isset($appConfig['auth']['salt']))
            $options['salt'] = $appConfig['auth']['salt'];
        
        return password_hash($password, self::$algo, $options);
    }

}
