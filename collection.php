<?php
// Checkt of er errors zijn
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

// Haalt de formules binnen
require_once "lib/autoload.php";

// Haalt zoekresultaat uit form
$result = '';
if ($_GET['search']) {
    $result= $_GET['search'];
}
// str replace zoekresultaat
$html = file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/searchbar.html');
$html .= file_get_contents("./templates/zoekresultaten.html");

$title = 'Mijn collectie';
$currentURL = $_SERVER['REQUEST_URI'];
$html = str_replace('%title%', $title, $html);
$html = str_replace('%heading', $title, $html);
$html = str_replace("%ZOEKTERM%", $result, $html);
$html = str_replace("%currentURL%", $currentURL, $html);

// message
if (!empty($message)) {
    $output = file_get_contents('templates/message.html');
    $output = str_replace('%message_text%', $message[0], $output);
    $html = str_replace('%message%', $output, $html);
} else {
    $html = str_replace('%message%','', $html);
}

// Sorteer via dropdown
if ($_GET['sorting']) {
    $sort = $_GET['sorting'];
}else{
    $sort = "alb_naam";
}

//get data
$sql = "select inh_use_id, alb_naam, alb_img, art_naam,  inh_lis_id, alb_id, art_id from user_album ";
$sql .= "left join album on inh_alb_id = alb_id ";
$sql .= "left join artist on alb_art_id = art_id ";
$sql .= "where inh_use_id = " . $_SESSION['user']['use_id'] . " and inh_lis_id = 1 ";
$sql .= "order by " . $sort . " asc ";
$data = GetData($sql);
//get template
$template = file_get_contents("templates/zoekresultaten-kolom.html");

//merge
$output = MergeViewWithData($template, $data);
$html = str_replace("%searchresult%", $output, $html);

// print  tekstje als er nog geen albums aanwezig zijn
if (empty($data)){
    $html .= "<h2 class='empty-collection'> U heeft nog geen albums in uw collectie. </h2>";
}

// voeg footer toe
$html .= file_get_contents('./templates/footer.html');

print $html;





