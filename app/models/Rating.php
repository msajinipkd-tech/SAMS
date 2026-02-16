<?php
class Rating {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Add a rating
    public function addRating($data) {
        $this->db->query('INSERT INTO expert_ratings (expert_id, farmer_id, rating, feedback) VALUES (:expert_id, :farmer_id, :rating, :feedback)');
        $this->db->bind(':expert_id', $data['expert_id']);
        $this->db->bind(':farmer_id', $data['farmer_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':feedback', $data['feedback']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get ratings for an expert
    public function getRatingsByExpert($expert_id) {
        $this->db->query('SELECT r.*, u.username as farmer_name FROM expert_ratings r JOIN users u ON r.farmer_id = u.id WHERE r.expert_id = :expert_id ORDER BY r.created_at DESC');
        $this->db->bind(':expert_id', $expert_id);
        return $this->db->resultSet();
    }

    // Get average rating
    public function getAverageRating($expert_id) {
        $this->db->query('SELECT AVG(rating) as average FROM expert_ratings WHERE expert_id = :expert_id');
        $this->db->bind(':expert_id', $expert_id);
        $row = $this->db->single();
        return $row->average ? round($row->average, 1) : 0;
    }
    
    // Check if farmer already rated expert (Optional, for now allowing multiple ratings or just one logic can be handled in controller)
    public function hasRated($expert_id, $farmer_id) {
        $this->db->query('SELECT * FROM expert_ratings WHERE expert_id = :expert_id AND farmer_id = :farmer_id');
        $this->db->bind(':expert_id', $expert_id);
        $this->db->bind(':farmer_id', $farmer_id);
        
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}
