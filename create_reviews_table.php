<?php
// Standalone script
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

    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

$db = new Database();

echo "Checking reviews table...\n";
$db->query("SHOW TABLES LIKE 'reviews'");
$results = $db->resultSet();

if (count($results) > 0) {
    echo "Table 'reviews' exists.\n";
} else {
    echo "Table 'reviews' does not exist. Creating...\n";
    $sql = "CREATE TABLE reviews (
        id INT(11) NOT NULL AUTO_INCREMENT,
        product_id INT(11) NOT NULL,
        user_id INT(11) NOT NULL,
        rating INT(11) NOT NULL,
        review TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )";
    $db->query($sql);
    if($db->execute()){
         echo "Table 'reviews' created successfully.\n";
    } else {
         echo "Failed to create 'reviews' table.\n";
    }
}
