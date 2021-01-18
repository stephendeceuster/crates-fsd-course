<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Haalt de formules binnen
require_once "lib/autoload.php";
$title = 'Registreer';
$html = file_get_contents('./templates/head.html');

if ( count($old_post) > 0 )
{
    $data = [ 0 => [
        "use_voornaam" => $old_post['usr_voornaam'],
        "use_naam" => $old_post['usr_naam'],
        "use_email" => $old_post['usr_email'],
        "use_password" => $old_post['usr_password']
    ]
    ];
}
else $data = [ 0 => [ "use_voornaam" => "", "use_naam" => "", "use_email" => "", "use_password" => "" ]];

//get template
$html .= file_get_contents("templates/register.html");

//add extra elements
$extra_elements['csrf_token'] = GenerateCSRF( "register.php"  );

//merge
$html = str_replace("%title%", $title, $html);
$html = MergeViewWithData( $html, $data );
$html = MergeViewWithExtraElements( $html, $extra_elements );
$html = MergeViewWithErrors( $html, $errors );
$html = RemoveEmptyErrorTags( $html, $data );
$html .= file_get_contents('./templates/footer.html');

print $html;
