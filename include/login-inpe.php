<?php

session_start();

function UrlAtual(){
	$dominio= $_SERVER['HTTP_HOST'];
	$url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
	return $url;
}

$urlPagina = "http://".$_SERVER['HTTP_HOST']."/acessoainformacao/institucional/agenda/";

$unidades;
$idPessoa;
$time;
$logado;

if (isset($_SESSION["logado"]) && $_SESSION["logado"] == true) {
    $logado = $_SESSION["logado"];
	$unidades = $_SESSION["unidades"];
	$idPessoa = $_SESSION["idPessoa"];
} else {
	$logado = null;
}

if(isset($_SESSION['sessiontime'])){
	$time = $_SESSION['sessiontime'];	
} else {
	$time = null;	
}

if (!$logado || $time < time()){
	session_destroy();
	if (UrlAtual() != $urlPagina){
		header("location:index.php");
	}
} else {
	$_SESSION["sessiontime"] = time() + 1200;
}

