<?php
class Admin extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URLROOT . '/users/login');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard'
        ];

        $this->view('admin/dashboard', $data);
    }
}
