<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

$html ='';
$html .= file_get_contents('./templates/head.html');
$html .= file_get_contents('./templates/header.html');

$title = "Score/Commentaar";

$html = str_replace("%title%", $title, $html);

//----------------------------------------------
// Vul values in
//----------------------------------------------

$query = "select inh_id, inh_rating, inh_comment from user_album ";
$query .= "where inh_use_id = " . $_SESSION['user']['use_id'] ;
$query .= " and inh_alb_id  = " . $_GET['inh_alb_id'];

//get data
$data = GetData($query);

//get template
$template = file_get_contents("templates/comment_form.html");

//merge
$html .= MergeViewWithData($template, $data);

//------------------------------------------------------
// stel header in
//------------------------------------------------------

$header = "album.php?alb_id=" . $_GET['inh_alb_id'];
$html = str_replace("%header%", $header, $html );

//------------------------------------------------------
// Custom select
//------------------------------------------------------

//$selectGenreSql = 'SELECT gen_id, gen_naam FROM genre ORDER BY gen_naam ASC';
//$selectGenre = MakeSelect($fkey = 'alb_gen_id', $value = '', $sql = $selectGenreSql);

//$html = str_replace('%selectGenre%', $selectGenre, $html);

//--------------------------------------------------------
// CSRF
//---------------------------------------------------------

$CSRF = GenerateCSRF("comment.php");
$html = str_replace("%csrf_token%", $CSRF , $html);

//--------------------------------------------------------
// Footer en print
//--------------------------------------------------------

$html .= file_get_contents('./templates/footer.html');

print $html;
