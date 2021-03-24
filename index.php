<?php

use Core\Request;
use Core\Router;

require_once 'vendor/autoload.php';
require_once 'core/bootstrap.php';

try {
    Router::load('app/routes.php')->direct(Request::uri(), Request::method());
} catch (Exception $e) {
    echo $e->getMessage();
}


