<?php
// Define config manually to match app/config/config.php
define('DB_HOST', '127.0.0.1'); // Removed ;port=3306 for PDO standard compatibility if needed, but keeping simple
define('DB_USER', 'farmer');
define('DB_PASS', 'bsccs2026');
define('DB_NAME', 'agriculture');

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create PDO instance
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            echo "Connected to database successfully.<br>";
        } catch(PDOException $e){
            $this->error = $e->getMessage();
            echo "Connection Error: " . $this->error . "<br>";
        }
    }

    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
}

$db = new Database();

$username = 'expert';
$password = 'password'; 

echo "Attempting to login as User: $username<br>";

// 1. Check if user exists
$db->query('SELECT * FROM users WHERE username = :username');
$db->bind(':username', $username);
$row = $db->single();

if($row){
    echo "User found: " . $row->username . " (ID: " . $row->id . ", Role: " . $row->role . ")<br>";
    echo "Stored Hash: " . $row->password . "<br>";
    
    // 2. Verify password
    if(password_verify($password, $row->password)){
        echo "Password verification: SUCCESS<br>";
    } else {
        echo "Password verification: FAILED<br>";
        echo "Hash of input '$password': " . password_hash($password, PASSWORD_DEFAULT) . "<br>";
    }
} else {
    echo "User '$username' NOT FOUND in database.<br>";
}

// 3. Check for recent users
echo "<br>Listing all users:<br>";
$db->query('SELECT * FROM users');
$db->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$stmt = $db->dbh->query('SELECT * FROM users');
while($user = $stmt->fetch()){
    echo "ID: " . $user['id'] . " | Name: " . $user['username'] . " | Role: " . $user['role'] . "<br>";
}
