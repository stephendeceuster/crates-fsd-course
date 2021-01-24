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
$template .= file_get_contents("templates/searchbar.html");
$template .= file_get_contents("templates/album.html");
$template .= file_get_contents("templates/footer.html");
$template = str_replace('%title%', '%alb_naam% - %art_naam%', $template);

if (!empty($message)) {
    $output = file_get_contents('templates/message.html');
    $output = str_replace('%message_text%', $message[0], $output);
    $template = str_replace('%message%', $output, $template);
} else {
    $template = str_replace('%message%','', $template);
}

//merge
$html = MergeViewWithData($template, $data);


//---------------------------------------------------------------------------------------
// Buttons
//---------------------------------------------------------------------------------------

// use_lis_id
$queryLisID = "SELECT inh_lis_id FROM user_album ";
$queryLisID .= "WHERE inh_alb_id = " . $_GET['alb_id'];
$queryLisID .= " AND inh_use_id = " . $_SESSION['user']['use_id'];
$result = GetData($queryLisID);

//CSRF
$CSRF = GenerateCSRF("album.php");

//use_list 1, 2 of 0

if ($result[0]['0'] == 1) {
   //=>tekst 'in collectie';
    $html = str_replace("%button-collectie%", "<h4 class='in-collection'>In Collectie</h4>", $html);
    $html = str_replace("%button-wishlist%", "", $html);


    $template = file_get_contents("templates/album_delete_collection.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    $output = str_replace("%csrf_token%", $CSRF , $output);
    $html = str_replace("%button-delete%", $output, $html);


} elseif ($result[0]['0'] == 0) {
    //=> album krijgt 3 knoppen
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

    //idem wishlist button
    $template = file_get_contents("templates/album_add_to_wishlist.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    $output = str_replace("%csrf_token%", $CSRF , $output);
    $html = str_replace("%button-wishlist%", $output, $html);

    //haalt %delete-button% van de pagina
    $html = str_replace("%button-delete%", "", $html);

} else {
    //=> album krijgt 2 knoppen en tekst 'in wishlist'
    $template = file_get_contents("templates/album_add_to_collection.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    $output = str_replace("%csrf_token%", $CSRF , $output);
    $html = str_replace("%button-collectie%", $output, $html);

    $html = str_replace("%button-wishlist%", "<h4 class='in-wishlist'>In Wishlist</h4>", $html);

    $template = file_get_contents("templates/album_delete_collection.html");
    $output = str_replace("%alb_id%", $_GET['alb_id'], $template);
    $output = str_replace("%art_id%", $_GET['art_id'], $output);
    $output = str_replace("%csrf_token%", $CSRF , $output);
    $html = str_replace("%button-delete%", $output, $html);
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

//--------------------------------------------------------------
// Comments en rating
//--------------------------------------------------------------

if ($result[0]['0'] > 0) {
    $query4 = "select inh_rating, inh_comment, inh_alb_id from user_album ";
    $query4 .= "where inh_alb_id = " . $_GET['alb_id'];
    $query4 .= " and inh_use_id = " . $_SESSION['user']['use_id'];

//get data
    $data4 = GetData($query4);

    //get template
    $rating = file_get_contents('templates/rating/rating' . $data4[0]['inh_rating'] . '.html');

    $template4 = file_get_contents("templates/album_comments.html");
    $template4 = str_replace('%inh_rating%', $rating, $template4);

//merge
    $output4 = MergeViewWithData($template4, $data4);

    $html = str_replace("%comments%", $output4, $html);
} else{
    $html = str_replace("%comments%", "", $html);
}

//-------------------------------------------------------------
// Andere albums van de artiest
//-------------------------------------------------------------

$query3 = "select * from album ";
$query3 .= "left join artist on alb_art_id = art_id ";
$query3 .= "where art_id = " . $data[0]['art_id'] ;
$query3 .= " and alb_id  != " . $_GET['alb_id'];


//get data
$data3 = GetData($query3);

//get template
$template3 = file_get_contents("templates/album_andere_albums.html");

//merge
$output3 = MergeViewWithData($template3, $data3);
$html = str_replace("%albums%", $output3, $html);

print $html;

