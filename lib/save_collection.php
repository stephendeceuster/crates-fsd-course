<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "autoload.php";

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

// get use_id
$sql = "select use_id from user ";
$sql .= "where use_email = '" . $_SESSION['user']['use_email'] . "'";
// use_id
$id = GetData($sql);

$queryLisID = "SELECT inh_lis_id FROM user_album ";
$queryLisID .= "WHERE inh_alb_id = " . $_POST["inh_alb_id"];
$queryLisID .= " AND inh_use_id = " . $id[0]['use_id'];
$result = GetData($queryLisID);

if (empty($result)){
$query = "INSERT INTO user_album ";
$query .= "(inh_use_id, inh_alb_id, inh_lis_id) ";
$query .= "VALUES (" . $id[0]['use_id'] . ", " . $_POST["inh_alb_id"] . "," . $_POST["inh_lis_id"] . ") ";
} else {
    $query = "UPDATE user_album SET ";
    $query .= "inh_use_id='". $id[0]['use_id'] . "',";
    $query .= "inh_alb_id='". $_POST["inh_alb_id"]."',";
    $query .= "inh_lis_id='". $_POST["inh_lis_id"]."' ";
    $query .= "WHERE inh_alb_id = " . $_POST ["inh_alb_id"];
    $query .= " AND inh_use_id = " . $id[0]['use_id'];
}

$result = ExecuteSQL($query);

header("Location: ../album.php?alb_id=" . $_POST["inh_alb_id"] . "&art_id=" . $_POST["art_id"]);