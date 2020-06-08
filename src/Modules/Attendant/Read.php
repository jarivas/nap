<?php


namespace Modules\Attendant;


use Core\Db\Persistence;
use Modules\Action;

class Read extends Action
{
    public static function process(array $params, Persistence $persistence): array
    {
        $options = [
            'skip' => $params['page'] * $params['page_length'],
            'limit' => $params['page_length']
        ];

        return ['success' => true, 'result' => $persistence->read([], $options)];
    }
}
