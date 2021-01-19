<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "autoload.php";

//if ($public_access !== true){
    if ( LoginCheck() )
    {
        $_SESSION['user'] = $_POST;
        $_SESSION['msgs'][] = "U bent ingelogd!";
        header('location: ../collection.php');
    }
    else
    {
        unset( $_SESSION['user'] );
        header('location: ../index.php');
    }
//}



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
        $password = GetData("select use_password from user where use_email = '". $_POST['use_email'] . "'");
        $password2 = $password[0][0];
        $value = $_POST['use_password'];
        if (password_verify($value , $password2 )){
            return true;
        }
    }
}
