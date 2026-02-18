<?php
// Standalone script, no dependencies
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

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
}

$db = new Database();

echo "Table: products\n";
$db->query('DESCRIBE products');
$results = $db->resultSet();
foreach ($results as $column) {
    echo $column->Field . " (" . $column->Type . ")\n";
}

echo "\nChecking feedback table...\n";
$db->query("SHOW TABLES LIKE 'feedback'");
$results = $db->resultSet();

if (count($results) > 0) {
    echo "Table 'feedback' exists.\n";
    $db->query('DESCRIBE feedback');
    $cols = $db->resultSet();
    foreach ($cols as $column) {
        echo $column->Field . " (" . $column->Type . ")\n";
    }
} else {
    echo "Table 'feedback' does not exist. Creating...\n";
    $sql = "CREATE TABLE feedback (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )";
    $db->query($sql);
    if($db->execute()){
         echo "Table 'feedback' created successfully.\n";
    } else {
         echo "Failed to create 'feedback' table.\n";
    }
}
