<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agriculture');

define('APPROOT', dirname(dirname(__FILE__)) . '/app');
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$port = $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '';
define('URLROOT', $protocol . '://' . $_SERVER['SERVER_NAME'] . $port);
define('SITENAME', 'SAMS - Smart Agriculture Management System');

file_put_contents("php://stdout", APPROOT);