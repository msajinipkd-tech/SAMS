<?php
class Pesticides extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'farmer')) {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->pesticideModel = $this->model('Pesticide');
    }

    public function index()
    {
        $pesticides = $this->pesticideModel->getPesticides();
        $data = [
            'pesticides' => $pesticides
        ];
        $this->view('pesticides/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'description' => trim($_POST['description']),
                'usage_instructions' => trim($_POST['usage_instructions']),
                'name_err' => '',
                'type_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['type'])) {
                $data['type_err'] = 'Please enter type';
            }

            if (empty($data['name_err']) && empty($data['type_err'])) {
                if ($this->pesticideModel->addPesticide($data)) {
                    header('location: ' . URLROOT . '/pesticides');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('pesticides/add', $data);
            }
        } else {
            $data = [
                'name' => '',
                'type' => '',
                'description' => '',
                'usage_instructions' => ''
            ];
            $this->view('pesticides/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'description' => trim($_POST['description']),
                'usage_instructions' => trim($_POST['usage_instructions']),
                'name_err' => '',
                'type_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['type'])) {
                $data['type_err'] = 'Please enter type';
            }

            if (empty($data['name_err']) && empty($data['type_err'])) {
                if ($this->pesticideModel->updatePesticide($data)) {
                    header('location: ' . URLROOT . '/pesticides');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('pesticides/edit', $data);
            }
        } else {
            $pesticide = $this->pesticideModel->getPesticideById($id);
            if ($pesticide->id != $id) {
                header('location: ' . URLROOT . '/pesticides');
            }

            $data = [
                'id' => $id,
                'name' => $pesticide->name,
                'type' => $pesticide->type,
                'description' => $pesticide->description,
                'usage_instructions' => $pesticide->usage_instructions
            ];
            $this->view('pesticides/edit', $data);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->pesticideModel->deletePesticide($id)) {
                header('location: ' . URLROOT . '/pesticides');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/pesticides');
        }
    }
}
