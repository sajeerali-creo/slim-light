<?php
require "config/database.php";
if(isset($_POST['submit'])){
    // getting input
    $username_email = filter_var($_POST['username_email'] , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var(($_POST['password']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$username_email){
        $_SESSION['signin'] = 'Username or Email is Inccorrect';

    }
    elseif(!$password){
        $_SESSION['signin'] = 'Password required';
 
    }else{  
        
    }

    if(isset($_SESSION['signin'])){
        $_SESSION['signin-data'] = $_POST;
        header('location: ' . ROOT_URL . 'login.php');
        die();
    }

}else{
    header('location: ' . ROOT_URL . "login.php");
    die();
}
