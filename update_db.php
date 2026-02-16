<?php
// Database configuration
$host = 'localhost';
$dbname = 'agriculture';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create expert_ratings table
    $sql = "CREATE TABLE IF NOT EXISTS expert_ratings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        expert_id INT NOT NULL,
        farmer_id INT NOT NULL,
        rating DECIMAL(2, 1) NOT NULL,
        feedback TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (expert_id) REFERENCES users(id),
        FOREIGN KEY (farmer_id) REFERENCES users(id)
    )";
    $pdo->exec($sql);
    echo "Table expert_ratings created or already exists.<br>";

    // Create advisories table
    $sql = "CREATE TABLE IF NOT EXISTS advisories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        expert_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (expert_id) REFERENCES users(id)
    )";
    $pdo->exec($sql);
    echo "Table advisories created or already exists.<br>";
    
    // Create messages table
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT NOT NULL,
        read_status BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id)
    )";
    $pdo->exec($sql);
    echo "Table messages created or already exists.<br>";

    // Check if expert user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => 'expert']);
    $user = $stmt->fetch();

    if (!$user) {
        // Create expert user
        $password = password_hash('password', PASSWORD_DEFAULT);
        $role = 'expert';
        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => 'expert', 'password' => $password, 'role' => $role]);
        echo "Expert user created (username: expert, password: password).<br>";
        
        // Get the new expert ID
        $expertId = $pdo->lastInsertId();
        
        // Create profile for expert
        $sql = "INSERT INTO profiles (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $expertId, 'full_name' => 'Dr. Green', 'phone' => '1234567890']);
        echo "Expert profile created.<br>";
        
    } else {
        echo "Expert user already exists.<br>";
    }
    
    // Check if farmer user exists (for testing ratings/messages)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => 'farmer']);
    $user = $stmt->fetch();

    if (!$user) {
        // Create farmer user
        $password = password_hash('password', PASSWORD_DEFAULT); 
        $role = 'farmer';
        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => 'farmer', 'password' => $password, 'role' => $role]);
        echo "Farmer user created (username: farmer, password: password).<br>";
        
        // Get the new farmer ID
        $farmerId = $pdo->lastInsertId();
        
        // Create profile for farmer
        $sql = "INSERT INTO profiles (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $farmerId, 'full_name' => 'John Farmer', 'phone' => '0987654321']);
        echo "Farmer profile created.<br>";
        
    } else {
        echo "Farmer user already exists.<br>";
    }

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>
