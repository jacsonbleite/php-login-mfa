<?php
require __DIR__ . '/vendor/autoload.php';

use \App\Common\Environment;
use App\Http\Router;

Environment::load(__DIR__);

$routerObj = new Router(getenv('URL'));


include __DIR__ . '/routes/api.php';

$routerObj->run()->sendResponse();
