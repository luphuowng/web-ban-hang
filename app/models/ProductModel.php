<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $categoryTable = 'categories';

    public function findAll()
    {
        // stm là viết tắt của statement
        $stm = $this->pdo->prepare("
            select p.*, c.name as category_name from {$this->table} as p 
            left join {$this->categoryTable} as c
                on c.id = p.category_id
            where p.active = 1
        ");

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByNameAndCategoryId($name, $categoryId)
    {
        $stm = $this->pdo->prepare("
            select p.*, c.name as category_name from `{$this->table}` as p 
            left join {$this->categoryTable} as c
                on c.id = p.category_id 
            where p.name = ? and p.category_id = ?
        ");

        $stm->execute([$name, $categoryId]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByPk($id)
    {
        $stm = $this->pdo->prepare("
            select p.*, c.name as category_name from {$this->table} as p 
            left join {$this->categoryTable} as c
                on c.id = p.category_id
            where p.id = ? and p.active = 1
        ");

        $stm->execute([$id]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateByPk($id, $data)
    {
        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
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

    public function inset($data)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->table,
            implode(', ', array_keys($data)),
            ':' . implode(', :', array_keys($data))
        );

        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($data);

            return $this->findByNameAndCategoryId($data['name'], $data['category_id']);
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