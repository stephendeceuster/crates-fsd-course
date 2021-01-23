<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

require_once "autoload.php";

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

$sql = "DELETE FROM user_album ";
$sql .= "WHERE inh_use_id = " . $_SESSION['user']['use_id'];
$sql .= " AND inh_alb_id = " . $_POST['inh_alb_id'];

$result = ExecuteSQL($sql);

header("Location: ../album.php?alb_id=" . $_POST["inh_alb_id"] . "&art_id=" . $_POST["art_id"]);