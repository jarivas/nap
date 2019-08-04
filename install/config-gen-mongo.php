<?php

composerRequire('mongodb/mongodb');

composerInstall();

run('pecl install mongodb');
run('echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`');


generateIni('mongo');
