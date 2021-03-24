<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Utils\Auth;
use Core\Controller;
use Core\Request;

class ProductsController extends Controller
{
    public $productModel;
    public $categoryModel;
    public $path = 'public/img/';

    public function __construct()
    {
        Auth::checkAuthenticated();

        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $products = $this->productModel->findAll();
        $categories = $this->categoryModel->findAll();

        return $this->view('products', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function show()
    {
        $data = Request::getQueries();

        $product = $this->productModel->findByPk($data['id']);

        echo json_encode([
            'product' => $product[0]
        ]);
    }

    public function store()
    {
        $data = Request::getBody();
        unset($data['id']);
        $result = $this->productModel->findByNameAndCategoryId($data['name'], $data['category_id']);
        if (count($result)) {
            echo json_encode([
                "error" => 'Sản phẩm đã tồn tại'
            ]);
        } else {
            $image = Request::file('image');

            $img = $image['name'];
            $tmp = $image['tmp_name'];
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            $now = new \DateTime();
            $final_image = $now->getTimestamp() . '.' . $ext;
            $path = $this->path . strtolower($final_image);
            $data['image'] = $path;
            if (move_uploaded_file($tmp, $path)) {
                $result = $this->productModel->inset($data);
                echo json_encode([
                    'product' => $result[0]
                ]);
            } else {
                echo json_encode([
                    "error" => 'Upload ảnh thất bại.'
                ]);
            }
        }
    }

    public function update()
    {
        $data = Request::getBody();
        $productId = $data['id'];
        unset($data['id']);
        $product = $this->productModel->findByNameAndCategoryId($data['name'], $data['category_id']);
        if (count($product) && $product[0]->id !== $productId) {
            echo json_encode([
                "error" => 'Mặt hàng đã tồn tại'
            ]);
        } else {
            $image = Request::file('image');

            if (!empty($image)) {
                $img = $image['name'];
                $tmp = $image['tmp_name'];
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $now = new \DateTime();
                $final_image = $now->getTimestamp() . '.' . $ext;
                $path = $this->path . strtolower($final_image);
                $data['image'] = $path;

                if (!move_uploaded_file($tmp, $path)) {
                    echo json_encode([
                        "error" => 'Upload ảnh thất bại.'
                    ]);
                    return;
                }
                if (file_exists($product[0]->image)) {
                    unlink($product[0]->image);
                }
            }

            $this->productModel->updateByPk($productId, $data);
            $result = $this->productModel->findByPk($productId);
            echo json_encode([
                'message' => true,
                'product' => $result[0]
            ]);
        }
    }

    public function destroy()
    {
        $data = Request::getBody();

        return $this->productModel->delete($data['id']);
    }
}