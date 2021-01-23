<?php

if(!$public_access && !isset($_SESSION['user'])) {
        header("Location: ./index.php");
}

?>
