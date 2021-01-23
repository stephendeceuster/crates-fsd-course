<?php
// display errors
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

// load library
require_once './lib/autoload.php';

//if (isset($_SESSION['message']) && $_SESSION['message']) {
//    printf('<b>%s</b>', $_SESSION['message']);
//    unset($_SESSION['message']);
//}

// Model : get data
$title = 'Upload album';

$CSRF = GenerateCSRF("update-db.php");

$currentYear = date('Y'); 

// View : get update template
$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/update-db.html');
$html .= file_get_contents('./templates/footer.html');

$datalistArtistSql = 'SELECT art_naam FROM artist ORDER BY art_naam ASC';
$datalist = MakeDatalistArtist($datalistArtistSql);

$selectGenreSql = 'SELECT gen_id, gen_naam FROM genre ORDER BY gen_naam ASC';
//var_dump(getData($selectGenreSql));
$selectGenre = MakeSelect($fkey = 'alb_gen_id', $value = '', $sql = $selectGenreSql);

//var_dump($selectGenre);


// Controller : merge & print html
$html = str_replace("%title%", $title, $html);
$html = str_replace('%csrf_token%', $CSRF, $html);
$html = str_replace('%datalist%', $datalist, $html);
$html = str_replace('%selectGenre%', $selectGenre, $html);
$html = str_replace('%currentYear%', $currentYear, $html);

echo $html;

