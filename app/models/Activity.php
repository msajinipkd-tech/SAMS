<?php
class Activity
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Get all activities for a user
    public function getActivities($userId)
    {
        $this->db->query('SELECT * FROM activities WHERE user_id = :user_id ORDER BY date ASC, created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Add new activity
    public function addActivity($data)
    {
        $this->db->query('INSERT INTO activities (user_id, title, description, date) VALUES (:user_id, :title, :description, :date)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':date', $data['date']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update activity
    public function updateActivity($data)
    {
        $this->db->query('UPDATE activities SET title = :title, description = :description, date = :date WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':date', $data['date']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete activity
    public function deleteActivity($id)
    {
        $this->db->query('DELETE FROM activities WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get single activity by ID
    public function getActivityById($id)
    {
        $this->db->query('SELECT * FROM activities WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Toggle status
    public function toggleStatus($id)
    {
        // First get current status
        $activity = $this->getActivityById($id);
        $newStatus = ($activity->status == 'pending') ? 'completed' : 'pending';

        $this->db->query('UPDATE activities SET status = :status WHERE id = :id');
        $this->db->bind(':status', $newStatus);
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
