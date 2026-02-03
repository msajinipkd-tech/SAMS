<?php
class Pesticide
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPesticides()
    {
        $this->db->query('SELECT * FROM pesticides ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function addPesticide($data)
    {
        $this->db->query('INSERT INTO pesticides (name, type, description, usage_instructions) VALUES(:name, :type, :description, :usage_instructions)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':usage_instructions', $data['usage_instructions']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getPesticideById($id)
    {
        $this->db->query('SELECT * FROM pesticides WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updatePesticide($data)
    {
        $this->db->query('UPDATE pesticides SET name = :name, type = :type, description = :description, usage_instructions = :usage_instructions WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':usage_instructions', $data['usage_instructions']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePesticide($id)
    {
        $this->db->query('DELETE FROM pesticides WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
