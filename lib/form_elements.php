<?php
require_once "autoload.php";

function MakeSelect( $fkey, $value, $sql )
{
    $select = "<select id=$fkey name=$fkey value=$value>";
    $select .= "<option value='0'>Maak een keuze</option>";

    $data = GetData($sql);

    foreach ( $data as $row )
    {
        if ( $row[0] == $value ) $selected = " selected ";
        else $selected = "";

        $select .= "<option $selected value=" . $row[0] . ">" . $row[1] . "</option>";
    }

    $select .= "</select>";

    return $select;
}

function MakeDatalistArtist ($sql) {
    $datalist = "<datalist id='artists'>";

    $data = GetData($sql);
    foreach ($data as $row) {
        $datalist .= "<option value='" . $row[0] . "'>";
    }
    $datalist .= "</datalist>";
    return $datalist;
}

