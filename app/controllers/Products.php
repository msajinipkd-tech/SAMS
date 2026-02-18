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
                'image' => '',
                'name_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'image_err' => ''
            ];

            // Image file handling
            if (!empty($_FILES['image']['name'])) {
                $imageName = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageSize = $_FILES['image']['size'];
                $imageError = $_FILES['image']['error'];

                $imageExt = explode('.', $imageName);
                $imageActualExt = strtolower(end($imageExt));
                $allowed = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($imageActualExt, $allowed)) {
                    if ($imageError === 0) {
                        if ($imageSize < 5000000) { // 5MB limit
                            $imageNameNew = uniqid('', true) . "." . $imageActualExt;
                            $imageDestination = 'img/products/' . $imageNameNew;
                            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                                $data['image'] = $imageNameNew;
                            } else {
                                $data['image_err'] = 'Failed to upload image';
                            }
                        } else {
                            $data['image_err'] = 'Your file is too big';
                        }
                    } else {
                        $data['image_err'] = 'There was an error uploading your file';
                    }
                } else {
                    $data['image_err'] = 'You cannot upload files of this type';
                }
            }

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['quantity_err']) && empty($data['image_err'])) {
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
                'description' => '',
                'image' => '',
                'name_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'image_err' => ''
            ];
            $this->view('products/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get existing product to preserve image if not updated
            $product = $this->productModel->getProductById($id);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'description' => trim($_POST['description']),
                'image' => $product->image, // Default to existing image
                'name_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'image_err' => ''
            ];

            // Image file handling
            if (!empty($_FILES['image']['name'])) {
                $imageName = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageSize = $_FILES['image']['size'];
                $imageError = $_FILES['image']['error'];

                $imageExt = explode('.', $imageName);
                $imageActualExt = strtolower(end($imageExt));
                $allowed = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($imageActualExt, $allowed)) {
                    if ($imageError === 0) {
                        if ($imageSize < 5000000) { // 5MB limit
                            $imageNameNew = uniqid('', true) . "." . $imageActualExt;
                            $imageDestination = 'img/products/' . $imageNameNew;
                            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                                $data['image'] = $imageNameNew;
                            } else {
                                $data['image_err'] = 'Failed to upload image';
                            }
                        } else {
                            $data['image_err'] = 'Your file is too big';
                        }
                    } else {
                        $data['image_err'] = 'There was an error uploading your file';
                    }
                } else {
                    $data['image_err'] = 'You cannot upload files of this type';
                }
            }

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (empty($data['name_err']) && empty($data['price_err']) && empty($data['quantity_err']) && empty($data['image_err'])) {
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
                'description' => $product->description,
                'image' => $product->image,
                'name_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'image_err' => ''
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
