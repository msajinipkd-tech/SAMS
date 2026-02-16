<?php
// Recreate Farmer User
// Connect as root (default XAMPP has no password for root)
$host = '127.0.0.1';
$port = '3307';
$user = 'root';
$pass = '';

echo "<h3>Recreating Farmer User...</h3>";

try {
    $dsn = "mysql:host=$host;port=$port";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected as root.<br>";
    
    // Create User
    // Note: In MariaDB/MySQL, CREATE USER IF NOT EXISTS is standard.
    // We also need to FLUSH PRIVILEGES.
    
    $app_user = 'farmer';
    $app_pass = 'bsccs2026'; // From config.php
    
    // Check if user exists
    $stmt = $pdo->query("SELECT User FROM mysql.user WHERE User = '$app_user'");
    if ($stmt->rowCount() == 0) {
        $sql = "CREATE USER '$app_user'@'localhost' IDENTIFIED BY '$app_pass';";
        $pdo->exec($sql);
        echo "User '$app_user'@'localhost' created.<br>";
        
        // Also for 127.0.0.1 for safety
        $sql = "CREATE USER '$app_user'@'127.0.0.1' IDENTIFIED BY '$app_pass';";
        $pdo->exec($sql);
        echo "User '$app_user'@'127.0.0.1' created.<br>";
    } else {
        echo "User '$app_user' already exists. Updating password just in case.<br>";
        $sql = "ALTER USER '$app_user'@'localhost' IDENTIFIED BY '$app_pass';";
        $pdo->exec($sql);
         $sql = "ALTER USER '$app_user'@'127.0.0.1' IDENTIFIED BY '$app_pass';";
        $pdo->exec($sql);
    }
    
    // Grant Privileges
    $sql = "GRANT ALL PRIVILEGES ON *.* TO '$app_user'@'localhost' WITH GRANT OPTION;";
    $pdo->exec($sql);
     $sql = "GRANT ALL PRIVILEGES ON *.* TO '$app_user'@'127.0.0.1' WITH GRANT OPTION;";
    $pdo->exec($sql);
    echo "Privileges granted.<br>";
    
    $pdo->exec("FLUSH PRIVILEGES;");
    echo "Privileges flushed.<br>";
    
    echo "<h3 style='color:green'>Success!</h3>";
    
} catch (PDOException $e) {
    echo "<h3 style='color:red'>Failed: " . $e->getMessage() . "</h3>";
}
