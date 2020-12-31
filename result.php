<?php
// Checkt of er errors zijn
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// Haalt de formules binnen
require_once "lib/autoload.php";

// Haalt de header en de search bar
require_once "./templates/zoekresultaten.html";

// Haalt zoekresultaat uit form
$result= $_POST['search'];

// str replace zoekresultaat
$searchbar = "./templates/zoekresultaten.html";

str_replace("%ZOEKTERM%", "iets", $searchbar);

// Haalt de albumresultaten uit de db,
// alles dus als er geen zoekopdracht wordt uitgevoerd

//get data
$data = GetData("select * from album
                     left join artist on alb_art_id = art_id 
                     where art_naam like '%$result%' or alb_naam like '%$result%'");

//get template
$template = file_get_contents("templates/zoekresultaten-kolom.html");

//merge
$output = MergeViewWithData($template, $data);
print $output;
