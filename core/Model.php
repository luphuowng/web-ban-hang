<?php


namespace Core;


use Core\Database\Connection;

abstract class Model
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = Connection::make(App::get('config')['database']);
    }
}