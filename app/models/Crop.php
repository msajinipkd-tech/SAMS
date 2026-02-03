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
        $this->db->query('INSERT INTO crops (name, type, description) VALUES(:name, :type, :description)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
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
        $this->db->query('UPDATE crops SET name = :name, type = :type, description = :description WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
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
