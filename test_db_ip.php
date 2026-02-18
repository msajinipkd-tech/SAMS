<?php
$conn = new mysqli('127.0.0.1', 'farmer', 'bsccs2026', 'agriculture', 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to 127.0.0.1\n";
$conn->close();
