<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class UserModel extends Model
{
    protected $table = 'users';

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
            select id, name, email, role ,phone, address from {$this->table}
            where id = ? and active = 1
        ");

        $stm->execute([$id]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateByPk($id, $user)
    {
        $keys = [];
        $values = [];
        foreach ($user as $key => $value) {
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

    public function inset($user)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->table,
            implode(', ', array_keys($user)),
            ':' . implode(', :', array_keys($user))
        );

        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($user);

            return $this->findByEmail($user['email']);
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