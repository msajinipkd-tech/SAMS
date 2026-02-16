<?php
class Expert extends Controller
{
    private $advisoryModel;
    private $messageModel;
    private $userModel;
    private $profileModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'expert') {
            header('location: ' . URLROOT . '/users/login');
        }

        $this->advisoryModel = $this->model('Advisory');
        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('User');
        $this->profileModel = $this->model('Profile');

        // Update activity
        $this->userModel->updateActivity($_SESSION['user_id']);
    }

    public function profile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user_id' => $_SESSION['user_id'],
                'full_name' => trim($_POST['full_name']),
                'address' => trim($_POST['address']),
                'phone' => trim($_POST['phone']),
                'username' => $_SESSION['user_username'], // For view
                'error' => ''
            ];

            // Validate Phone
            if(strlen($data['phone']) > 10){
                $data['error'] = 'Phone number cannot exceed 10 digits';
            } elseif (!is_numeric($data['phone']) && !empty($data['phone'])) {
                $data['error'] = 'Phone number must be numeric';
            }

            if(empty($data['error'])){
                if ($this->profileModel->updateProfile($data)) {
                     $data['success'] = 'Profile updated successfully';
                     $this->view('expert/profile', $data);
                } else {
                     $data['error'] = 'Something went wrong';
                     $this->view('expert/profile', $data);
                }
            } else {
                $this->view('expert/profile', $data);
            }

        } else {
            $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'full_name' => $profile ? $profile->full_name : '',
                'address' => $profile ? $profile->address : '',
                'phone' => $profile ? $profile->phone : '',
                'username' => $_SESSION['user_username']
            ];

            $this->view('expert/profile', $data);
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        // Get stats for dashboard
        $advisories = $this->advisoryModel->getAdvisoriesByExpert($_SESSION['user_id']);
        // For simplicity, just count
        $advisoryCount = count($advisories);
        
        // Get profile data
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        
        // Get ratings data
        $ratingModel = $this->model('Rating');
        $averageRating = $ratingModel->getAverageRating($_SESSION['user_id']);
        $recentRatings = $ratingModel->getRatingsByExpert($_SESSION['user_id']);
        
        $data = [
            'advisory_count' => $advisoryCount,
            'recent_advisories' => array_slice($advisories, 0, 5),
            'profile' => $profile,
            'average_rating' => $averageRating,
            'recent_ratings' => array_slice($recentRatings, 0, 5)
        ];

        $this->view('expert/dashboard', $data);
    }

    public function advisory()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'expert_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'message' => trim($_POST['message']),
                'type' => trim($_POST['type']),
                'title_err' => '',
                'message_err' => ''
            ];

            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter message';
            }

            if (empty($data['title_err']) && empty($data['message_err'])) {
                if ($this->advisoryModel->addAdvisory($data)) {
                    header('location: ' . URLROOT . '/expert/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('expert/advisory', $data);
            }
        } else {
            $data = [
                'title' => '',
                'message' => '',
                'type' => 'weather',
                'title_err' => '',
                'message_err' => ''
            ];
            $this->view('expert/advisory', $data);
        }
    }

    public function chat($userId = null)
    {
        // If userId is null, show list of conversations or select first one
        // For now, simple implement: separate view for list vs view
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
         
        $messages = [];
        $currentChatUser = null;

        if($userId){
             $messages = $this->messageModel->getMessages($_SESSION['user_id'], $userId);
             $currentChatUser = $this->userModel->getUserById($userId);
        }

        $data = [
            'conversations' => $conversations,
            'messages' => $messages,
            'currentChatUser' => $currentChatUser
        ];

        $this->view('expert/chat', $data);
    }

    public function sendMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
             
             $data = [
                 'sender_id' => $_SESSION['user_id'],
                 'receiver_id' => $_POST['receiver_id'],
                 'message' => $_POST['message']
             ];

             if($this->messageModel->addMessage($data)){
                 // Redirect back to chat
                 header('location: ' . URLROOT . '/expert/chat/' . $data['receiver_id']);
             } else {
                 die('Error sending message');
             }
        }
    }

    public function history()
    {
        $advisories = $this->advisoryModel->getAdvisoriesByExpert($_SESSION['user_id']);
        $data = [
            'advisories' => $advisories
        ];
        $this->view('expert/history', $data);
    }
}
