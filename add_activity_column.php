<?php
// Define config manually to match app/config/config.php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'farmer');
define('DB_PASS', 'bsccs2026');
define('DB_NAME', 'agriculture');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Add last_activity column
    $sql = "ALTER TABLE users ADD COLUMN last_activity DATETIME NULL DEFAULT NULL";
    
    try {
        $pdo->exec($sql);
        echo "Column 'last_activity' added successfully to 'users' table.<br>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') { // Duplicate column name
            echo "Column 'last_activity' already exists.<br>";
        } else {
            throw $e;
        }
    }

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
