<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

require_once "autoload.php";
//var_dump($_POST);

//controle CSRF token
if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

$_SESSION['lastest_csrf'] = "";

// Haal de vorige nummers weg
$sql = "DELETE FROM songs WHERE son_alb_id = " . $_GET['alb_id'];

$result = ExecuteSQL( $sql );

// voeg de nieuwe nummers toe
    foreach ( $_POST as $field => $value ){
        if ( in_array( $field, [ 'csrf' ] ) ) continue; // csrf uit post overslaan

        if (empty($value)) continue; // als een veld leeggelaten wordt, ga verder

            $sql = "insert into songs (son_alb_id, son_title) ";
            $sql .= "values (" . $_GET['alb_id'] . ", '$value'); ";
            $result = ExecuteSQL( $sql );
            //print $sql;
        }

// Boodschap meegeven
$_SESSION['message'][0] = "Nummers aangepast!";

header("Location: ../album.php?alb_id=" . $_GET["alb_id"]);




