<?php
class FarmLog
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // --- IRRIGATION ---
    public function addIrrigation($data)
    {
        $this->db->query('INSERT INTO irrigation_logs (cycle_id, date, duration, method, water_volume, notes) VALUES(:cycle_id, :date, :duration, :method, :water_volume, :notes)');
        $this->db->bind(':cycle_id', $data['cycle_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':method', $data['method']);
        $this->db->bind(':water_volume', $data['water_volume']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    public function getIrrigationLogs($cycle_id)
    {
        $this->db->query('SELECT * FROM irrigation_logs WHERE cycle_id = :cycle_id ORDER BY date DESC');
        $this->db->bind(':cycle_id', $cycle_id);
        return $this->db->resultSet();
    }

    // --- NUTRIENTS ---
    public function addNutrient($data)
    {
        $this->db->query('INSERT INTO nutrient_logs (cycle_id, fertilizer_id, date, quantity_used, notes) VALUES(:cycle_id, :fertilizer_id, :date, :quantity_used, :notes)');
        $this->db->bind(':cycle_id', $data['cycle_id']);
        $this->db->bind(':fertilizer_id', $data['fertilizer_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':quantity_used', $data['quantity_used']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    public function getNutrientLogs($cycle_id)
    {
        $this->db->query('SELECT nl.*, fi.name as fertilizer_name FROM nutrient_logs nl LEFT JOIN fertilizer_inventory fi ON nl.fertilizer_id = fi.id WHERE nl.cycle_id = :cycle_id ORDER BY nl.date DESC');
        $this->db->bind(':cycle_id', $cycle_id);
        return $this->db->resultSet();
    }

    // --- PESTS ---
    public function addPestReport($data)
    {
        $this->db->query('INSERT INTO pest_reports (cycle_id, pest_name, severity, observation_date, image_path, notes) VALUES(:cycle_id, :pest_name, :severity, :observation_date, :image_path, :notes)');
        $this->db->bind(':cycle_id', $data['cycle_id']);
        $this->db->bind(':pest_name', $data['pest_name']);
        $this->db->bind(':severity', $data['severity']);
        $this->db->bind(':observation_date', $data['observation_date']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    public function getPestReports($cycle_id)
    {
        $this->db->query('SELECT * FROM pest_reports WHERE cycle_id = :cycle_id ORDER BY observation_date DESC');
        $this->db->bind(':cycle_id', $cycle_id);
        return $this->db->resultSet();
    }

    // --- HARVESTS ---
    public function addHarvest($data)
    {
        $this->db->query('INSERT INTO harvests (cycle_id, date, quantity, unit, market_price, notes) VALUES(:cycle_id, :date, :quantity, :unit, :market_price, :notes)');
        $this->db->bind(':cycle_id', $data['cycle_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':market_price', $data['market_price']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    public function getHarvests($cycle_id)
    {
        $this->db->query('SELECT * FROM harvests WHERE cycle_id = :cycle_id ORDER BY date DESC');
        $this->db->bind(':cycle_id', $cycle_id);
        return $this->db->resultSet();
    }
}
