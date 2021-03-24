<?php


namespace App\Controllers;


use App\Models\UserModel;
use App\Utils\Auth;
use App\Utils\Session;
use Core\App;
use Core\Controller;
use Core\Request;
use Exception;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        Auth::checkUnauthenticated('/');

        return $this->view('authentication/login');
    }

    public function login()
    {
        $data = Request::getBody();
        $result = $this->userModel->findByEmail($data['email']);
        if (count($result)) {
            $user = $result[0];
            if ($user->password === $data['password']) {
                $_SESSION['user_id'] = $user->id;

                echo json_encode([
                    "success" => true,
                    "user" => $_SESSION['user_id']
                ]);
            } else {
                echo json_encode([
                    "error" => "Tài khoản hoặc mật khẩu không chính xác."
                ]);
            }
        } else {
            echo json_encode([
                "error" => "Tài khoản hoặc mật khẩu không chính xác."
            ]);
        }
    }

    public function logout()
    {
        Session::destroy();

        $this->redirect('/login');
    }
}