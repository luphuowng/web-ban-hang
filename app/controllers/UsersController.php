<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Utils\Auth;
use Core\Controller;
use Core\Request;

class UsersController extends Controller
{
    public $userModel;

    public function __construct()
    {
        Auth::checkAuthenticated();

        $this->userModel = new UserModel();
    }

    public function index()
    {
        $users = $this->userModel->findAll();

        return $this->view('users', [
            'users' => $users
        ]);
    }

    public function show()
    {
        $data = Request::getQueries();

        $user = $this->userModel->findByPk($data['id']);

        echo json_encode([
            'user' => $user[0]
        ]);
    }

    public function store()
    {
        $data = Request::getBody();
        unset($data['id']);
        $user = $this->userModel->findByEmail($data['email']);

        if (count($user)) {
            echo json_encode([
                "error" => 'Email đã được sử dụng'
            ]);
        } else {
            $user = $this->userModel->inset($data);
            echo json_encode([
                'user' => $user[0]
            ]);
        }
    }

    public function update()
    {
        $data = Request::getBody();
        $userId = $data['id'];
        unset($data['id']);
        $user = $this->userModel->findByEmail($data['email']);
        if (count($user[0]) && $user[0]->id !== $userId) {
            echo json_encode([
                "error" => 'Email đã được sử dụng'
            ]);
        } else {
            if ($data['password'] === '') {
                unset($data['password']);
            }
            $this->userModel->updateByPk($userId, $data);
            echo json_encode([
                'message' => true,
                'user' => $data
            ]);
        }
    }

    public function destroy()
    {
        $data = Request::getBody();

        return $this->userModel->delete($data['id']);
    }
}