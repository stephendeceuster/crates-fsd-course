<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "autoload.php";

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

$queryLisID = 'SELECT inh_lis_id FROM user_album WHERE inh_alb_id = ' . $_POST['inh_alb_id'];
$result = GetData($queryLisID);


$query = "INSERT INTO user_album ";
$query .= "(inh_use_id, inh_alb_id, inh_lis_id) ";
$query .= "VALUES (" . $_POST["inh_use_id"] . ", " . $_POST["inh_alb_id"] . "," . $_POST["inh_lis_id"] . ") ";

// Create and check connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

//define and execute query
$result = $conn->query($query);

header("Location: ../album.php?alb_id=" . $_POST["inh_alb_id"] . "&art_id=" . $_POST["art_id"]);