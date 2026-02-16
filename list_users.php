<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriculture';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT username, role, password FROM users WHERE role = 'buyer'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " buyer(s):\n";
    while($row = $result->fetch_assoc()) {
        echo "Username: " . $row["username"] . " | Role: " . $row["role"] . "\n";
    }
} else {
    echo "No buyer accounts found.\n";
}

$conn->close();
