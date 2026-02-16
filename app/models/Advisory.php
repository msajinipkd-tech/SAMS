<?php
class Advisory {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addAdvisory($data) {
        $this->db->query('INSERT INTO advisories (expert_id, title, message, type) VALUES (:expert_id, :title, :message, :type)');
        $this->db->bind(':expert_id', $data['expert_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':type', $data['type']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdvisories() {
        $this->db->query('SELECT *, advisories.created_at as created_at, users.username as expert_name FROM advisories JOIN users ON advisories.expert_id = users.id ORDER BY advisories.created_at DESC');
        return $this->db->resultSet();
    }

    public function getAdvisoriesByExpert($expertId) {
        $this->db->query('SELECT * FROM advisories WHERE expert_id = :expert_id ORDER BY created_at DESC');
        $this->db->bind(':expert_id', $expertId);
        return $this->db->resultSet();
    }
}
