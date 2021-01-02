<?php

// Checkt of er errors zijn
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

// Haalt de formules binnen
require_once "lib/autoload.php";

// Haalt de header en de search bar
require_once "./templates/zoekresultaten.html";

// Haalt zoekresultaat uit form
$result = $_POST['search'];

// str replace zoekresultaat
$searchbar = "./templates/zoekresultaten.html";

str_replace("%ZOEKTERM%", "iets", $searchbar);

// Haalt de albumresultaten uit de db,
// alles dus als er geen zoekopdracht wordt uitgevoerd

$query = "select inh_use_id, alb_naam, alb_img, art_naam,  inh_lis_id, alb_id, art_id from user_album ";
$query .= "left join album on inh_alb_id = alb_id ";
$query .= "left join artist on alb_art_id = art_id ";
$query .= "where inh_use_id = 1 and inh_lis_id = 2";

//get data
$data = GetData($query);

//get template
$template = file_get_contents("templates/zoekresultaten-kolom.html");

//merge
$output = MergeViewWithData($template, $data);
print $output;
