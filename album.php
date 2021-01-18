<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

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
$template = file_get_contents("templates/head.html");
$template .= file_get_contents("templates/header.html");
$template .= file_get_contents("templates/album.html");
$template .= file_get_contents("templates/footer.html");
$template = str_replace('%title%', '%alb_naam% - %art_naam%', $template);

//merge
$html = MergeViewWithData($template, $data);

//---------------------------------------------------------------------------------------
// Buttons
//---------------------------------------------------------------------------------------

// Bepaalt of een album al in user_albums is opgenomen
$sql = "select use_id from user ";
$sql .= "where use_email = '" . $_SESSION['user']['use_email'] . "'";

// use_id
$id = GetData($sql);

// use_lis_id
$queryLisID = "SELECT inh_lis_id FROM user_album ";
$queryLisID .= "WHERE inh_alb_id = " . $_GET['alb_id'];
$queryLisID .= " AND inh_use_id = " . $id[0]['use_id'];
$result = GetData($queryLisID);

//CSRF
$CSRF = GenerateCSRF("album.php");

//use_list 1, 2 of 0

if ($result[0]['0'] == 1) {
   //=>tekst 'in collectie';
    $html = str_replace("%button-collectie%", "<h4 class='in-collection'>In Collectie</h4>", $html);
    $html = str_replace("%button-wishlist%", "", $html);
} elseif ($result[0]['0'] == 0) {
    //=> album krijgt 2 knoppen
    // Neem de html-template
    $template = file_get_contents("templates/album_add_to_collection.html");
    // Vervang alb_id door juist getal
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    // Stuur art_id mee voor redirecting
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    // Voeg csrf token toe
    $output = str_replace("%csrf_token%", $CSRF , $output);
    // vervang de tag in volledige template
    $html = str_replace("%button-collectie%", $output, $html);

    $template = file_get_contents("templates/album_add_to_wishlist.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    $output = str_replace("%csrf_token%", $CSRF , $output);
    $html = str_replace("%button-wishlist%", $output, $html);
} else {
    //=> album krijgt 1 knop en tekst 'in wishlist'
    $template = file_get_contents("templates/album_add_to_collection.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    $output = str_replace("%csrf_token%", $CSRF , $output);
    $html = str_replace("%button-wishlist%", "<h4 class='in-wishlist'>In Wishlist</h4>", $html);
    $html = str_replace("%button-collectie%", $output, $html);
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
$html = str_replace("%songs%", $output2, $html);

//-------------------------------------------------------------
// Andere albums van de artiest
//-------------------------------------------------------------

$query3 = "select * from album ";
$query3 .= "left join artist on alb_art_id = art_id ";
$query3 .= "where art_id = " . $_GET['art_id'] ;
$query3 .= " and alb_id  != " . $_GET['alb_id'];

//get data
$data3 = GetData($query3);

//get template
$template3 = file_get_contents("templates/album_andere_albums.html");

//merge
$output3 = MergeViewWithData($template3, $data3);
$html = str_replace("%albums%", $output3, $html);

print $html;

