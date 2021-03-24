<?php


namespace App\Controllers;


use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Utils\Auth;
use App\Utils\Session;
use Core\Controller;
use Core\Request;

class OrdersController extends Controller
{
    protected $orderModel;
    protected $customerModel;
    protected $productModel;

    public function __construct()
    {
        Auth::checkAuthenticated();

        $this->orderModel = new OrderModel();
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $orders = $this->orderModel->findAll();
        $customers = $this->customerModel->findAll();
        $products = $this->productModel->findAll();

        return $this->view('orders', [
            'orders' => $orders,
            'customers' => $customers,
            'products' => $products
        ]);
    }

    public function getOne()
    {
        $data = Request::getQueries();

        $order_details = $this->orderModel->findOrderDetailsByOrderId($data['id']);

        echo json_encode([
            'order_details' => $order_details
        ]);
    }

    public function store()
    {
        $data = Request::getBody();
        $data['user_id'] = Session::get('user_id');

        $qualities = $data['qualities'];
        $productIds = $data['productIds'];

        $now = new \DateTime();

        $data['id'] = $now->getTimestamp();
        unset($data['qualities']);
        unset($data['productIds']);

        $this->orderModel->inset($data);

        for ($i = 0; $i < count($productIds); $i++) {
            if ($productIds[$i] !== '' && $qualities[$i] !== '') {
                $this->orderModel->insertOrderDetail([
                    "order_id" => $data['id'],
                    "product_id" => $productIds[$i],
                    "quality" => $qualities[$i],
                ]);
            }
        }
        $result = $this->orderModel->findByPk($data['id']);
        echo json_encode([
            'order' => $result[0]
        ]);
    }

    public function destroy()
    {
        $data = Request::getBody();

        return $this->orderModel->delete($data['id']);
    }
}