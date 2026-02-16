<?php
// Set working directory to public so that ../app paths work correctly
chdir(__DIR__ . '/public');

// Mock Session
session_start();
$_SESSION['user_id'] = 1; // Assuming 1 is an expert
$_SESSION['user_role'] = 'expert';
$_SESSION['user_username'] = 'expert1';

// Adjust paths for require, since we are effectively in /public now
require_once '../config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

// Load Controller
require_once '../app/controllers/Expert.php';

// Instantiate
$expert = new Expert();

// Mock the view method validation - wait, View method also does require_once '../app/views/'
// Capture output
ob_start();
try {
    $expert->dashboard();
    $output = ob_get_clean();
    echo "Dashboard loaded successfully.\n";
    // Check key content, e.g. "Expert Dashboard" or "Welcome"
    if (strpos($output, 'Expert Dashboard') !== false) {
        echo "Contains 'Expert Dashboard'\n";
    } else {
        echo "WARNING: Does not contain 'Expert Dashboard'. Output sample: " . substr($output, 0, 100) . "...\n";
    }
} catch (Exception $e) {
    ob_end_clean();
    echo "Error loading dashboard: " . $e->getMessage() . "\n";
}
