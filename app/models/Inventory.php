<?php
class Inventory
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // --- SEEDS ---
    public function getSeeds($user_id)
    {
        $this->db->query('SELECT * FROM seed_inventory WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addSeed($data)
    {
        $this->db->query('INSERT INTO seed_inventory (user_id, name, variety, quantity, unit, purchase_date, expiry_date) VALUES(:user_id, :name, :variety, :quantity, :unit, :purchase_date, :expiry_date)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':variety', $data['variety']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':purchase_date', $data['purchase_date']);
        $this->db->bind(':expiry_date', $data['expiry_date']);

        return $this->db->execute();
    }

    public function deleteSeed($id)
    {
        $this->db->query('DELETE FROM seed_inventory WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- FERTILIZERS ---
    public function getFertilizers($user_id)
    {
        $this->db->query('SELECT * FROM fertilizer_inventory WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addFertilizer($data)
    {
        $this->db->query('INSERT INTO fertilizer_inventory (user_id, name, type, quantity, unit) VALUES(:user_id, :name, :type, :quantity, :unit)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);

        return $this->db->execute();
    }

    public function deleteFertilizer($id)
    {
        $this->db->query('DELETE FROM fertilizer_inventory WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
