<?php
require_once 'config/config.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = 'expert1';
$password = password_hash('password123', PASSWORD_DEFAULT);
$role = 'expert';

// Check if user exists
$checkSql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($checkSql);

if ($result->num_rows > 0) {
    echo "User '$username' already exists.\n";
} else {
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "User '$username' created successfully with password 'password123'.\n";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "\n";
    }
}

$conn->close();
?>
