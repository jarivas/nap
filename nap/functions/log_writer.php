<?php

DEFINE('LOG_DIR', ROOT_DIR . 'log' . DIRECTORY_SEPARATOR);

function log_writer(string $level, string $message) {
    $fileName = LOG_DIR . $level . '.log';
    $microtime = explode(' ', microtime());
    $microtime = date('Y-m-d h:i:s.') . $microtime[1];
    $message = $microtime . ': ' . $message . PHP_EOL . print_r(debug_backtrace(), 1);
    
    file_put_contents($fileName, $message, FILE_APPEND | LOCK_EX);
}