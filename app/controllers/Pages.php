<?php
class Pages extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            $data = [
                'title' => 'Welcome to SAMS',
                'description' => 'Smart Agriculture Management System'
            ];
            $this->view('pages/index', $data);
        } else {
            $data = [
                'title' => 'Welcome to SAMS',
                'description' => 'Please login to manage the system.'
            ];
            $this->view('pages/index', $data);
        }
    }

    public function about()
    {
        $data = [
            'title' => 'About Us',
            'description' => 'App to manage agriculture resources.'
        ];
        $this->view('pages/about', $data);
    }
}
