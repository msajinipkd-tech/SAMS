<?php
class Profile {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProfileByUserId($user_id) {
        $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $row = $this->db->single();

        return $row;
    }

    public function updateProfile($data) {
        // Check if profile exists
        $profile = $this->getProfileByUserId($data['user_id']);

        if($profile) {
            // Update
            $this->db->query('UPDATE profiles SET full_name = :full_name, address = :address, phone = :phone WHERE user_id = :user_id');
        } else {
            // Insert
            $this->db->query('INSERT INTO profiles (user_id, full_name, address, phone) VALUES (:user_id, :full_name, :address, :phone)');
        }

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone', $data['phone']);
     
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

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
