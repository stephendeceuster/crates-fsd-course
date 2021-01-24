<?php

require_once './lib/autoload.php';

unset($_SESSION['user']);

session_regenerate_id();

session_destroy();


header("Location: index.php?logout=true");
