<?php
class Crop
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getCrops()
    {
        $this->db->query('SELECT * FROM crops ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function addCrop($data)
    {
        $this->db->query('INSERT INTO crops (name, type, variety, season, duration, soil_type, water_requirement, description) VALUES(:name, :type, :variety, :season, :duration, :soil_type, :water_requirement, :description)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':variety', $data['variety']);
        $this->db->bind(':season', $data['season']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':soil_type', $data['soil_type']);
        $this->db->bind(':water_requirement', $data['water_requirement']);
        $this->db->bind(':description', $data['description']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCropById($id)
    {
        $this->db->query('SELECT * FROM crops WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateCrop($data)
    {
        $this->db->query('UPDATE crops SET name = :name, type = :type, variety = :variety, season = :season, duration = :duration, soil_type = :soil_type, water_requirement = :water_requirement, description = :description WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':variety', $data['variety']);
        $this->db->bind(':season', $data['season']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':soil_type', $data['soil_type']);
        $this->db->bind(':water_requirement', $data['water_requirement']);
        $this->db->bind(':description', $data['description']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCrop($id)
    {
        $this->db->query('DELETE FROM crops WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
