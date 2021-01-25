<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

$public_access = true;
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

// Haalt de formules binnen
require_once "lib/autoload.php";

// MODEL - data
$title = 'Log in';

$data = [ 0 => [ "use_email" => "", "use_password" => "" ]];

//add extra elements
$extra_elements['csrf_token'] = GenerateCSRF( "index.php"  );


// VIEW - templates
$html = file_get_contents('./templates/head.html');
$html .= file_get_contents("templates/index.html");
$html .= file_get_contents('./templates/footer.html');


// CONTROLLER - merge & print
$html = str_replace("%title%", $title, $html);
// message
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $message[0] = 'U bent uitgelogd.';
}

if (!empty($message)) {   
    $output = file_get_contents('templates/message.html');
    $output = str_replace('%message_text%', $message[0], $output);
    $html = str_replace('%message%', $output, $html);
} else {
    $html = str_replace('%message%','', $html);
}

$html = MergeViewWithData( $html, $data );
$html = MergeViewWithExtraElements( $html, $extra_elements );

echo $html;
