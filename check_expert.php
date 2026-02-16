<?php
require_once 'config/config.php';

echo "Testing connection to " . DB_HOST . "...\n";

try {
    $dsn = 'mysql:host=' . DB_HOST . ';port=3307;dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!\n";
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'expert'");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($users) . " experts:\n";
    foreach ($users as $user) {
        echo "- " . $user['username'] . " (ID: " . $user['id'] . ")\n";
        // Verify password 'password123'
        if (password_verify('password123', $user['password'])) {
             echo "  [OK] Password 'password123' matches.\n";
        } else {
             echo "  [FAIL] Password 'password123' does not match.\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
