<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";
//require_once "./templates/zoekresultaten.html";

if ( ! is_numeric( $_GET['alb_id']) ) die("Ongeldig argument " . $_GET['img_id'] . " opgegeven");

$query = "select alb_id, alb_naam, art_naam, art_id, gen_naam, alb_img ";
$query .= "from album left join artist on alb_art_id = art_id ";
$query .= "left join genre on alb_gen_id = gen_id ";
$query .= "where alb_id = " . $_GET['alb_id'] ;

$data = GetData($query);

//get template
$template = file_get_contents("templates/album.html");

//merge
$output = MergeViewWithData($template, $data);
print $output;

//-------------------------------------------------------------
// Hier voeg ik de template van de add to collection button toe
//-------------------------------------------------------------

$queryCollection = "select art_id, alb_id, inh_id from album ";
$queryCollection .= "left join artist on art_id = alb_art_id ";
$queryCollection .= "left join user_album on alb_id = inh_alb_id ";
$queryCollection .= "where alb_id = " . $_GET['alb_id'];

$dataCollection= GetData($queryCollection);

$dataCollection[0]["csrf_token"] = GenerateCSRF( "album.php" );

//get template
$templateCollection = file_get_contents("templates/album_add_to_collection.html");

//merge
$outputCollection = MergeViewWithData($templateCollection, $dataCollection);
print $outputCollection;


//-------------------------------------------------------------
// Hier voeg ik de template van de add to wishlist button toe
//-------------------------------------------------------------

//get template
$templateWishlist = file_get_contents("templates/album_add_to_wishlist.html");

//merge
$outputWishlist = MergeViewWithData($templateWishlist, $dataCollection);
print $outputWishlist;


//--------------------------------------------------------------
// Songs
//--------------------------------------------------------------

$query2 = "select alb_id, son_title from songs ";
$query2 .= "left join album on son_alb_id = alb_id ";
$query2 .= "where alb_id = " . $_GET['alb_id'];

$data2= GetData($query2);

//get template
$template2 = file_get_contents("templates/album_songs.html");

//merge
$output2 = MergeViewWithData($template2, $data2);
print $output2;

//-------------------------------------------------------------
// Andere albums van de artiest
//-------------------------------------------------------------

$query3 = "select * from album ";
$query3 .= "left join artist on alb_art_id = art_id ";
$query3 .= "where art_id = " . $_GET['ID'] ;
$query3 .= " and alb_id  != " . $_GET['alb_id'];

//get data
$data3 = GetData($query3);

//get template
$template3 = file_get_contents("templates/album_andere_albums.html");

//merge
$output3 = MergeViewWithData($template3, $data3);
print $output3;
