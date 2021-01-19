<?php

if(!$public_access) {
    if (!isset($_SESSION['user'])) {
        header("Location: ./index.php");
    }
}

?>