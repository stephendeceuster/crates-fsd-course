<?php
// display errors
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// load library
require_once './lib/autoload.php';


// Model : get data
$title = 'Verander albumfoto';

$CSRF = GenerateCSRF("new_image.php");
$alb_id = $GET['alb_id'];
$sending_form_uri = $_SERVER['HTTP_REFERER'];


// View : get update template
$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/new_image.html');
$html .= file_get_contents('./templates/footer.html');


// Controller : merge & print html
$html = str_replace("%title%", $title, $html);
$html = str_replace('%img_id%', $img_id, $html);
$html = str_replace('%csrf_token%', $CSRF, $html);
$html = str_replace('%sending_from_uri%', $sending_form_uri, $html);


echo $html;

