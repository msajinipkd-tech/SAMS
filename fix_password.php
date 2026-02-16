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

    $username = 'expert';
    $password = 'password';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update password for expert
    $sql = "UPDATE users SET password = :password WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $hashed_password, 'username' => $username]);

    if ($stmt->rowCount() > 0) {
        echo "Password for user '$username' updated successfully.<br>";
    } else {
        echo "User '$username' found but password was already correct or could not be updated.<br>";
    }

    // Verify
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        echo "VERIFICATION SUCCESS: Login with '$username' / '$password' should work now.<br>";
    } else {
        echo "VERIFICATION FAILED: Something is still wrong.<br>";
    }

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
