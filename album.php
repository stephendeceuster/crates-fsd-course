<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

if ( ! is_numeric( $_GET['alb_id']) ) die("Ongeldig argument " . $_GET['img_id'] . " opgegeven");

$data= GetData("select * from album inner join artist on alb_art_id = art_id where alb_id=" . $_GET['alb_id'] );

//get template
$template = file_get_contents("templates/zoekresultaten-kolom.html");

//merge
$output = MergeViewWithData($template, $data);
print $output;