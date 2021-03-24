<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class CustomerModel extends Model
{
    protected $table = 'customers';

    public function findAll()
    {
        // stm là viết tắt của statement
        $stm = $this->pdo->prepare("select * from {$this->table} where active = 1");

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByEmail($email)
    {
        $stm = $this->pdo->prepare("select * from {$this->table} where email = ?");

        $stm->execute([$email]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByPk($id)
    {
        $stm = $this->pdo->prepare("
            select id, name, email ,phone, address from {$this->table}
            where id = ? and active = 1
        ");

        $stm->execute([$id]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateByPk($id, $customer)
    {
        $keys = [];
        $values = [];
        foreach ($customer as $key => $value) {
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

    public function inset($customer)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->table,
            implode(', ', array_keys($customer)),
            ':' . implode(', :', array_keys($customer))
        );

        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($customer);

            return $this->findByEmail($customer['email']);
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