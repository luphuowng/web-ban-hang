<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $userTable = 'users';
    protected $customerTable = 'customers';
    protected $orderDetailTable = 'order_details';
    protected $productTable = 'products';
    protected $categoryTable = 'categories';


    public function findAll()
    {
        // stm là viết tắt của statement
        $stm = $this->pdo->prepare("
            select o.*, u.name as user_name, c.name as customer_name, sum(od.quality * p.price) as total
            from orders o
                     left join users u on u.id = o.user_id
                     left join customers c on c.id = o.customer_id
                     left join order_details od on o.id = od.order_id
                     left join products p on od.product_id = p.id
            where o.active = 1 
            GROUP BY o.id
            
        ");

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findByPk($id)
    {
        $stm = $this->pdo->prepare("
            select o.*, u.name as user_name, c.name as customer_name, sum(od.quality * p.price) as total
            from orders o
                     left join users u on u.id = o.user_id
                     left join customers c on c.id = o.customer_id
                     left join order_details od on o.id = od.order_id
                     left join products p on od.product_id = p.id
            where o.active = 1 and o.id = ?
        ");

        $stm->execute([$id]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function findOrderDetailsByOrderId($id)
    {
        $stm = $this->pdo->prepare("
            select od.*, name, price, image
            from order_details od
                     left join orders o on od.order_id = o.id
                     left join products p on od.product_id = p.id
            where o.id = ? and o.active = 1;
        ");

        $stm->execute([$id]);

        return $stm->fetchAll(PDO::FETCH_OBJ);
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
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function insertOrderDetail($data)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->orderDetailTable,
            implode(', ', array_keys($data)),
            ':' . implode(', :', array_keys($data))
        );

        try {
            $stm = $this->pdo->prepare($sql);

            $stm->execute($data);
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