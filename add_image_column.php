<?php
require_once 'config/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'image'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $pdo->exec("ALTER TABLE products ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER description");
        echo "Column 'image' added successfully.\n";
    } else {
        echo "Column 'image' already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
