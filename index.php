<?php

//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

// Haalt de formules binnen
require_once "lib/autoload.php";


// MODEL - data
$title = 'Log in';

$data = [ 0 => [ "use_email" => "", "use_password" => "" ]];

//add extra elements
$extra_elements['csrf_token'] = GenerateCSRF( "index.php"  );
if ($errors['login-error']) {
    $message = "<p>" . $errors['login-error'] . "</p>";
} else {
    $message = "";
}


// VIEW - templates
$html = file_get_contents('./templates/head.html');
$html .= file_get_contents("templates/index.html");
$html .= file_get_contents('./templates/footer.html');


// CONTROLLER - merge & print
$html = str_replace("%title%", $title, $html);
$html = str_replace("%message%", $message, $html);
$html = MergeViewWithData( $html, $data );
$html = MergeViewWithExtraElements( $html, $extra_elements );


// Controller : merge & print html
$html = str_replace("title%", $title, $html);
echo $html;