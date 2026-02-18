<?php
class ExpertRequest
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getOpenRequests()
    {
        $this->db->query("SELECT er.*, u.username as farmer_name 
                          FROM expert_requests er 
                          JOIN users u ON er.user_id = u.id 
                          WHERE er.status = 'open' 
                          ORDER BY er.created_at DESC");
        return $this->db->resultSet();
    }

    public function getRequestsByUserId($id)
    {
        // Fetch requests for a specific farmer, including the answer
        $this->db->query("SELECT * FROM expert_requests WHERE user_id = :id ORDER BY created_at DESC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function getRequestById($id)
    {
        $this->db->query("SELECT er.*, u.username as farmer_name 
                          FROM expert_requests er 
                          JOIN users u ON er.user_id = u.id 
                          WHERE er.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function submitRequest($data)
    {
        $this->db->query('INSERT INTO expert_requests (user_id, subject, message) VALUES (:user_id, :subject, :message)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function submitReply($data)
    {
        $this->db->query("UPDATE expert_requests 
                          SET answer = :answer, 
                              expert_id = :expert_id, 
                              answered_at = NOW(), 
                              status = 'answered' 
                          WHERE id = :id");
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':expert_id', $data['expert_id']);
        $this->db->bind(':id', $data['id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
