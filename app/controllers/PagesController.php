<?php

namespace App\Controllers;

use App\Utils\Auth;
use Core\Controller;

class PagesController extends Controller
{
    public function __construct()
    {
        Auth::checkAuthenticated();
    }

    public function index()
    {
        return $this->view('index');
    }

    public function notFound()
    {
        return $this->view('404');
    }
}