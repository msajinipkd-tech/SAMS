<?php
class Products extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'farmer')) {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        $data = [
            'products' => $products
        ];
        $this->view('products/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'price_err' => '',
                'quantity_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['quantity_err'])) {
                if ($this->productModel->addProduct($data)) {
                    header('location: ' . URLROOT . '/products');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('products/add', $data);
            }
        } else {
            $data = [
                'name' => '',
                'price' => '',
                'quantity' => '',
                'description' => ''
            ];
            $this->view('products/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'price_err' => '',
                'quantity_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['quantity_err'])) {
                if ($this->productModel->updateProduct($data)) {
                    header('location: ' . URLROOT . '/products');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('products/edit', $data);
            }
        } else {
            $product = $this->productModel->getProductById($id);
            if ($product->id != $id) {
                header('location: ' . URLROOT . '/products');
            }

            $data = [
                'id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'description' => $product->description
            ];
            $this->view('products/edit', $data);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->deleteProduct($id)) {
                header('location: ' . URLROOT . '/products');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/products');
        }
    }
}
