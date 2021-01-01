<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

if ( ! is_numeric( $_GET['alb_id']) ) die("Ongeldig argument " . $_GET['img_id'] . " opgegeven");

$query = "select alb_naam, art_naam, gen_naam, alb_img ";
$query .= "from album left join artist on alb_art_id = art_id ";
$query .= "left join genre on alb_gen_id = gen_id ";
$query .= "where alb_id = " . $_GET['alb_id'] ;



$data= GetData($query);

//get template
$template = file_get_contents("templates/album.html");

//merge
$output = MergeViewWithData($template, $data);
print $output;

$query2 = "select alb_id, son_title from songs ";
$query2 .= "left join album on son_alb_id = alb_id ";
$query2 .= "where alb_id = " . $_GET['alb_id'];


$data2= GetData($query2);

//get template
$template2 = file_get_contents("templates/album_songs.html");

//merge
$output2 = MergeViewWithData($template2, $data2);
print $output2;

//get data
$data3 = GetData("select * from album
                     left join artist on alb_art_id = art_id 
                     where art_id = " . $_GET['ID']);

//get template
$template3 = file_get_contents("templates/album_andere_albums.html");

//merge
$output3 = MergeViewWithData($template3, $data3);
print $output3;