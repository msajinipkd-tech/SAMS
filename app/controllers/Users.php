<?php
class Users extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login()
    {
        // Init data
        $data = [
            'username' => '',
            'password' => '',
            'username_err' => '',
            'password_err' => '',
        ];

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data['username'] = trim($_POST['username']);
            $data['password'] = trim($_POST['password']);

            // Validate Email
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if ($this->userModel->findUserByUsername($data['username'])) {
                // User found
            } else {
                $data['username_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_role'] = $user->role;

        // Redirect based on role or to dashboard
        if ($user->role == 'admin') {
            header('location: ' . URLROOT . '/admin/dashboard');
        } elseif ($user->role == 'farmer') {
            header('location: ' . URLROOT . '/farmer/dashboard');
        } elseif ($user->role == 'buyer') {
            header('location: ' . URLROOT . '/buyer/dashboard');
        } else {
            header('location: ' . URLROOT . '/pages/index');
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_role']);
        session_destroy();
        header('location: ' . URLROOT . '/users/login');
    }

    // Admin Managment Methods

    public function manage()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URLROOT . '/users/login');
        }

        $users = $this->userModel->getUsers();
        $data = [
            'users' => $users
        ];
        $this->view('users/manage', $data);
    }

    public function add()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URLROOT . '/users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'role' => trim($_POST['role']),
                'username_err' => '',
                'password_err' => '',
                'role_err' => ''
            ];

            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } else {
                if ($this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username is already taken';
                }
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (empty($data['role'])) {
                $data['role_err'] = 'Please select role';
            }

            if (empty($data['username_err']) && empty($data['password_err']) && empty($data['role_err'])) {
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->userModel->addUser($data)) {
                    header('location: ' . URLROOT . '/users/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/add', $data);
            }
        } else {
            $data = [
                'username' => '',
                'password' => '',
                'role' => 'farmer',
                'username_err' => '',
                'password_err' => '',
                'role_err' => ''
            ];
            $this->view('users/add', $data);
        }
    }

    public function edit($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URLROOT . '/users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'username' => trim($_POST['username']),
                'role' => trim($_POST['role']),
                'username_err' => '',
                'role_err' => ''
            ];

            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            if (empty($data['role'])) {
                $data['role_err'] = 'Please select role';
            }

            if (empty($data['username_err']) && empty($data['role_err'])) {
                if ($this->userModel->updateUser($data)) {
                    header('location: ' . URLROOT . '/users/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/edit', $data);
            }
        } else {
            $user = $this->userModel->getUserById($id);

            $data = [
                'id' => $id,
                'username' => $user->username,
                'role' => $user->role,
                'username_err' => '',
                'role_err' => ''
            ];
            $this->view('users/edit', $data);
        }
    }

    public function delete($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URLROOT . '/users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->deleteUser($id)) {
                header('location: ' . URLROOT . '/users/manage');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/users/manage');
        }
    }
}
