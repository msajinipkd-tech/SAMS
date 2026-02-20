<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Login User
    public function login($username, $password)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Find user by username
    public function findUserByUsername($username)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get all users
    public function getUsers()
    {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }

    // Add User (for admin interface)
    public function addUser($data)
    {
        $this->db->query('INSERT INTO users (username, password, role) VALUES(:username, :password, :role)');

        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update User
    public function updateUser($data)
    {
        $this->db->query('UPDATE users SET username = :username, role = :role WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':role', $data['role']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete User
    // Delete User
    public function deleteUser($id)
    {
        // Delete related data first to avoid Foreign Key Violations
        
        // 1. Delete Profile
        $this->db->query('DELETE FROM profiles WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 2. Delete Advisories (if expert)
        $this->db->query('DELETE FROM advisories WHERE expert_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 3. Delete Ratings (as expert or farmer)
        $this->db->query('DELETE FROM expert_ratings WHERE expert_id = :id OR farmer_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 4. Delete Messages (sent or received)
        $this->db->query('DELETE FROM messages WHERE sender_id = :id OR receiver_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 5. Delete Expert Requests
        $this->db->query('DELETE FROM expert_requests WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 6. Delete Orders
        $this->db->query('DELETE FROM orders WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 7. Delete Activities
        $this->db->query('DELETE FROM activities WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Finally Delete User
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Utility to get user by ID
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();
        return $row;
    }

    // Change Password
    public function changePassword($id, $newPassword)
    {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // Get users by role
    public function getUsersByRole($role)
    {
        $this->db->query('SELECT * FROM users WHERE role = :role');
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    // Update last activity
    public function updateActivity($id) {
        $this->db->query('UPDATE users SET last_activity = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    // Check if user is online (active in last 5 minutes)
    public function isOnline($id) {
        $this->db->query('SELECT last_activity FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if($row && $row->last_activity) {
            $last_activity = strtotime($row->last_activity);
            $current_time = time();
            $time_diff = $current_time - $last_activity;
            
            // 5 minutes = 300 seconds
            if($time_diff <= 300) {
                return true;
            }
        }
        return false;
    }
}
