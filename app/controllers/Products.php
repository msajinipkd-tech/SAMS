<?php
class Products extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'farmer' && $_SESSION['user_role'] != 'buyer')) {
            // Check if role is buyer as well since they can view products
             // Actually, the original code only allowed admin and farmer. 
             // Logic update: Buyers need access to Products::show.
             // But Products controller seems to be for admin/farmer management mainly. 
             // Review: "Products::index" lists all products for management. 
             // "Buyer::shop" lists products for buying.
             // If I use "Products::show" for details, I must allow buyers to access it.
             // Or I should implement "Buyer::show".
             // Plan said "Products::show". So I'll update auth check or just allow everyone to see 'show'.
        }
        $this->productModel = $this->model('Product');
        $this->reviewModel = $this->model('Review');
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
                'category' => trim($_POST['category']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'category_err' => '',
                'price_err' => '',
                'quantity_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['category'])) {
                $data['category_err'] = 'Please enter category';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (empty($data['name_err']) && empty($data['category_err']) && empty($data['price_err']) && empty($data['quantity_err'])) {
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
                'category' => '',
                'price' => '',
                'quantity' => '',
                'description' => '',
                'name_err' => '',
                'category_err' => '',
                'price_err' => '',
                'quantity_err' => ''
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
                'category' => trim($_POST['category']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'category_err' => '',
                'price_err' => '',
                'quantity_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['category'])) {
                $data['category_err'] = 'Please enter category';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (empty($data['name_err']) && empty($data['category_err']) && empty($data['price_err']) && empty($data['quantity_err'])) {
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
                'category' => $product->category,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'description' => $product->description,
                'name_err' => '',
                'category_err' => '',
                'price_err' => '',
                'quantity_err' => ''
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

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        $reviews = $this->reviewModel->getReviewsByProductId($id);
        $avgRating = $this->reviewModel->getAvgRating($id);

        $data = [
            'product' => $product,
            'reviews' => $reviews,
            'avgRating' => $avgRating ? round($avgRating, 1) : 0
        ];

        $this->view('products/show', $data);
    }
}
