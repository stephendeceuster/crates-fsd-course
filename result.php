<?php
// Checkt of er errors zijn
// error_reporting( E_ALL );
// ini_set( 'display_errors', 1 );
// Haalt de formules binnen
require_once "lib/autoload.php";


$html = file_get_contents('./templates/head.html');
$html = str_replace("%title%", "Search" , $html);
$html .= file_get_contents('./templates/header.html');

// Haalt zoekresultaat uit form
$result = '';
if ($_GET['search']) {
    $result= $_GET['search'];
}
// str replace zoekresultaat
$html .= file_get_contents("./templates/zoekresultaten.html");

// Haalt de albumresultaten uit de db,
// alles dus als er geen zoekopdracht wordt uitgevoerd
$html = str_replace("%ZOEKTERM%", $result, $html);

//get data
$sql = "select * from album ";
$sql .= "left join artist on alb_art_id = art_id ";
$sql .= "where art_naam like '%$result%' or alb_naam like '%$result%' ";
$sql .= "order by alb_naam asc ";

$data = GetData($sql);
//get template
$template = file_get_contents("templates/zoekresultaten-kolom.html");

//merge
$output = MergeViewWithData($template, $data);
$html = str_replace("%searchresult%", $output, $html);
$html .= file_get_contents('./templates/footer.html');
print $html;
