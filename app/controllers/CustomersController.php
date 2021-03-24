<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Utils\Auth;
use Core\Controller;
use Core\Request;

class CustomersController extends Controller
{
    public $customerModel;

    public function __construct()
    {
        Auth::checkAuthenticated();

        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $customers = $this->customerModel->findAll();

        return $this->view('customers', [
            'customers' => $customers
        ]);
    }

    public function show()
    {
        $data = Request::getQueries();

        $customer = $this->customerModel->findByPk($data['id']);

        echo json_encode([
            'customer' => $customer[0]
        ]);
    }

    public function store()
    {
        $data = Request::getBody();
        unset($data['id']);
        $customer = $this->customerModel->findByEmail($data['email']);

        if (count($customer)) {
            echo json_encode([
                "error" => 'Email đã được sử dụng.'
            ]);
        } else {
            $customer = $this->customerModel->inset($data);
            echo json_encode([
                'customer' => $customer[0]
            ]);
        }

    }

    public function update()
    {
        $data = Request::getBody();
        $customerId = $data['id'];
        unset($data['id']);
        $result = $this->customerModel->findByEmail($data['email']);
        if (count($result) && $result[0]->id !== $customerId) {
            echo json_encode([
                "error" => 'Email đã được sử dụng'
            ]);
        } else {
            if ($data['password'] === '') {
                unset($data['password']);
            }
            $this->customerModel->updateByPk($customerId, $data);
            echo json_encode([
                'message' => true,
                'customer' => $data
            ]);
        }
    }

    public function destroy()
    {
        $data = Request::getBody();

        return $this->customerModel->delete($data['id']);
    }
}