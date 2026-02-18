<?php
class Profile
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getProfileByUserId($user_id)
    {
        $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    public function updateProfile($data)
    {
        $this->db->query('INSERT INTO profiles (user_id, full_name, address, phone, farm_size, main_crops) 
                          VALUES (:user_id, :full_name, :address, :phone, :farm_size, :main_crops)
                          ON DUPLICATE KEY UPDATE 
                          full_name = :full_name, address = :address, phone = :phone, farm_size = :farm_size, main_crops = :main_crops');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':farm_size', $data['farm_size']);
        $this->db->bind(':main_crops', $data['main_crops']);

        return $this->db->execute();
    }

    public function updateProfilePicture($user_id, $path)
    {
        $this->db->query('INSERT INTO profiles (user_id, profile_picture) 
                          VALUES (:user_id, :path)
                          ON DUPLICATE KEY UPDATE 
                          profile_picture = :path');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':path', $path);

        return $this->db->execute();
    }
}
