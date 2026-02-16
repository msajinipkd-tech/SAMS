<?php
// Load config
require_once 'config/config.php';
// Load libraries
require_once 'app/core/Database.php';

$db = new Database;
$db->query('SELECT * FROM users');
$users = $db->resultSet();

echo "ID | Username | Role\n";
echo "---|---|---\n";
foreach($users as $user){
    echo $user->id . " | " . $user->username . " | " . $user->role . "\n";
}
