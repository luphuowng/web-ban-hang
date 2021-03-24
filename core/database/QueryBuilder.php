<?php

namespace Core\Database;

use Core\App;
use PDO;
use PDOException;

class QueryBuilder
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = Connection::make(App::get('config')['database']);
    }

    public function selectAll($table)
    {
        $stm = $this->pdo->prepare("select * from {$table}");

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function insert($table, $parameters)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );

        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($parameters);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}