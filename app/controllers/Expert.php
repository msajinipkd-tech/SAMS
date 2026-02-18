<?php
class Expert extends Controller
{
    private $requestModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'expert') {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->requestModel = $this->model('ExpertRequest');
    }

    public function index()
    {
        $requests = $this->requestModel->getOpenRequests();
        $data = [
            'title' => 'Expert Dashboard',
            'requests' => $requests
        ];
        $this->view('expert/dashboard', $data);
    }

    public function reply($id)
    {
        $request = $this->requestModel->getRequestById($id);
        $data = [
            'title' => 'Reply to Request',
            'request' => $request
        ];
        $this->view('expert/reply', $data);
    }

    public function submit_reply()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $_POST['request_id'],
                'expert_id' => $_SESSION['user_id'],
                'answer' => trim($_POST['answer'])
            ];

            if ($this->requestModel->submitReply($data)) {
                header('location: ' . URLROOT . '/expert/index');
            } else {
                die('Something went wrong');
            }
        }
    }
}
