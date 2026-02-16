<?php
// Handle static files for PHP built-in server
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
}

session_start();

require_once '../config/config.php';
require_once '../app/helpers/session_helper.php';
// require_once '../app/helpers/url_helper.php'; // Commented out to avoid re-declare if I move it

require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

// Init Core App
$init = new App();
