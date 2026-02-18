<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agriculture');

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $stmt;

    public function __construct(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $this->dbh = new PDO($dsn, $this->user, $this->pass);
    }
    public function query($sql){ $this->stmt = $this->dbh->prepare($sql); }
    public function bind($param, $value, $type = null){ $this->stmt->bindValue($param, $value, $type ?? PDO::PARAM_STR); }
    public function execute(){ return $this->stmt->execute(); }
    public function resultSet(){ $this->execute(); return $this->stmt->fetchAll(PDO::FETCH_OBJ); }
    public function single(){ $this->execute(); return $this->stmt->fetch(PDO::FETCH_OBJ); }
}

class ReviewTest {
    private $db;
    public function __construct() { $this->db = new Database; }
    
    public function addReview($data) {
        $this->db->query('INSERT INTO reviews (product_id, user_id, rating, review) VALUES(:product_id, :user_id, :rating, :review)');
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review', $data['review']);
        return $this->db->execute();
    }
}

// Get a legitimate product ID
$db = new Database();
$db->query("SELECT id FROM products LIMIT 1");
$prod = $db->single();
if(!$prod) die("No products found to review.\n");

// Get a legitimate user ID
$db->query("SELECT id FROM users LIMIT 1");
$user = $db->single();
if(!$user) die("No users found.\n");

$data = [
    'product_id' => $prod->id,
    'user_id' => $user->id,
    'rating' => 5,
    'review' => 'Test Review ' . date('H:i:s')
];

$model = new ReviewTest();
if($model->addReview($data)) {
    echo "Review added successfully for Product {$prod->id} by User {$user->id}.\n";
} else {
    echo "Failed to add review.\n";
}
