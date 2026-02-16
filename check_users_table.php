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

    public function __construct(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $this->dbh = new PDO($dsn, $this->user, $this->pass);
    }
    public function query($sql){ return $this->dbh->query($sql); }
}

$db = new Database();
$stmt = $db->query("DESCRIBE users");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($results);
