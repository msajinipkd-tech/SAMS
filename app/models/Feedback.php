<?php
class Feedback {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Add Feedback
    public function addFeedback($data) {
        $this->db->query('INSERT INTO feedback (user_id, message) VALUES(:user_id, :message)');
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get all feedbacks
    public function getFeedbacks() {
        $this->db->query('SELECT *, 
                        feedback.id as feedbackId, 
                        users.id as userId, 
                        feedback.created_at as feedbackCreated
                        FROM feedback 
                        INNER JOIN users 
                        ON feedback.user_id = users.id 
                        ORDER BY feedback.created_at DESC');

        return $this->db->resultSet();
    }
}
