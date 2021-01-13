<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// Haalt de formules binnen
require_once "lib/autoload.php";

// Model : get data
$title = 'Crates | Login';

// View : get login template
$html = file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/index.html');
$html .= file_get_contents('./templates/footer.html');

// Controller : merge & print html
$html = str_replace("title%", $title, $html);
echo $html;