<?php
class Farmer extends Controller
{
    private $feedbackModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'farmer') {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->feedbackModel = $this->model('Feedback');
        $this->reviewModel = $this->model('Review');
    }

    public function reviews() {
        $reviews = $this->reviewModel->getAllReviews();
        $data = [
            'title' => 'Product Reviews',
            'reviews' => $reviews
        ];
        $this->view('farmer/reviews', $data);
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
            'title' => 'Weather Information'
        ];
        $this->view('farmer/weather', $data);
    }

    public function activities()
    {
        // Placeholder for activity model interaction
        $data = [
            'title' => 'Activity Planner'
        ];
        $this->view('farmer/activities', $data);
    }

    public function orders()
    {
        // Placeholder for order model interaction
        $data = [
            'title' => 'My Orders'
        ];
        $this->view('farmer/orders', $data);
    }

    public function expert()
    {
        // Load Chat Model
        $chatModel = $this->model('ChatModel');
        $userModel = $this->model('User');

        // Get conversations for the current farmer
        $conversations = $chatModel->getConversations($_SESSION['user_id']);
        
        // Get all experts for "New Chat"
        $experts = $userModel->getUsersByRole('expert');

        $data = [
            'title' => 'Expert Advice',
            'conversations' => $conversations,
            'experts' => $experts
        ];
        
        $this->view('farmer/expert', $data);
    }

    public function profile()
    {
        $data = [
            'title' => 'My Profile'
        ];
        $this->view('farmer/profile', $data);
    }

    public function feedback()
    {
        $feedbacks = $this->feedbackModel->getFeedbacks();
        $data = [
            'title' => 'Buyer Feedback',
            'feedbacks' => $feedbacks
        ];
        $this->view('farmer/feedback', $data);
    }

    public function rateExpert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
             
             $data = [
                 'expert_id' => $_POST['expert_id'],
                 'farmer_id' => $_SESSION['user_id'],
                 'rating' => $_POST['rating'],
                 'feedback' => $_POST['feedback']
             ];
             
             $ratingModel = $this->model('Rating');
             
             if($ratingModel->addRating($data)) {
                 echo json_encode(['status' => 'success', 'message' => 'Rating submitted successfully']);
             } else {
                 echo json_encode(['status' => 'error', 'message' => 'Something went wrong']);
             }
        }
    }
}
