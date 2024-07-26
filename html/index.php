<?php
ob_start();
require __DIR__ . '/vendor/autoload.php';

use \App\Database\DatabaseConnection;
use \App\Common\Environment;
use \App\Http\Router;

Environment::load(__DIR__);

DatabaseConnection::config(
    getenv('DB_DRIVER'),
    getenv('DB_HOST'),
    getenv('POSTGRES_DB'),
    getenv('POSTGRES_USER'),
    getenv('POSTGRES_PASSWORD'),
    getenv('DB_PORT')
);




$routerObj = new Router(getenv('URL'));


include __DIR__ . '/routes/api.php';

$routerObj->run()->sendResponse();
ob_end_flush();