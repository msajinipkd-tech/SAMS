<?php
// require_once 'config/config.php';
// Database class likely uses constants, so we define them here for the script
define('DB_HOST', '127.0.0.1'); 
define('DB_USER', 'root'); // Try root (default XAMPP)
define('DB_PASS', ''); // Default XAMPP root password is empty
define('DB_NAME', 'agriculture');

require_once 'app/core/Database.php';

class DatabaseUpdater {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addCategoryColumn() {
        echo "Checking if 'category' column exists in 'products' table...\n";
        
        // check if column exists
        $this->db->query("SHOW COLUMNS FROM products LIKE 'category'");
        $result = $this->db->single();

        if ($result) {
            echo "Column 'category' already exists.\n";
        } else {
            echo "Adding 'category' column...\n";
            $this->db->query("ALTER TABLE products ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT 'General' AFTER name");
            if ($this->db->execute()) {
                echo "Column 'category' added successfully.\n";
            } else {
                echo "Failed to add column 'category'.\n";
            }
        }
    }
}

$updater = new DatabaseUpdater();
$updater->addCategoryColumn();
