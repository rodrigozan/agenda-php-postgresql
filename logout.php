<?php 
include($_SERVER['DOCUMENT_ROOT']."/include/functions.php"); 
include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/institucional/agenda/include/functions-login.php";

$redirect = "/acessoainformacao/institucional/agenda/";

session_start();
logout();
header("location:$redirect");
?>