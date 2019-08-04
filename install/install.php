<?php
$installDir = __DIR__;
$rootDir = dirname($installDir);

include 'basic.php';

include 'config-gen.php';

changeDir($rootDir);

run('rm install.sh');
run('rm -rf install');
