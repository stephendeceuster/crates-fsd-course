<?php
function PrintHead()
{
    $head = file_get_contents("templates/head.html");
    print $head;
}

function MergeViewWithData( $template, $data )
{
    $returnvalue = "";

    foreach ( $data as $row )
    {
        $output = $template;

        foreach( array_keys($row) as $field )  {//eerst "img_id", dan "img_title", ...
          if ($field === 'alb_img' && ($row["$field"] === '' || $row["$field"] === null)) {
            $output = str_replace("%$field%", '0-crates.jpg', $output);
          } else {
            $output = str_replace( "%$field%", $row["$field"], $output );
          }
        }

        $returnvalue .= $output;
    }

    return $returnvalue;
}

function MergeViewWithExtraElements( $template, $selects )
{
    foreach ( $selects as $key => $select )
    {
        $template = str_replace( "%$key%", $select, $template );
    }
    return $template;
}

function MergeViewWithErrors( $template, $errors )
{
    foreach ( $errors as $key => $error )
    {
        $template = str_replace( "%$key%", "<p style='color:red'>$error</p>", $template );
    }
    return $template;
}

function RemoveEmptyErrorTags( $template, $data )
{
    foreach ( $data as $row )
    {
        foreach( array_keys($row) as $field )  //eerst "img_id", dan "img_title", ...
        {
            $template = str_replace( "%$field" . "_error%", "", $template );
        }
    }

    return $template;
}

function MakeSongform( $arr ) {
    $output = '';
    $songsNr = count($arr);
    for ($i = 1; $i <= $songsNr; $i++) {
        $output .= file_get_contents('templates/songs_form_songs.html');
        $output = str_replace('%i%', $i, $output);
        $output = str_replace('%son_title%', $arr[$i-1], $output);
    }
    for ($i = $songNr+1; $i <= 20; $i++) {
        $output .= file_get_contents('templates/songs_form_songs.html');
        $output = str_replace('%i%', $i, $output);
        $output = str_replace('%son_title%', '', $output);
    }
    return $output;
}