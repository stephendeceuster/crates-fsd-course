<?php
// display errors
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// load library
require_once './lib/autoload.php';

//if (isset($_SESSION['message']) && $_SESSION['message']) {
//    printf('<b>%s</b>', $_SESSION['message']);
//    unset($_SESSION['message']);
//}

// Model : get data
$title = 'Upload album';

$CSRF = GenerateCSRF("update-db.php");

// View : get update template
$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/update-db.html');
$html .= file_get_contents('./templates/footer.html');

$datalistSql = 'SELECT art_naam FROM artist ORDER BY art_naam ASC';
$datalist = MakeDatalistArtist($datalistSql);


// Controller : merge & print html
$html = str_replace("%title%", $title, $html);
$html = str_replace('%csrf_token%', $CSRF, $html);
$html = str_replace('%datalist%', $datalist, $html);

echo $html;

