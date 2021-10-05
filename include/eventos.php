<?php

include($_SERVER['DOCUMENT_ROOT']."/include/connect-serv-agenda.php"); 
include($_SERVER['DOCUMENT_ROOT']."/include/functions.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/functions-ws.php";

//include "functions-agenda.php";
header('Content-type: application/json');
$pdo = conecta();	
	
if($pdo != null){
    $unidade = $_POST['unidade'];
    if(isset($unidade)) {
        $query = "SELECT * FROM agenda_autoridades.acesso_informacao_agenda where unidade = ".$unidade;
    }else {
        $query = "SELECT * FROM agenda_autoridades.acesso_informacao_agenda";
    }
    $result = executaSql($pdo,$query);
    $result->execute();	
    
    while($row=$result->fetch(PDO::FETCH_OBJ)) {	
        $visivel = $row->visivel;
        if($visivel == True){
            $post_data[] = $row;
        }
    }

    $json = json_encode($post_data);

    echo $json;
            
    fecha($pdo, $result);
}