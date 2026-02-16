<?php
$host = '127.0.0.1';
$port = '3306';
$db   = 'agriculture';
$user = 'farmer';
$pass = 'bsccs2026';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected successfully to Port 3306!";
     
     // Check table
     $stmt = $pdo->query("SHOW TABLES LIKE 'expert_ratings'");
     $table = $stmt->fetch();
     if($table){
         echo "\nTable expert_ratings exists.";
     } else {
         echo "\nTable expert_ratings MISSING.";
     }

} catch (\PDOException $e) {
     echo "Connection failed: " . $e->getMessage();
}
?>
