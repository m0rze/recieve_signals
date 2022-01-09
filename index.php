<?php

use App\Kernel;

require_once './vendor/autoload_runtime.php';
putenv("DATABASE_URL=mysql://".\Root\Config::$dbUser.":".\Root\Config::$dbPass."@127.0.0.1:3306/".\Root\Config::$dbName);

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
