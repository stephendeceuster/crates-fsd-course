<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

$html = file_get_contents("templates/head.html");
$html .= file_get_contents("templates/header.html");
$html .= file_get_contents("templates/songs_form.html");

$query = "select son_id, alb_id, son_title from songs ";
$query .= "left join album on son_alb_id = alb_id ";
$query .= "where alb_id = " . $_GET['alb_id'];

$data= GetData($query);
//get template
$template = file_get_contents("templates/songs_form_songs.html");
//merge
$output = MergeViewWithData($template, $data);
$output = str_replace("%son_title%", $output, $output);

$html = str_replace("%songs%", $output, $html);

print $html;