<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class CategoryModel extends Model
{
    protected $table = 'categories';

    public function findAll()
    {
        // stm là viết tắt của statement
        $stm = $this->pdo->prepare("select * from {$this->table} where active = 1");

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByName($name)
    {
        $stm = $this->pdo->prepare("select * from {$this->table} where name = ?");

        $stm->execute([$name]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByPk($id)
    {
        $stm = $this->pdo->prepare("
            select id, name from {$this->table}
            where id = ? and active = 1
        ");

        $stm->execute([$id]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateByPk($id, $category)
    {
        $keys = [];
        $values = [];
        foreach ($category as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        $values[] = $id;
        $sql = sprintf(
            "update %s set %s where %s",
            $this->table,
            join(" = ?, ", $keys) . " = ?",
            "id = ?"
        );
        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($values);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function inset($category)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->table,
            implode(', ', array_keys($category)),
            ':' . implode(', :', array_keys($category))
        );

        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($category);

            return $this->findByName($category['name']);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $stm = $this->pdo->prepare("
                update {$this->table} set active = 0 where id = ? 
            ");

            $stm->execute([$id]);
        } catch (PDOException $e) {
            throw $e;
        }
        return true;
    }
}