<?php
require_once 'config/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tables = ['users', 'advisories', 'profiles', 'expert_ratings', 'messages'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "Table '$table' exists.\n";
        $columns = $conn->query("SHOW COLUMNS FROM $table");
        echo "  Columns: ";
        while($row = $columns->fetch_assoc()) {
            echo $row['Field'] . " ";
        }
        echo "\n";
    } else {
        echo "Table '$table' does NOT exist.\n";
    }
}

$conn->close();
