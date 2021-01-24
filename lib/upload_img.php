<?php
// display errors
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );
$public_access = false;

// load library once
require_once "autoload.php";

$sending_form_uri = $_SERVER['HTTP_REFERER'];

if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {

  //controle CSRF token
  if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
  if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

  $_SESSION['lastest_csrf'] = "";

  // get data from form
  $alb_id = $_POST['alb_id'];
  $albumnaam = htmlspecialchars(ucwords(strtolower($_POST['alb_naam'])),  ENT_QUOTES);


  // upload image to db
  ImageUpload($alb_id, $albumnaam);

  // SEND USER TO NEW ALBUM PAGE 
  $_SESSION['message'][0] = 'De foto van is vervangen';
  $sendTo = "Location: ./../album.php?alb_id=" . $alb_id;
  header($sendTo);
}


