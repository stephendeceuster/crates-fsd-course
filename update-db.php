<?php
// display errors
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// load library
require_once './lib/autoload.php';

if (isset($_SESSION['message']) && $_SESSION['message']) {
    printf('<b>%s</b>', $_SESSION['message']);
    unset($_SESSION['message']);
}

$output = file_get_contents('templates/update-db.html');

print $output;
