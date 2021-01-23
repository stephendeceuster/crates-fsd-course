<?php
require_once "autoload.php";

function CreateConnection()
{
    global $conn;
    global $servername, $dbname, $username, $password;

    // Create and check connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function GetData( $sql )
{
    global $conn;

    CreateConnection();

    //define and execute query
    $result = $conn->query( $sql );

    //show result (if there is any)
    if ( $result->rowCount() > 0 )
    {
        $rows = $result->fetchAll(PDO::FETCH_BOTH);
        return $rows;
    }
    else
    {
        return [];
    }

}

function ExecuteSQL( $sql )
{
    global $conn;

    CreateConnection();

    //define and execute query
    $result = $conn->query( $sql );

    return $result;
}

function ImageUpload($alb_id, $albumnaam) {
    if (isset($_FILES['alb_img']) && $_FILES['alb_img']['error'] === UPLOAD_ERR_OK) {
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
  
      if (in_array($fileExtension, $allowedfileExtensions)) {
        // directory in which the uploaded file will be moved
        $uploadFileDir = './../assets/uploads/';
        $dest_path = $uploadFileDir . $newFileName;
  
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
          $imgsql = "UPDATE album SET alb_img = '" . $newFileName . "' WHERE alb_id = " . $alb_id;
          ExecuteSQL($imgsql);
          // check if Execute was succesfull en change message
          $_SESSION['message'][0] ='De foto is toegevoegd';    
        } else {
          $_SESSION['errors']['alb_img'] = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        }
      } else {
        $_SESSION['errors']['alb_img'] = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
      }
    } else {
      $_SESSION['errors']['alb_img'] = 'Er was een error met het uploaden van de foto.<br>';
      $_SESSION['errors']['alb_img'] .= 'Error:' . $_FILES['alb_img']['error'];
    }
  }
