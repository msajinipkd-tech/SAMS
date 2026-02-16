<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriculture';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to MySQL server.\n";

if (!$conn->select_db($dbname)) {
    echo "Database '$dbname' does not exist.\n";
} else {
    echo "Database '$dbname' exists.\n";
}
$conn->close();
