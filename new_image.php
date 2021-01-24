<?php
// display errors
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

// load library
require_once './lib/autoload.php';


// MODEL : get data
$title = 'Verander albumfoto';

$CSRF = GenerateCSRF("new_image.php");

$sql = "SELECT * FROM album LEFT JOIN artist ON alb_art_id = art_id WHERE alb_id = " . $_GET['alb_id'];;
$album = GetData($sql);
$sending_form_uri = $_SERVER['HTTP_REFERER'];
$alb_naam = $album[0]['alb_naam'];
$art_naam = $album[0]['art_naam'];


// VIEW : get update template
$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/new_image.html');
$html .= file_get_contents('./templates/footer.html');


// CONTROLLER : merge & print html
$html = str_replace("%title%", $title, $html);
$html = str_replace("%alb_naam%", $alb_naam, $html);
$html = str_replace("%art_naam%", $art_naam, $html);
$html = str_replace('%alb_id%', $_GET['alb_id'], $html);
$html = str_replace('%csrf_token%', $CSRF, $html);
$html = str_replace('%sending_form_uri%', $sending_form_uri, $html);


echo $html;

