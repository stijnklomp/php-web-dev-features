<?php
session_start();
$user = json_decode(file_get_contents("php://input"));
setcookie('Username', $user->username, time()+60*60*24*30, '/', '', FALSE, TRUE);
setcookie('Password', $user->password, time()+60*60*24*30, '/', '', FALSE, TRUE);
?>