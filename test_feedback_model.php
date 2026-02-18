<?php
// Define config constants manually to avoid dependency issues in CLI
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agriculture');

// Mock Database class
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e){
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }
    public function query($sql){ $this->stmt = $this->dbh->prepare($sql); }
    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value): $type = PDO::PARAM_INT; break;
                case is_bool($value): $type = PDO::PARAM_BOOL; break;
                case is_null($value): $type = PDO::PARAM_NULL; break;
                default: $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    public function execute(){ return $this->stmt->execute(); }
    public function resultSet(){ $this->execute(); return $this->stmt->fetchAll(PDO::FETCH_OBJ); }
}

// Include Feedback Model (but we need to modify it or mock it because it extends Model/Database or uses 'new Database')
// The Feedback model uses 'new Database', so if we include the class definition above, it should work if we copy the code or include the file.
// Since Feedback.php doesn't have 'namespace' or complex imports, I can just copy the method to test or include it if I mock Database first.
// But Feedback.php might have 'require' statements or extend something.
// Let's just define the class Feedback here to match the logic exactly.

class FeedbackTest {
    private $db;
    public function __construct() { $this->db = new Database; }
    
    public function addFeedback($data) {
        $this->db->query('INSERT INTO feedback (user_id, message) VALUES(:user_id, :message)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);
        if ($this->db->execute()) { return true; } else { return false; }
    }
}

// Test Insertion
echo "Testing Feedback Insertion...\n";
// We need a valid user_id. Let's pick 1, assuming a user exists.
$data = [
    'user_id' => 1, 
    'message' => 'Test feedback from CLI script ' . date('H:i:s')
];

$feedback = new FeedbackTest();
if($feedback->addFeedback($data)) {
    echo "Feedback inserted successfully!\n";
} else {
    echo "Feedback insertion FAILED.\n";
}

// Test Retrieval
echo "Testing Feedback Retrieval...\n";
// We need to implement getFeedbacks similar to the model
// Note: The model joins with 'users'. If user 1 doesn't exist, the join might result in empty rows if it's INNER JOIN.
// Let's check users table too.

$db = new Database();
$db->query("SELECT id FROM users LIMIT 1");
$user = $db->resultSet();
if(count($user) > 0) {
    echo "Found user with ID: " . $user[0]->id . "\n";
    $data['user_id'] = $user[0]->id;
    // Retry insertion with valid ID if 1 was invalid
    if($user[0]->id != 1) {
       $feedback->addFeedback($data);
    }
} else {
    echo "No users found in database. Insertion might fail foreign key constraints or join retrieval.\n";
}

// Manual Query to check
$db->query('SELECT * FROM feedback ORDER BY id DESC LIMIT 1');
$res = $db->resultSet();
print_r($res);
