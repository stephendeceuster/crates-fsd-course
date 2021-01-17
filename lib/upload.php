<?php

// load library once
require_once "autoload.php";


$message = ''; 

uploadAlbum();

function uploadAlbum() {
  if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
//check if users comes from update-db form
if (!isset($_POST['uploadBtn']) && $_POST['uploadBtn'] != 'Maak album aan') { 
    // if not from form -> stop & send to other page.
}

// get artist from form & check if already in database.
$art_naam = ucwords(strtolower($_POST['art_naam'])); // Naam moet nog verder opgekuisd worden
$artsql = "SELECT art_id FROM artist WHERE art_naam LIKE '". $art_naam . "'";
$data = GetData($artsql);
var_dump($data);

// if data is empty -> insert artist in db
if (!$data) {
    $sql = "INSERT INTO artist (art_naam) VALUES ('" . $art_naam . "')";
    $result = ExecuteSQL( $sql );
    $art_id = ExecuteSQL( 'SELECT LAST_INSERT_ID()')[0]['art_id'];
    var_dump($art_id);
} else {
    $art_id = $data[0]['art_id'];
    //print 'Artist with id = ' . $data[0]["art_id"] . ' is in db';
}

<<<<<<< Updated upstream
=======
// get data from rest of form
$albumnaam = htmlspecialchars(ucwords(strtolower($_POST['alb_naam'])),  ENT_QUOTES);
$albumyear = $_POST['alb_year']

// create sql 
$albsql = 'INSERT INTO album (alb_naam, alb_art_id) VALUES ';
$artsql .= $albumnaam . ', ';
$artsql .= $art_id . ')'; 

$result = ExecuteSQL( $albsql );
$alb_id = ExecuteSQL( 'SELECT LAST_INSERT_ID()')[0]['a_id'];

>>>>>>> Stashed changes

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
    //$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
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
        $message ='File is successfully uploaded.';
        ExecuteSQL('INSERT INTO album (alb_img) VALUES ' . $newFileName);
      }
      else 
      {
        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
      }
    }
    else
    {
      $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
  }
  else
  {
    $message = 'There is some error in the file upload. Please check the following error.<br>';
    $message .= 'Error:' . $_FILES['alb_img']['error'];
  }

$_SESSION['message'] = $message;
$_SESSION['post'] = $_POST;

print $message;

// SEND USER BACK TO FORM... ?
// header("Location: update-db.php");
}
}