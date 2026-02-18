<?php
// Bootstrap manually
define('APPROOT', dirname(__FILE__) . '/app');
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/User.php';

// Mock session if needed (though deleteUser likely doesn't need it)
if (!isset($_SESSION)) {
    session_start();
}

try {
    $userModel = new User();
    $db = new Database();

    // 1. Create a dummy user to delete
    echo "Creating dummy user 'todelete'...<br>";
    $username = 'todelete';
    $password = password_hash('password', PASSWORD_DEFAULT);
    $role = 'farmer';
    
    // Check if exists first
    $db->query("SELECT id FROM users WHERE username = :username");
    $db->bind(':username', $username);
    $existing = $db->single();
    
    if($existing){
        $userId = $existing->id;
        echo "User 'todelete' already exists (ID: $userId).<br>";
    } else {
        $db->query("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $db->bind(':username', $username);
        $db->bind(':password', $password);
        $db->bind(':role', $role);
        $db->execute();
        $db->query("SELECT id FROM users WHERE username = :username");
        $db->bind(':username', $username);
        $row = $db->single();
        $userId = $row->id;
        echo "User 'todelete' created (ID: $userId).<br>";
    }

    // 2. Create a dummy profile to trigger FK constraint
    echo "Creating dummy profile for user ID $userId...<br>";
    $db->query("INSERT INTO profiles (user_id, full_name) VALUES (:user_id, 'To Delete') ON DUPLICATE KEY UPDATE full_name='To Delete'");
    $db->bind(':user_id', $userId);
    $db->execute();
    echo "Profile created/updated.<br>";

    // 3. Attempt to delete via User model
    echo "Attempting to delete user ID $userId...<br>";
    if ($userModel->deleteUser($userId)) {
        echo "SUCCESS: User deleted successfully via User::deleteUser().<br>";
    } else {
        echo "FAILURE: User::deleteUser() returned false.<br>";
    }

    // 4. Verification Check
    $db->query("SELECT * FROM users WHERE id = :id");
    $db->bind(':id', $userId);
    $checkUser = $db->single();

    $db->query("SELECT * FROM profiles WHERE user_id = :id");
    $db->bind(':id', $userId);
    $checkProfile = $db->single();

    if(!$checkUser && !$checkProfile){
        echo "VERIFICATION PASSED: User and Profile are gone.<br>";
    } else {
        echo "VERIFICATION FAILED: ";
        if($checkUser) echo "User still exists. ";
        if($checkProfile) echo "Profile still exists. ";
        echo "<br>";
    }

} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString();
}
