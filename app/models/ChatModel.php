<?php
class ChatModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addMessage($data) {
        $this->db->query('INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)');
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':message', $data['message']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getMessages($sender_id, $receiver_id) {
        $this->db->query('SELECT * FROM messages WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) OR (sender_id = :receiver_id AND receiver_id = :sender_id) ORDER BY created_at ASC');
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':receiver_id', $receiver_id);

        return $this->db->resultSet();
    }

    public function getConversations($user_id) {
        // Get all unique users who have exchanged messages with current user
        $this->db->query('
            SELECT DISTINCT u.id, u.username, u.role 
            FROM users u 
            JOIN messages m ON (m.sender_id = u.id AND m.receiver_id = :user_id) OR (m.sender_id = :user_id AND m.receiver_id = u.id)
        ');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Get unread count for a user (optional but good for dashboard)
    public function getUnreadCount($user_id) {
        $this->db->query('SELECT COUNT(*) as count FROM messages WHERE receiver_id = :user_id AND read_status = 0');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row->count;
    }

    public function deleteMessage($id, $user_id) {
        $this->db->query('DELETE FROM messages WHERE id = :id AND sender_id = :sender_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':sender_id', $user_id);

        if ($this->db->execute()) {
            return true; 
            // Note: execute() returns true on success, even if 0 rows deleted. 
            // But from security perspective, if it's not their message, it won't be deleted, which is fine.
            // Ideally we might want to know if it was deleted, but this is acceptable for now.
        } else {
            return false;
        }
    }
}
