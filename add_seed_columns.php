<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriculture';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Array of columns to add
$columns = [
    "ADD COLUMN expiry_date DATE NULL",
    "ADD COLUMN batch_number VARCHAR(50) NULL",
    "ADD COLUMN usage_instructions TEXT NULL",
    "ADD COLUMN weight VARCHAR(50) NULL"
];

foreach ($columns as $col) {
    $sql = "ALTER TABLE products $col";
    if ($conn->query($sql) === TRUE) {
        echo "Column added successfully: $col\n";
    } else {
        echo "Error adding column: " . $conn->error . "\n";
    }
}

$conn->close();
?>
