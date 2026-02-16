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
        $data = [
            'title' => 'Expert Advice'
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
}
