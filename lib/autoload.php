<?php
session_start();

require_once "connection_data.php";
require_once "pdo.php";
require_once "html_functions.php";
require_once "form_elements.php";
require_once "sanitize.php";
require_once "security.php";
require_once "validate.php";
require_once "access_control.php";


$errors = [];
$message = [];
$old_post = [];


if ( key_exists( 'errors', $_SESSION ) AND is_array( $_SESSION['errors']) )
{
    $errors = $_SESSION['errors'];
    $_SESSION['errors'] = null;
}

if ( key_exists( 'message', $_SESSION ) AND is_array( $_SESSION['message']) )
{
    $message = $_SESSION['message'];
    $_SESSION['message'] = null;
}

if ( key_exists( 'old_post', $_SESSION ) AND is_array( $_SESSION['old_post']) )
{
    $old_post = $_SESSION['old_post'];
    $_SESSION['old_post'] = null;
}