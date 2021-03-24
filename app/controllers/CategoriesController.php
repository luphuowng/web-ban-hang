<?php


namespace App\Controllers;


use App\Models\CategoryModel;
use App\Utils\Auth;
use Core\Controller;
use Core\Request;

class CategoriesController extends Controller
{
    public $categoryModel;

    public function __construct()
    {
        Auth::checkAuthenticated();

        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->findAll();

        return $this->view('categories', [
            'categories' => $categories
        ]);
    }

    public function show()
    {
        $data = Request::getQueries();

        $user = $this->categoryModel->findByPk($data['id']);

        echo json_encode([
            'category' => $user[0]
        ]);
    }

    public function store()
    {
        $data = Request::getBody();
        unset($data['id']);
        $category = $this->categoryModel->findByName($data['name']);

        if (count($category)) {
            echo json_encode([
                "error" => 'Tên nhóm hàng đã được sử dụng'
            ]);
        } else {
            $category = $this->categoryModel->inset($data);
            echo json_encode([
                'category' => $category[0]
            ]);
        }

    }

    public function update()
    {
        $data = Request::getBody();
        $categoryId = $data['id'];
        unset($data['id']);
        $result = $this->categoryModel->findByName($data['name']);

        if (count($result) && $result[0]->id !== $categoryId) {
            echo json_encode([
                "error" => 'Tên nhóm hàng đã được sử dụng'
            ]);
        } else {
            $this->categoryModel->updateByPk($categoryId, $data);
            $result = $this->categoryModel->findByPk($categoryId);
            echo json_encode([
                'message' => true,
                'category' => $result[0]
            ]);
        }
    }

    public function destroy()
    {
        $data = Request::getBody();

        return $this->categoryModel->delete($data['id']);
    }
}