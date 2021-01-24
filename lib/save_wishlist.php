<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once "autoload.php";

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

$query = "INSERT INTO user_album ";
$query .= "(inh_use_id, inh_alb_id, inh_lis_id) ";
$query .= "VALUES (" . $_SESSION['user']['use_id'] . ", " . $_POST["inh_alb_id"] . "," . $_POST["inh_lis_id"] . ") ";

$result = ExecuteSQL($query);

header("Location: ../album.php?alb_id=" . $_POST["inh_alb_id"]);