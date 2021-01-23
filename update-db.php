<?php
// display errors
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// load library
require_once './lib/autoload.php';

// MODEL : get data
$title = 'Upload album';
$CSRF = GenerateCSRF("update-db.php");
$currentYear = date('Y');
$data = [ 0 => [ "alb_naam" => "", "art_naam" => "" ]];

<<<<<<< Updated upstream
// View : get update template
=======
// VIEW : get update template
>>>>>>> Stashed changes
$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/update-db.html');
$html .= file_get_contents('./templates/footer.html');

<<<<<<< Updated upstream
$datalistSql = 'SELECT art_naam FROM artist ORDER BY art_naam ASC';
$datalist = MakeDatalistArtist($datalistSql);

=======
$datalistArtistSql = 'SELECT art_naam FROM artist ORDER BY art_naam ASC';
$datalist = MakeDatalistArtist($datalistArtistSql);

$selectGenreSql = 'SELECT gen_id, gen_naam FROM genre ORDER BY gen_naam ASC';
$selectGenre = MakeSelect($fkey = 'alb_gen_id', $value = '', $sql = $selectGenreSql);

// var_dump($errors);
// var_dump($data);
>>>>>>> Stashed changes

// CONTROLLER : merge & print html
$html = str_replace("%title%", $title, $html);
$html = str_replace('%csrf_token%', $CSRF, $html);
$html = str_replace('%datalist%', $datalist, $html);
<<<<<<< Updated upstream
=======
$html = str_replace('%selectGenre%', $selectGenre, $html);
$html = str_replace('%currentYear%', $currentYear, $html);
MergeViewWithErrors( $html, $errors );
RemoveEmptyErrorTags( $html, $data );
>>>>>>> Stashed changes

echo $html;

