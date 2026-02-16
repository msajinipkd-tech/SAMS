<?php
// Define APPROOT manually to match config based on file location
define('APPROOT', dirname(__FILE__) . '/app');
// Redefine URLROOT if config puts it out, or just require config carefully
// Actually, config.php uses dirname(dirname(__FILE__)) so it expects to be in app/config
// Let's just bypass config and connect directly if possible, OR just set the constants needed by Database.php
// But Database.php uses DB_HOST etc from config.

require_once 'app/config/config.php';
require_once 'app/libraries/Database.php';

$db = new Database();

echo "<h1>Products Table</h1>";
$db->query('DESCRIBE products');
$results = $db->resultSet();
echo "<pre>";
foreach ($results as $column) {
    echo $column->Field . " (" . $column->Type . ")\n";
}
echo "</pre>";

echo "<h1>Feedback Table</h1>";
$db->query("SHOW TABLES LIKE 'feedback'");
$exists = $db->single();

if ($exists) {
    echo "Table 'feedback' ALREADY EXISTS.<br>";
    $db->query('DESCRIBE feedback');
    $cols = $db->resultSet();
    echo "<pre>";
    foreach ($cols as $column) {
        echo $column->Field . " (" . $column->Type . ")\n";
    }
    echo "</pre>";
} else {
    echo "Table 'feedback' DOES NOT EXIST. Creating...<br>";
    $sql = "CREATE TABLE feedback (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )";
    $db->query($sql);
    if($db->execute()){
         echo "Table 'feedback' created successfully.<br>";
    } else {
         echo "Failed to create 'feedback' table.<br>";
    }
}
