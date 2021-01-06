<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

if ( ! is_numeric( $_GET['alb_id']) ) die("Ongeldig argument " . $_GET['alb_id'] . " opgegeven");

//--------------------------------------------------------------------------------------
// Header, img, titel, artiest
//--------------------------------------------------------------------------------------

$query = "select alb_id, alb_naam, art_naam, art_id, gen_naam, alb_img ";
$query .= "from album left join artist on alb_art_id = art_id ";
$query .= "left join genre on alb_gen_id = gen_id ";
$query .= "where alb_id = " . $_GET['alb_id'] ;

$data = GetData($query);

//get template
$template = file_get_contents("templates/album.html");

//merge
$html = MergeViewWithData($template, $data);

//---------------------------------------------------------------------------------------
// Buttons
//---------------------------------------------------------------------------------------

// Bepaalt of een album al in user_albums is opgenomen
$queryLisID = 'SELECT inh_lis_id FROM user_album WHERE inh_alb_id = ' . $_GET['alb_id'];
$result = GetData($queryLisID);

if ($result[0]['0'] == 1) {
   //=>tekst 'in collectie';
    $html = str_replace("%button-collectie%", "<h2>In Collectie</h2>", $html);
    $html = str_replace("%button-wishlist%", "", $html);
} elseif ($result[0]['0'] == 0) {
    //=> knop 'toevoegen wishlist'
    $template = file_get_contents("templates/album_add_to_wishlist.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $html = str_replace("%button-collectie%", $output, $html);
    $html = str_replace("%button-wishlist%", $output, $html);
} else {
    //=> album krijgt 2 knoppen
    $template = file_get_contents("templates/album_add_to_collection.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $html = str_replace("%button-wishlist%", "<h2>In Wishlist</h2>", $html);
    $html = str_replace("%button-collectie%", $output, $html);
}

/*
//if result === 0 {
    $query = 'INSERT INTO user_album (inh_use_id, inh_alb_id, inh_lis_id)
                VALUES ($user_id, $_GET['alb_id'], 1 of 2)'
} else {
    $query = 'UPDATE ...'
}

*/
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
$html = str_replace("%songs%", $output2, $html);

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
$html = str_replace("%albums%", $output3, $html);

print $html;

