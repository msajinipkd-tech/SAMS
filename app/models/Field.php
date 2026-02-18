<?php
class Field
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getFields($user_id)
    {
        $this->db->query('SELECT * FROM fields WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addField($data)
    {
        $this->db->query('INSERT INTO fields (user_id, name, size, soil_type, location) VALUES(:user_id, :name, :size, :soil_type, :location)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':size', $data['size']);
        $this->db->bind(':soil_type', $data['soil_type']);
        $this->db->bind(':location', $data['location']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getFieldById($id)
    {
        $this->db->query('SELECT * FROM fields WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateField($data)
    {
        $this->db->query('UPDATE fields SET name = :name, size = :size, soil_type = :soil_type, location = :location WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':size', $data['size']);
        $this->db->bind(':soil_type', $data['soil_type']);
        $this->db->bind(':location', $data['location']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteField($id)
    {
        $this->db->query('DELETE FROM fields WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
