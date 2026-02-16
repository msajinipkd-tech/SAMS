<?php
// Load Config
require_once __DIR__ . '/../config/config.php';
// Load Database
require_once __DIR__ . '/../app/core/Database.php';

echo "<h2>Database Diagnostic</h2>";

try {
    $db = new Database();
    echo "<p>Database connection successful.</p>";
} catch (Exception $e) {
    die("<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>");
}

// Check if expert_ratings table exists
echo "<h3>Checking tables...</h3>";
try {
    $db->query("SHOW TABLES LIKE 'expert_ratings'");
    $result = $db->resultSet();
    if(count($result) > 0){
        echo "<p style='color:green'>Table 'expert_ratings' exists.</p>";
    } else {
        echo "<p style='color:red'>Table 'expert_ratings' DOES NOT exist.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error checking table: " . $e->getMessage() . "</p>";
}

// Check for Experts
echo "<h3>Checking Experts...</h3>";
try {
    $db->query("SELECT * FROM users WHERE role = 'expert'");
    $experts = $db->resultSet();
    echo "<p>Found " . count($experts) . " experts.</p>";
    foreach($experts as $expert){
        echo "ID: " . $expert->id . " | Name: " . $expert->name . " | Email: " . $expert->email . "<br>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error fetching experts: " . $e->getMessage() . "</p>";
}

// Try the query from Expert Controller
if(count($experts) > 0){
    $expertId = $experts[0]->id;
    echo "<h3>Testing Dashboard Query for Expert ID: $expertId</h3>";
    try {
        $db->query('SELECT r.*, u.username as farmer_name FROM expert_ratings r JOIN users u ON r.farmer_id = u.id WHERE r.expert_id = :expert_id ORDER BY r.created_at DESC');
        $db->bind(':expert_id', $expertId);
        $ratings = $db->resultSet();
        echo "<p style='color:green'>Query successful. Found " . count($ratings) . " ratings.</p>";
    } catch (Exception $e) {
        echo "<p style='color:red'>Query FAILED: " . $e->getMessage() . "</p>";
    }
}
?>
