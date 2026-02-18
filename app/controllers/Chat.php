<?php
class Chat extends Controller {
    private $chatModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
        }
        $this->chatModel = $this->model('ChatModel');
        $this->userModel = $this->model('User');
        
        // Update activity
        if(isset($_SESSION['user_id'])){
             $this->userModel->updateActivity($_SESSION['user_id']);
        }
    }

    public function index() {
        // Load the chat view
        // Pass necessary data like current user info if needed
        $conversations = $this->chatModel->getConversations($_SESSION['user_id']);
        $users = $this->userModel->getUsers(); // Get all users for "New Chat"
        
        // Filter out admins if current user is expert
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'expert'){
            $conversations = array_filter($conversations, function($c){
                return $c->role != 'admin';
            });
            $users = array_filter($users, function($u){
                return $u->role != 'admin';
            });
        }

        $data = [
            'conversations' => $conversations,
            'users' => $users
        ];
        $this->view('expert/chat_new', $data);
    }

    public function fetch($other_user_id) {
        // FETCH messages via AJAX
        // Should return JSON
        if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {
             $messages = $this->chatModel->getMessages($_SESSION['user_id'], $other_user_id);
             
             // Mark as read logic could go here
             
             header('Content-Type: application/json');
             echo json_encode($messages);
        }
    }

    public function send() {
        // SEND message via AJAX
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data?
            // Assuming JSON input or Form data. Let's support standard POST for AJAX.
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => $_POST['receiver_id'],
                'message' => $_POST['message']
            ];

            if ($this->chatModel->addMessage($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Something went wrong']);
            }
        }
    }
    
    // Method to get user details for the chat header
    public function getUser($id) {
        $user = $this->userModel->getUserById($id);
        header('Content-Type: application/json');
        echo json_encode($user);
    }

    public function sendVoice() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             if(isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(APPROOT) . '/public/uploads/voice/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Ensure dir exists
                }

                $fileName = 'voice_' . time() . '_' . uniqid() . '.webm';
                $uploadPath = $uploadDir . $fileName;

                if(move_uploaded_file($_FILES['audio']['tmp_name'], $uploadPath)) {
                    $data = [
                        'sender_id' => $_SESSION['user_id'],
                        'receiver_id' => $_POST['receiver_id'],
                        'message' => '[VOICE]' . $fileName
                    ];

                    if ($this->chatModel->addMessage($data)) {
                        echo json_encode(['status' => 'success']);
                    } else {
                         echo json_encode(['status' => 'error', 'message' => 'Database error']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'File upload failed']);
                }
             } else {
                 echo json_encode(['status' => 'error', 'message' => 'No audio file received']);
             }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->chatModel->deleteMessage($id, $_SESSION['user_id'])) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Something went wrong']);
            }
        }
    }
}
