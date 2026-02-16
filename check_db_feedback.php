<?php
// Use full paths or correct relative paths
define('APPROOT', dirname(__FILE__) . '/app');
require_once 'app/config/config.php';
require_once 'app/libraries/Database.php';

// Instantiate DB
$db = new Database();

// Check 'products' table
echo "Table: products\n";
$db->query('DESCRIBE products');
$results = $db->resultSet();
foreach ($results as $column) {
    echo $column->Field . " (" . $column->Type . ")\n";
}

// Check 'feedback' table
$db->query("SHOW TABLES LIKE 'feedback'");
$results = $db->resultSet(); // Use resultSet for show tables, though single() is better, let's stick to consistent pattern or just check if meaningful

if (count($results) > 0) {
    echo "\nTable 'feedback' exists.\n";
    $db->query('DESCRIBE feedback');
    $cols = $db->resultSet();
    foreach ($cols as $column) {
        echo $column->Field . " (" . $column->Type . ")\n";
    }
} else {
    echo "\nTable 'feedback' does not exist. Creating...\n";
    $sql = "CREATE TABLE feedback (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )";
    $db->query($sql);
    if($db->execute()){
         echo "Table 'feedback' created successfully.\n";
    } else {
         echo "Failed to create 'feedback' table.\n";
    }
}
