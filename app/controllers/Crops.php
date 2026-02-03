<?php
class Crops extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'farmer')) {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->cropModel = $this->model('Crop');
    }

    public function index()
    {
        $crops = $this->cropModel->getCrops();
        $data = [
            'crops' => $crops
        ];
        $this->view('crops/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'description' => trim($_POST['description']),
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
                if ($this->cropModel->addCrop($data)) {
                    header('location: ' . URLROOT . '/crops');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('crops/add', $data);
            }
        } else {
            $data = [
                'name' => '',
                'type' => '',
                'description' => ''
            ];
            $this->view('crops/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'type' => trim($_POST['type']),
                'description' => trim($_POST['description']),
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
                if ($this->cropModel->updateCrop($data)) {
                    header('location: ' . URLROOT . '/crops');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('crops/edit', $data);
            }
        } else {
            $crop = $this->cropModel->getCropById($id);
            if ($crop->id != $id) {
                header('location: ' . URLROOT . '/crops');
            }

            $data = [
                'id' => $id,
                'name' => $crop->name,
                'type' => $crop->type,
                'description' => $crop->description
            ];
            $this->view('crops/edit', $data);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->cropModel->deleteCrop($id)) {
                header('location: ' . URLROOT . '/crops');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/crops');
        }
    }
}
