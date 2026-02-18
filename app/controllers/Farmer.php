<?php
class Farmer extends Controller
{
    private $activityModel;
    private $userModel;
    private $profileModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'farmer') {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->activityModel = $this->model('Activity');
        $this->userModel = $this->model('User');
        $this->profileModel = $this->model('Profile');
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Farmer Dashboard',
            'description' => 'Welcome to your dashboard'
        ];
        $this->view('farmer/dashboard', $data);
    }

    public function weather()
    {
        $data = [
            'title' => 'Weather Forecast',
            'description' => 'Check weather forecast for your farm'
        ];
        $this->view('farmer/weather', $data);
    }

    // --- ACTIVITIES ---
    public function activities()
    {
        $activities = $this->activityModel->getActivities($_SESSION['user_id']);
        $data = [
            'title' => 'Activity Planner',
            'activities' => $activities
        ];
        $this->view('farmer/activities', $data);
    }

    public function add_activity()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'date' => trim($_POST['date'])
            ];
            $this->activityModel->addActivity($data);
            header('location: ' . URLROOT . '/farmer/activities');
        }
    }

    public function edit_activity()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $_POST['id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'date' => trim($_POST['date'])
            ];
            $this->activityModel->updateActivity($data);
            header('location: ' . URLROOT . '/farmer/activities');
        }
    }

    public function delete_activity($id)
    {
        $this->activityModel->deleteActivity($id);
        header('location: ' . URLROOT . '/farmer/activities');
    }

    public function toggle_activity_status($id)
    {
        $this->activityModel->toggleStatus($id);
        header('location: ' . URLROOT . '/farmer/activities');
    }

    public function orders()
    {
        // Placeholder for order model interaction
        $data = [
            'title' => 'My Orders'
        ];
        $this->view('farmer/orders', $data);
    }

    // --- EXPERT ADVICE ---
    public function expert()
    {
        $requestModel = $this->model('ExpertRequest');
        $requests = $requestModel->getRequestsByUserId($_SESSION['user_id']);
        $data = [
            'title' => 'Expert Advice',
            'requests' => $requests
        ];
        $this->view('farmer/expert', $data);
    }

    public function ask_expert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $requestModel = $this->model('ExpertRequest');
            $data = [
                'user_id' => $_SESSION['user_id'],
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message'])
            ];
            $requestModel->submitRequest($data);
            header('location: ' . URLROOT . '/farmer/expert');
        }
    }

    public function profile()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'profile' => $profile
        ];
        $this->view('farmer/profile', $data);
    }

    public function update_profile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user_id' => $_SESSION['user_id'],
                'full_name' => trim($_POST['full_name']),
                'address' => trim($_POST['address']),
                'phone' => trim($_POST['phone']),
                'farm_size' => trim($_POST['farm_size']),
                'main_crops' => trim($_POST['main_crops'])
            ];

            if ($this->profileModel->updateProfile($data)) {
                header('location: ' . URLROOT . '/farmer/profile?status=success');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function change_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            $user = $this->userModel->getUserById($_SESSION['user_id']);

            if (password_verify($current_password, $user->password)) {
                if ($new_password == $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    if ($this->userModel->changePassword($_SESSION['user_id'], $hashed_password)) {
                        header('location: ' . URLROOT . '/farmer/profile?password_status=success');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    header('location: ' . URLROOT . '/farmer/profile?password_error=mismatch');
                }
            } else {
                header('location: ' . URLROOT . '/farmer/profile?password_error=incorrect');
            }
        }
    }

    public function update_profile_picture()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
            $file = $_FILES['profile_picture'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 5000000) { // 5MB
                        $fileNameNew = "profile" . $_SESSION['user_id'] . "." . $fileActualExt;
                        // APPROOT is app/, we need public/img/profiles/
                        $uploadDir = dirname(APPROOT) . '/public/img/profiles/';
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $uploadPath = $uploadDir . $fileNameNew;

                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $dbPath = 'img/profiles/' . $fileNameNew;
                            $this->profileModel->updateProfilePicture($_SESSION['user_id'], $dbPath);
                            header("Location: " . URLROOT . "/farmer/profile?upload=success");
                        } else {
                            header("Location: " . URLROOT . "/farmer/profile?upload=error");
                        }
                    } else {
                        header("Location: " . URLROOT . "/farmer/profile?upload=too_big");
                    }
                } else {
                    header("Location: " . URLROOT . "/farmer/profile?upload=error_uploading");
                }
            } else {
                header("Location: " . URLROOT . "/farmer/profile?upload=invalid_type");
            }
        }
    }
}
