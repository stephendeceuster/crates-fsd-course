<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 1 );

$public_access = true;

require_once "autoload.php";

$user = LoginCheck();

if ($user) {
    $_SESSION['user'] = $user;
    $_SESSION['message'][0] = "Hallo " . $user['use_voornaam'] . "!";
    header('location: ../collection.php');
} else {
    $_SESSION['user'] = [];
    $_SESSION['errors']['login-error'] = "Er is iets misgelopen met het inloggen.";
    header('location: ../index.php');
}



function LoginCheck()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //controle CSRF token
        if (!key_exists("csrf", $_POST)) die("Missing CSRF");
        if (!hash_equals($_POST['csrf'], $_SESSION['lastest_csrf'])) die("Problem with CSRF");

        $_SESSION['lastest_csrf'] = "";

        //sanitization
        $_POST = StripSpaces($_POST);
        $_POST = ConvertSpecialChars($_POST);

        //validation
        $sending_form_uri = $_SERVER['HTTP_REFERER'];

        //checking password
        $email = $_POST['use_email'];
        $pw = $_POST['use_password'];
        $data = GetData("SELECT * FROM user WHERE use_email = '". $email . "'");
        
        if (password_verify($pw , $data[0]['use_password'] )){
            return $data[0];
        } 
    }
    return NULL;
}
