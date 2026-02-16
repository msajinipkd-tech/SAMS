<?php
class Setup extends Controller {
    public function __construct() {
        $this->db = new Database;
        $this->createTable();
    }

    public function index() {
        echo "Setup complete.";
    }

    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            read_status TINYINT(1) DEFAULT 0,
            FOREIGN KEY (sender_id) REFERENCES users(id),
            FOREIGN KEY (receiver_id) REFERENCES users(id)
        )";
        
        $this->db->query($sql);
        if($this->db->execute()){
            echo "Messages table created successfully.";
        } else {
            echo "Error creating messages table/already exists.";
        }
    }
}
