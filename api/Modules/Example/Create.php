<?php


namespace Modules\Example;


use Core\Db\Persistence;
use Modules\Action;

class Create extends Action
{
    public static function process(array $params, Persistence $persistence): array
    {
        $success = $persistence->create($params);

        if ($success) {
            $success = self::sendEmail($params['email'], $persistence);
        }

        return ['success' => $success];
    }

    private static function sendEmail($email, Persistence $persistence): bool
    {
        $message = <<<EOT
Thanks a lot for register in the incoming My Type event, we hope you enjoy it
Sincerely My Type Team
EOT;
        //$success = mail($email, 'Registered on My Type', $message);
        $success = true;

        if (!$success) {
            $persistence->delete(['email' => $email]);
        }

        return $success;
    }
}
