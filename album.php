<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";
//require_once "./templates/zoekresultaten.html";
var_dump($_GET['inh_lis_id']);

if ( ! is_numeric( $_GET['alb_id']) ) die("Ongeldig argument " . $_GET['alb_id'] . " opgegeven");

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
// Hier voeg ik de template van de add to collection button
// toe als het album nog niet toegevoegd is aan de collectie
//-------------------------------------------------------------

//$queryCollection = "select art_id, alb_id, inh_id from album ";
//$queryCollection .= "left join artist on art_id = alb_art_id ";
//$queryCollection .= "left join user_album on alb_id = inh_alb_id ";
//$queryCollection .= "where alb_id = " . $_GET['alb_id'];

$queryCollection = 'SELECT inh_lis_id FROM user_album WHERE inh_alb_id = ' . $_GET['alb_id'];
//$result = uitkomst query;
/*
if (result === 1) {
   //=>tekst 'in collectie';
} else {
    //=> knop 'toevoegen collectie';
    if (result === 2) {
        //=> teskt 'in wishlist';
    } else {
        //=> knop 'toevoegen wishlist';
    }
}

//if result === 0 {
    $query = 'INSERT INTO user_album (inh_use_id, inh_alb_id, inh_lis_id)
                VALUES ($user_id, $_GET['alb_id'], 1 of 2)'
} else {
    $query = 'UPDATE ...'
}
*/
$dataCollection = GetData($queryCollection);

$dataCollection[0]["csrf_token"] = GenerateCSRF("album.php");

if ($_GET["inh_lis_id"] != 1) {

//get template
    $templateCollection = file_get_contents("templates/album_add_to_collection.html");

//merge
    $outputCollection = MergeViewWithData($templateCollection, $dataCollection);
    print $outputCollection;

}

//-------------------------------------------------------------
// Hier voeg ik de template van de add to wishlist button toe
// als het album nog niet in de wishlist of de collectie staat
//-------------------------------------------------------------

if ($_GET["inh_lis_id"] == 0) {

//get template
    $templateWishlist = file_get_contents("templates/album_add_to_wishlist.html");

//merge
    $outputWishlist = MergeViewWithData($templateWishlist, $dataCollection);
    print $outputWishlist;

}

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
