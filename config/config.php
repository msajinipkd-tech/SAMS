<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'farmer');
define('DB_PASS', 'bsccs2026');
define('DB_NAME', 'agriculture');

define('APPROOT', dirname(dirname(__FILE__)) . '/app');
define('URLROOT', 'http://localhost:8080');
define('SITENAME', 'SAMS - Smart Agriculture Management System');

file_put_contents("php://stdout", APPROOT);