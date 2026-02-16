<?php
class Message {
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

    public function getMessages($userId, $otherUserId) {
        $this->db->query('SELECT * FROM messages WHERE (sender_id = :userId AND receiver_id = :otherUserId) OR (sender_id = :otherUserId AND receiver_id = :userId) ORDER BY created_at ASC');
        $this->db->bind(':userId', $userId);
        $this->db->bind(':otherUserId', $otherUserId);

        return $this->db->resultSet();
    }

    public function getConversations($userId) {
        // sophisticated query to get latest message per user conversation
        // For simplicity, just get distinct users who messaged or were messaged by userId
        $this->db->query('
            SELECT DISTINCT u.id, u.username, u.role 
            FROM users u 
            JOIN messages m ON (m.sender_id = u.id AND m.receiver_id = :userId) OR (m.sender_id = :userId AND m.receiver_id = u.id)
        ');
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }

    public function markAsRead($senderId, $receiverId) {
        $this->db->query('UPDATE messages SET read_status = 1 WHERE sender_id = :senderId AND receiver_id = :receiverId');
        $this->db->bind(':senderId', $senderId);
        $this->db->bind(':receiverId', $receiverId);
        return $this->db->execute();
    }
}
