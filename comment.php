<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents("templates/comment_form.html");
$html .= file_get_contents('./templates/footer.html');

$selectGenreSql = 'SELECT gen_id, gen_naam FROM genre ORDER BY gen_naam ASC';
$selectGenre = MakeSelect($fkey = 'alb_gen_id', $value = '', $sql = $selectGenreSql);

$html = str_replace('%selectGenre%', $selectGenre, $html);

print $html;
