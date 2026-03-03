<?php

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/Views');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once ROOT_PATH . '/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();
require_once APP_PATH . '/Core/helpers.php';

session_start();

require ROOT_PATH . '/routes/web.php';

use App\Core\App;

$app = App::getInstance();
$app->run();
