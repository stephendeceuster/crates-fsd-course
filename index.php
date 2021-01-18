<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Haalt de formules binnen
require_once "lib/autoload.php";

$title = 'Log in';
$html = file_get_contents('./templates/head.html');


$data = [ 0 => [ "use_email" => "", "use_password" => "" ]];

//get template
$html .= file_get_contents("templates/index.html");

//add extra elements
$extra_elements['csrf_token'] = GenerateCSRF( "index.php"  );

//merge
$html = str_replace("%title%", $title, $html);
$html = MergeViewWithData( $html, $data );
$html = MergeViewWithExtraElements( $html, $extra_elements );
$html .= file_get_contents('./templates/footer.html');

print $html;
