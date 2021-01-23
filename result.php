<?php
// Checkt of er errors zijn
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );
// Haalt de formules binnen
require_once "lib/autoload.php";

$html = file_get_contents('./templates/head.html');
$html = str_replace("%title%", "Search" , $html);
$html .= file_get_contents('./templates/header.html');
$html .= file_get_contents('./templates/searchbar.html');

// Haalt zoekresultaat uit form
$result = '';
if (isset($_GET['search'])) {
    $result= $_GET['search'];
}
$results = explode(" ", $result);

// str replace zoekresultaat
$html .= file_get_contents("./templates/zoekresultaten.html");

// Haalt de albumresultaten uit de db,
// alles dus als er geen zoekopdracht wordt uitgevoerd
$html = str_replace("%ZOEKTERM%", $result, $html);
$currentURL = $_SERVER['REQUEST_URI'];
$html = str_replace("%currentURL%", $currentURL, $html);

// Sorteer via dropdown
if ($_GET['sorting']) {
    $sort = $_GET['sorting'];
}else{
    $sort = "alb_naam";
}

//get data
$sql = "select * from album ";
$sql .= "left join artist on alb_art_id = art_id ";
$sql .= "where art_naam like '%$results[0]%' or alb_naam like '%$results[0]%' ";
if (count($results) > 1 ){
    $sql .= "or art_naam like '%$results[1]%' or alb_naam like '%$results[1]%' ";
}
$sql .= "order by " . $sort . " asc ";

$data = GetData($sql);
//get template
$template = file_get_contents("templates/zoekresultaten-kolom.html");

//merge
$output = MergeViewWithData($template, $data);
$html = str_replace("%searchresult%", $output, $html);
$html .= file_get_contents('./templates/footer.html');
print $html;
