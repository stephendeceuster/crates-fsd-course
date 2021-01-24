<?php

// display errors
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );
$public_access = false;

// load library once
require_once "autoload.php";

$sending_form_uri = $_SERVER['HTTP_REFERER'];

if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {

    // check if albumnaam en artiestnaam zijn ingevuld
    if (empty($_POST["alb_naam"])) {
        $msg = "Geef aub een albumnaam in.";
        $_SESSION['errors']["alb_naam_error"] = $msg;
    }
    if (empty($_POST["art_naam"])) {
        $msg = "Geef aub een artiestnaam in.";
        $_SESSION['errors']["art_naam_error"] = $msg;
    }

    if (isset($_SESSION['errors']) and count($_SESSION['errors']) > 0) {
        $_SESSION['old_post'] = $_POST;
        header("Location: " . $sending_form_uri);
        exit();
    }

    //controle CSRF token
    if (!key_exists("csrf", $_POST)) die("Missing CSRF");
    if (!hash_equals($_POST['csrf'], $_SESSION['lastest_csrf'])) die("Problem with CSRF");

    $_SESSION['lastest_csrf'] = "";

    // get artist from form & check if already in database.
    $art_naam = htmlspecialchars(ucwords(strtolower($_POST['art_naam'])), ENT_QUOTES); // Naam moet nog verder opgekuisd worden?
    $artsql = "SELECT art_id FROM artist WHERE art_naam LIKE '" . $art_naam . "'";
    $data = GetData($artsql);

    // if data is empty -> insert artist in db
    if (!$data) {
        $sql = "INSERT INTO artist (art_naam) VALUES ('" . $art_naam . "')";
        $result = ExecuteSQL($sql);
        $art_id = GetData($artsql)[0]['art_id'];
    } else {
        $art_id = $data[0]['art_id'];
    }

    // get data from rest of form
    $albumnaam = htmlspecialchars(ucwords(strtolower($_POST['alb_naam'])), ENT_QUOTES);
    $albumyear = $_POST['alb_releaseyear'];
    $albumgenre = $_POST['alb_gen_id'];

    // execute query

    $resultsql = "update album ";
    $resultsql .= "set alb_art_id = '" . $art_id . "', alb_gen_id = '" . $albumgenre . "', alb_naam = '" . $albumnaam;
    $resultsql .= "', alb_releaseyear = '" . $albumyear . "' ";
    $resultsql .= "where alb_id = " . $_POST['alb_id'];

    $result = ExecuteSQL( $resultsql );
}
header("Location: ../album.php?alb_id=" . $_POST["alb_id"]);
