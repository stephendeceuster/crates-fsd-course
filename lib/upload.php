<?php

// display errors
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );
$public_access = false;

// load library once
require_once "autoload.php";

 

if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

$_SESSION['lastest_csrf'] = "";

// get artist from form & check if already in database.
$art_naam = htmlspecialchars(ucwords(strtolower($_POST['art_naam'])),  ENT_QUOTES); // Naam moet nog verder opgekuisd worden?
$artsql = "SELECT art_id FROM artist WHERE art_naam LIKE '". $art_naam . "'";
$data = GetData($artsql);

// if data is empty -> insert artist in db
if (!$data) {
    $sql = "INSERT INTO artist (art_naam) VALUES ('" . $art_naam . "')";
    $result = ExecuteSQL( $sql );
    $art_id = GetData($artsql)[0]['art_id'];
} else {
    $art_id = $data[0]['art_id'];
}

// get data from rest of form
$albumnaam = htmlspecialchars(ucwords(strtolower($_POST['alb_naam'])),  ENT_QUOTES);
$albumyear = $_POST['alb_releaseyear'];
$albumgenre = $_POST['alb_gen_id'];

// CHECK IF ARTIST ID ALREADY HAS THIS ALBUM IN DATABASE!!
$checkAlbum = getData("SELECT * FROM album WHERE alb_naam='" . $albumnaam . "' AND alb_art_id=" . $art_id);

if (count($checkAlbum) > 0) {
  // album already in db
  $_SESSION['message'] = 'Dit album is al aanwezig in de catalogus. Indien er fouten staan, kan je deze hier aanpassen.';
  header('Location : ./../album?alb_id='. $checkAlbum[0]['alb_id'] .'&art_id=5' . $checkAlbum[0]['alb_art_id']);
  //exit();
} else {
  // album needs to be created.
  // create sql to insert formdata to database
$albsql = "INSERT INTO album (alb_naam, alb_art_id, alb_releaseyear, alb_gen_id) VALUES ('" . $albumnaam . "', " .  $art_id . ", " . $albumyear . ", " . $albumgenre . ")";
$result = ExecuteSQL( $albsql );



// get new album id
$albsql = "SELECT alb_id FROM album WHERE alb_naam='" . $albumnaam . "' AND alb_art_id=" . $art_id;
$alb_id = GetData($albsql)[0]['alb_id'];


// get data from rest of form
//$albumnaam = $_POST['alb_naam'];


if (isset($_FILES['alb_img']) && $_FILES['alb_img']['error'] === UPLOAD_ERR_OK)
  {
    // get details of the uploaded file
    $fileTmpPath = $_FILES['alb_img']['tmp_name'];
    $fileName = $_FILES['alb_img']['name'];
    $fileSize = $_FILES['alb_img']['size'];
    $fileType = $_FILES['alb_img']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // sanitize file-name
    $newFileName = $alb_id . '-' . str_replace(' ', '-' ,strtolower($albumnaam)) . '.' . $fileExtension;

    // check if file has one of the following extensions
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

    if (in_array($fileExtension, $allowedfileExtensions))
    {
      // directory in which the uploaded file will be moved
      $uploadFileDir = './../assets/uploads/';
      $dest_path = $uploadFileDir . $newFileName;

      if(move_uploaded_file($fileTmpPath, $dest_path)) 
      {
        $imgsql = "UPDATE album SET alb_img = '" . $newFileName . "' WHERE alb_id = " . $alb_id;
        ExecuteSQL($imgsql);
        // check if Execute was succesfull en change message
        $_SESSION['message'] ='File is successfully uploaded.';
        
      }
      else 
      {
        $_SESSION['error'] = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
      }
    }
    else
    {
      $_SESSION['error'] = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
  }
  else
  {
    $_SESSION['error'] = 'There is some error in the file upload. Please check the following error.<br>';
    $_SESSION['error'] .= 'Error:' . $_FILES['alb_img']['error'];
  }

$_SESSION['post'] = $_POST;


// SEND USER TO NEW ALBUM PAGE ?
$sendTo = "Location: ./../album.php?alb_id=" . $alb_id . "&art_id=" . $art_id;
header($sendTo);
}
}