<?php
class Buyer extends Controller
{
    private $productModel;
    private $cartModel;
    private $orderModel;
    private $userModel;
    private $feedbackModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
        }

        $this->productModel = $this->model('Product');
        $this->cartModel = $this->model('Cart');
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
        $this->feedbackModel = $this->model('Feedback');
    }

    public function dashboard()
    {
        // Fetch 4 most recent products for Quick Access
        $recentProducts = $this->productModel->getProducts(null, 4);
        
        $data = [
            'recentProducts' => $recentProducts
        ];

        $this->view('buyer/dashboard', $data);
    }

    public function shop()
    {
        $products = [];
        $category = isset($_GET['category']) ? $_GET['category'] : null;

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
            $products = $this->productModel->searchProducts($_GET['search']);
        } else {
            $products = $this->productModel->getProducts($category);
        }

        $categories = $this->productModel->getCategories();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $category
        ];

        $this->view('buyer/shop', $data);
    }

    public function cart()
    {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->total_price;
        }

        $data = [
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->view('buyer/cart', $data);
    }

    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'product_id' => $_POST['product_id'],
                'quantity' => $_POST['quantity']
            ];

            // Check product availability
            $product = $this->productModel->getProductById($data['product_id']);
            if ($product->quantity < $data['quantity']) {
                flash('cart_error', 'Product not available for this quantity', 'alert alert-danger');
                header('location: ' . URLROOT . '/buyer/shop');
                return;
            }

            if ($this->cartModel->addToCart($data['user_id'], $data['product_id'], $data['quantity'])) {
                header('location: ' . URLROOT . '/buyer/cart');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function removeFromCart($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->cartModel->removeFromCart($id)) {
                header('location: ' . URLROOT . '/buyer/cart');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function checkout()
    {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        if (empty($cartItems)) {
            header('location: ' . URLROOT . '/buyer/shop');
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->total_price;
        }

        $data = [
            'cartItems' => $cartItems,
            'total' => $total,
            'card_name' => '',
            'card_number' => '',
            'card_expiry' => '',
            'card_cvv' => '',
            'phone' => '',
            'card_name_err' => '',
            'card_number_err' => '',
            'card_expiry_err' => '',
            'card_cvv_err' => '',
            'phone_err' => '',
            'general_err' => ''
        ];
        $this->view('buyer/checkout', $data);
    }

    public function placeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'card_name' => trim($_POST['card_name']),
                'card_number' => str_replace([' ', '-'], '', $_POST['card_number']), // Strip spaces and dashes
                'card_expiry' => trim($_POST['card_expiry']),
                'card_cvv' => trim($_POST['card_cvv']),
                'card_name_err' => '',
                'card_number_err' => '',
                'card_expiry_err' => '',
                'card_cvv_err' => '',
                'phone_err' => '',
                'general_err' => ''
            ];

            // Debug Logging
            $logMsg = date('[Y-m-d H:i:s] ') . "Payment Attempt - User: " . $_SESSION['user_id'] . "\n";
            file_put_contents('debug.log', $logMsg, FILE_APPEND);

            // Validate Phone Number
            if (empty($_POST['phone'])) {
                 $data['phone_err'] = 'Please enter phone number';
            } elseif (!preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
                 $data['phone_err'] = 'Invalid phone number (10 digits required)';
            } else {
                 $data['phone'] = trim($_POST['phone']);
            }

            // Validate Card Number
            if (empty($data['card_number'])) {
                $data['card_number_err'] = 'Please enter card number';
            } elseif (!is_numeric($data['card_number'])) {
                $data['card_number_err'] = 'Card number must be numeric (digits only)';
            } elseif (strlen($data['card_number']) < 13 || strlen($data['card_number']) > 16) {
                $data['card_number_err'] = 'Card number must be between 13 and 16 digits';
            }

            // Validate Expiration Date
            if (empty($data['card_expiry'])) {
                $data['card_expiry_err'] = 'Please enter expiration date';
            } else {
                if (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2}|[0-9]{4})$/', $data['card_expiry'], $matches)) {
                     $data['card_expiry_err'] = 'Invalid format (MM/YY or MM/YYYY)';
                } else {
                    $month = $matches[1];
                    $year = $matches[2];
                    if (strlen($year) == 2) {
                        $year = '20' . $year;
                    }
                    $currentYear = date('Y');
                    $currentMonth = date('m');

                    if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                        $data['card_expiry_err'] = 'Card has expired';
                    }
                }
            }

            // Validate CVV
            if (empty($data['card_cvv'])) {
                 $data['card_cvv_err'] = 'Please enter CVV';
            } elseif (!is_numeric($data['card_cvv'])) {
                $data['card_cvv_err'] = 'CVV must be numeric';
            } elseif (strlen($data['card_cvv']) < 3 || strlen($data['card_cvv']) > 4) {
                $data['card_cvv_err'] = 'CVV must be 3 or 4 digits';
            }
            
            // Validate Name
            if (empty($data['card_name'])) {
                $data['card_name_err'] = 'Please enter name on card';
            }

            // Make sure errors are empty
            if (empty($data['card_name_err']) && empty($data['card_number_err']) && empty($data['card_expiry_err']) && empty($data['card_cvv_err']) && empty($data['phone_err'])) {
                // Validated
                $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
                if (empty($cartItems)) {
                    header('location: ' . URLROOT . '/buyer/shop');
                    exit;
                }

                if ($this->orderModel->createOrder($_SESSION['user_id'], $cartItems)) {
                    $this->cartModel->clearCart($_SESSION['user_id']);
                    file_put_contents('debug.log', "Order Success for User " . $_SESSION['user_id'] . "\n", FILE_APPEND);
                    flash('order_message', 'Order Placed Successfully! Payment Verified.');
                    header('location: ' . URLROOT . '/buyer/orders');
                } else {
                     // Log the error
                    file_put_contents('debug.log', "Order Creation Failed for User " . $_SESSION['user_id'] . " (Database/Stock)\n", FILE_APPEND);
                    $data['general_err'] = 'Order failed! Insufficient Stock or System Error. Please try again.';
                    
                    // Reload view with general error
                    $total = 0;
                    foreach ($cartItems as $item) {
                        $total += $item->total_price;
                    }
                    $data['cartItems'] = $cartItems;
                    $data['total'] = $total;
                    $this->view('buyer/checkout', $data);
                }
            } else {
                // Log validation errors
                file_put_contents('debug.log', "Validation Errors: " . print_r($data, true) . "\n", FILE_APPEND);

                // Load view with errors
                $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
                $total = 0;
                foreach ($cartItems as $item) {
                    $total += $item->total_price;
                }
                $data['cartItems'] = $cartItems;
                $data['total'] = $total;
                
                $this->view('buyer/checkout', $data);
            }
        }
    }

    public function orders()
    {
        $orders = $this->orderModel->getOrdersByUserId($_SESSION['user_id']);
        $data = [
            'orders' => $orders
        ];
        $this->view('buyer/orders', $data);
    }

    public function cancelOrder($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->orderModel->cancelOrder($id)) {
                flash('order_message', 'Order Cancelled Successfully', 'alert alert-danger');
                header('location: ' . URLROOT . '/buyer/orders');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function feedback() {
        $data = [
            'title' => 'Give Feedback',
            'message' => '',
            'message_err' => ''
        ];
        $this->view('buyer/feedback', $data);
    }

    public function submitFeedback() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user_id' => $_SESSION['user_id'],
                'message' => trim($_POST['message']),
                'message_err' => ''
            ];

            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your feedback message';
            }

            if (empty($data['message_err'])) {
                if ($this->feedbackModel->addFeedback($data)) {
                    flash('feedback_success', 'Thank you for your feedback!');
                    redirect('buyer/dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('buyer/feedback', $data);
            }
        } else {
            redirect('buyer/feedback');
        }
    }

    public function addReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'product_id' => $_POST['product_id'],
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'],
                'review' => trim($_POST['review'])
            ];

            // Simple validation
            if(!empty($data['review']) && !empty($data['rating'])) {
                $this->reviewModel = $this->model('Review'); // Ensure model is loaded if not already
                if($this->reviewModel->addReview($data)) {
                    flash('review_success', 'Review submitted successfully');
                } else {
                    flash('review_error', 'Something went wrong', 'alert alert-danger');
                }
            } else {
                flash('review_error', 'Please fill in all fields', 'alert alert-danger');
            }
            redirect('products/show/' . $data['product_id']);
        }
    }
}
