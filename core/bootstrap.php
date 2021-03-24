<?php

use Core\App;
use Core\Database\QueryBuilder;

session_start();

App::bind('config', require_once 'config.php');
App::bind('database', new QueryBuilder());
App::bind("user", null);

function view($name, $data = [])
{
    extract($data);
    return require_once "app/views/{$name}.php";
}

function redirect($path)
{
    header("Location: /{$path}");
}
