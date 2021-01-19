<?php

require_once './lib/autoload.php';

if ($_SESSION['user']) {
  unset ($_SESSION['user']);
  header('Location: ./index.php'); exit();
}  