<?php
class CropCycle
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getCycles($user_id)
    {
        $this->db->query('SELECT
                            cc.*,
                            c.name as crop_name,
                            f.name as field_name
                          FROM crop_cycles cc
                          JOIN crops c ON cc.crop_id = c.id
                          JOIN fields f ON cc.field_id = f.id
                          WHERE cc.user_id = :user_id
                          ORDER BY cc.status, cc.start_date DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addCycle($data)
    {
        $this->db->query('INSERT INTO crop_cycles (user_id, crop_id, field_id, season, start_date, expected_harvest_date, status) VALUES(:user_id, :crop_id, :field_id, :season, :start_date, :expected_harvest_date, :status)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':crop_id', $data['crop_id']);
        $this->db->bind(':field_id', $data['field_id']);
        $this->db->bind(':season', $data['season']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':expected_harvest_date', $data['expected_harvest_date']);
        $this->db->bind(':status', $data['status']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCycleById($id)
    {
        $this->db->query('SELECT
                            cc.*,
                            c.name as crop_name,
                            c.variety,
                            c.duration as recommended_duration,
                            c.soil_type as required_soil_type,
                            c.water_requirement as recommended_water,
                            c.season as recommended_season,
                            f.name as field_name
                          FROM crop_cycles cc
                          JOIN crops c ON cc.crop_id = c.id
                          JOIN fields f ON cc.field_id = f.id
                          WHERE cc.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateStatus($id, $status)
    {
        $this->db->query('UPDATE crop_cycles SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function deleteCycle($id)
    {
        $this->db->query('DELETE FROM crop_cycles WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
