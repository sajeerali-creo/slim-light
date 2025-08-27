<?php
require 'config/constants.php';
require 'config/database.php';
session_start();
//destroy all sessions and redirect user to login page
session_unset();     // Unset all session variables
session_destroy();
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    mysqli_query($connection, "DELETE FROM remember_tokens WHERE token = '$token'");
    setcookie('remember_token', '', time() - 3600, '/');
}

header('location: ' . ROOT_URL);
die();
?>