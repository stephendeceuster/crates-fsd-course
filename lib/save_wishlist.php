<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "autoload.php";

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

$sql = "select use_id from user ";
$sql .= "where use_email = '" . $_SESSION['user']['use_email'] . "'";

// use_id
$id = GetData($sql);

$query = "INSERT INTO user_album ";
$query .= "(inh_use_id, inh_alb_id, inh_lis_id) ";
$query .= "VALUES (" . $id[0]['use_id'] . ", " . $_POST["inh_alb_id"] . "," . $_POST["inh_lis_id"] . ") ";

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