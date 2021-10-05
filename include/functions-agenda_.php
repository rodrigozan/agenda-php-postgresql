<?php

include($_SERVER['DOCUMENT_ROOT']."/include/connect-serv-agenda.php"); 
//include($_SERVER['DOCUMENT_ROOT']."/include/functions.php");
include $_SERVER['DOCUMENT_ROOT'] . "/include/functions-email.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/functions-ws.php";
include($_SERVER['DOCUMENT_ROOT']."/include/converte-data.php");
include($_SERVER['DOCUMENT_ROOT']."/include/functions-validacoes.php");

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

function getUnidades($unidades) {
    $option = "";
    foreach ($unidades as $unidade) {
        if($unidade['autoridade'] == "Patricia Marciano Leite"){
            $option .= "<option value=" . $unidade['id'] . ">(Diretor do INPE) Darcton Policarpo Damião</option>";
        }else {
            //$option .= "<option value=" . $unidade['id'] . ">" . $unidade['autoridade'].": ".$unidade['sigla'] . "</option>";
            $option .= "<option value=" . $unidade['id'] . ">(" . $unidade['nomeUnidade'] ."-" . $unidade['sigla'].") ".$unidade['autoridade'] . "</option>";
        }
        
    }
    return $option;
}

function validaEvento() {
	$valid = "";
    $erro = 0;
    $sucesso = "";

    global $valid, $erro;

    if (isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != null){
        $recaptcha = $_POST["g-recaptcha-response"];
        $erroCP = verificaCaptcha($recaptcha);
        if ($erroCP == false){
            $erro = 1;		
            $valid .= "Captcha Inválido<br />";	
        }
    } else {
        $erro = 1;		
        $valid .= "Captcha não foi selecionado!<br />";	
    }
        
    $title = null;
    if (isset($_POST["title"]) && $_POST["title"] != null){
        $title = $_POST["title"];
        $title = strip_tags($title); //remove tags html	
        $error2 = verificaTamanho($title, 150); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Titulo com tamanho maior que o permitido!<br />";
        }
    } else {
        $erro = 1;		
        $valid .= "Titulo não foi informado!<br />";	
    }
    $description = null;
    if (isset($_POST["description"]) && $_POST["description"] != null){
        $description = $_POST["description"];
        $description = strip_tags($description); //remove tags html	
        $error2 = verificaTamanho($description, 1000); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Descrição com tamanho maior que o permitido!<br />";
        }
    }else {
        $description = "";
    }

    $classname = "";

    $horainicio = null;
    if (isset($_POST["horainicio"]) && $_POST["horainicio"] != null){
        $horainicio = $_POST["horainicio"];
        $horainicio = strip_tags($horainicio);
        $error2 = verificaTamanho($horainicio, 8); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Data de início com tamanho maior que o permitido<br />";
        }
    }

    $start = null;
    if (isset($_POST["start"]) && $_POST["start"] != null){
        $classname = "event-single";
        if($_POST["horainicio"] == ""){
            $horainicio = "00:00:00";
        }
        $start = $_POST["start"].' '.$horainicio;
        $start = strip_tags($start); //remove tags html	
        $error2 = verificaTamanho($start, 19); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Data de início com tamanho maior que o permitido<br />";
        }
    } else {
        $erro = 1;		
        $valid .= "Data de início não foi informada<br />";	
    }

    $horafim = null;
    $end = null;
    if (isset($_POST["end"]) && $_POST["end"] != null){
        if (!isset($_POST["horainicio"]) && $_POST["horainicio"] == null){
            $erro = 1;		
            $valid .= "Hora de início não foi informada<br />";	
        }
        if (isset($_POST["horafim"]) && $_POST["horafim"] != null){
            $horafim = $_POST["horafim"];
            $horafim = strip_tags($horafim);
            $error2 = verificaTamanho($horafim, 8); //verifica tamanho do campo
            if ($error2 == 1){
                $erro = 1;
                $valid .= "Data de início com tamanho maior que o permitido<br />";
            }
        }else {
            $erro = 1;		
            $valid .= "Hora fim não foi informada<br />";	
        }
        $end = $_POST["end"].' '.$horafim;        
        $end = strip_tags($end); //remove tags html	
        $error2 = verificaTamanho($end, 19); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Data fim com tamanho maior que o permitido<br />";
        }
        $classname = "event-period";
    } else {
        if (isset($_POST["horafim"]) && $_POST["horafim"] != null){
            $end = $_POST["start"].' '.$_POST["horafim"];
        }else {
            $end = $start;
        }
    }

    $visivel = 0;
    if (isset($_POST["visivel"]) && $_POST["visivel"] != null){
        $visivel = $_POST["visivel"];
        $visivel = strip_tags($visivel); //remove tags html	
    }

    $acao = null;
    if (isset($_POST["acao"]) && $_POST["acao"] != null){
        $acao = $_POST["acao"];
        $acao = strip_tags($acao); //remove tags html	
    }

    $idPessoa = null;
    if (isset($_POST["idPessoa"]) && $_POST["idPessoa"] != null){
        $idPessoa = $_POST["idPessoa"];
        $idPessoa = strip_tags($idPessoa); //remove tags html	
    }

    $unidade;
    if (isset($_POST["unidade"]) && $_POST["unidade"] != null){
        $unidade = $_POST["unidade"];
        $unidade = strip_tags($unidade); //remove tags html	
        $error2 = verificaTamanho($unidade, 5); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Unidade com tamanho maior que o permitido!<br />";
        }
    } else {
        $erro = 1;		
        $valid .= "Unidade não foi informada!<br />";	
    }

    /*echo "Ação: " . $_POST['acao'] . "<br>";
    echo 'Unidade pessoa: ' . $idPessoa . "<br>"; 
    echo 'Título: ' . $title . "<br>"; 
    echo 'Descrição:' . $description . "<br>"; 
    echo 'Dia de início: ' . $start . "<br>"; 
    echo 'Hora de início: ' . $horainicio . "<br>";
    echo 'Dia do término: ' . $end . "<br>"; 
    echo 'Hora do término: ' . $horafim . "<br>"; 
    echo 'Visível: ' . $visivel . "<br>"; 
    echo 'Classname: ' . $classname . "<br>"; 
    echo 'Unidade: ' . $unidade . "<br>";

    /*if($erro == 0){
        if(!salvarEvento($title, $description, $start, $horainicio, $end, $horafim, $visivel, $classname, $unidade, $idPessoa, $acao)){
            $erro = 1;
            echo $erro;
            $valid .= "Não foi possível salvar o cadastro!<br />";
        }
    }*/

    if($erro == 0){
        if(!salvarEvento($title, $description, $start, $end, $visivel, $classname, $unidade, $idPessoa, $acao)){
            $erro = 1;
            echo $erro;
            $valid .= "Não foi possível salvar o cadastro!<br />";
        }
    }

}

function salvarEvento($title, $description, $start, $end, $visivel, $classname, $unidade, $idPessoa, $acao){

    $salvo = false;
    $pdo = conecta();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if($pdo != null){
        try { 
            $pdo->beginTransaction();
                    
            $query = "INSERT INTO agenda_autoridades.acesso_informacao_agenda(title, description, start, visivel, classname, unidade, idpessoa, ultimaatualizacao, \"end\") 
						VALUES (:title, :description, :start, :visivel, :classname, :unidade, :idpessoa, now(), :end) 
                        RETURNING ag_id";
            $result = executaSql($pdo, $query);
            $result->bindValue(':title', $title, PDO::PARAM_STR);
            $result->bindValue(':description', $description, PDO::PARAM_STR);
            $result->bindValue(':start', $start, PDO::PARAM_STR);
            //$result->bindValue(':starthorainicio', $horainicio, PDO::PARAM_STR);
            $result->bindValue(':end', $end, PDO::PARAM_STR);
            //$result->bindValue(':endhoratermino', $horafim, PDO::PARAM_STR);
            $result->bindValue(':visivel', $visivel, PDO::PARAM_STR);
            $result->bindValue(':classname', $classname, PDO::PARAM_STR);
            $result->bindValue(':idpessoa', $idPessoa, PDO::PARAM_STR);
            $result->bindValue(':unidade', $unidade, PDO::PARAM_STR);
                    
            $result->execute(); 

            $row = $result->fetch(PDO::FETCH_OBJ);
            $idevento = $row->ag_id;

            $salvo = true;
            
            $pdo->commit();		

            //salvarAcao($idevento, $idPessoa, $acao);
            
        } catch(PDOExecption $e) { 
            $pdo->rollBack(); 			
            $salvo = false;
        } 	
        
    }
    fecha($pdo, $result);
    return $salvo;
}

function listaEventos($diaConsulta) {
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    $pdo = conecta();	
    $eventos = "";
    if($pdo != null){

        $diaConsulta = $_POST['diaConsulta']; 
        $unidade = $_POST['unidade']; 

        // '".$diaConsulta."' between start::date and \"end\"::date  and

        $query = "SELECT * FROM agenda_autoridades.acesso_informacao_agenda where '".$diaConsulta."' between start::date and \"end\"::date  and unidade ='$unidade'"; 


        $result = executaSql($pdo,$query);
        $result->execute();	
        $eventos = "";

        while($row=$result->fetch(PDO::FETCH_ASSOC)) {	
 
            $id = $row["ag_id"];
            $idpessoa = trim($row["idpessoa"]);
            $title = trim($row["title"]);
            $description = trim($row["description"]);
            $start = date("d/m/Y", strtotime($row["start"]));
            $horainicio = date("H:i", strtotime($row["start"]));
            $start =  $start . " " . $horainicio;
            $end = date("d/m/Y", strtotime($row["end"]));
            $horatermino = date("H:i", strtotime($row["end"]));
            $end =  $end . " " . $horatermino;

            if($row["start"] == $row["end"]){
                $end = null;
                if($horainicio ==  $horatermino){
                    $horatermino = null;
                }
            }

            if($horainicio == "00:00"){
                $start = date("d/m/Y", strtotime($row["start"]));
            }
            
            
            $visivel = $row["visivel"];
            $unidade = $row["unidade"];
            $classname = $row["classname"];

            $eventos .= "<tr><td style='display:none;' class='id'>".$id."</td>".
            "<td style='display:none;' class='visivel'>".$visivel."</td>".
            "<td style='display:none;' class='idpessoa'>".$idpessoa."</td>".
            "<td style='display:none;' class='unidade'>".$unidade."</td>".
            "<td style='display:none;' class='classname'>".$classname."</td>".
            "<td class='title'>".$title."</td>".
            "<td class='description'>".$description."</td>".
            "<td class='start'>".$start.
            "<td style='display:none;' class='horainicio'>".$horainicio.
            "<td style='display:none;' class='horafim'>".$horatermino.
            "<td class='end'>".$end.
            "</td><td class=\"acao\"><button class='editar'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></button></td>
            <td class=\"acao\"><button class='deletar'><span class='glyphicon glyphicon-remove' aria-hidden='true'></button></span></td>";
            if($visivel == TRUE){
                $eventos .= "<td class=\"acao\"><button class='disponibilizado'><span class='glyphicon glyphicon-ok' aria-hidden='true'></button></span></td></tr>";
            }else {
                $eventos .= "<td class=\"acao\"><button class='disponibilizar'><span class='glyphicon glyphicon-ok' aria-hidden='true'></button></span></td></tr>";
            }
        }

        echo $eventos;
    }
    fecha($pdo, $result);
}

function validaUpdateEvento() {
	$valid = "";
	$erro = 0;

    global $valid, $erro;

    if (isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != null){
        $recaptcha = $_POST["g-recaptcha-response"];
        $erroCP = verificaCaptcha($recaptcha);
        if ($erroCP == false){
            $erro = 1;		
            $valid .= "Captcha Inválido!<br />";	
        }
    } else {
        $erro = 1;		
        $valid .= "Captcha não foi selecionado)!<br />";	
    }

    if (isset($_POST["id"]) && $_POST["id"] != null){
        $id = $_POST["id"];
        $id = strip_tags($id); 
    } else {
        $erro = 1;		
        $valid .= "Id não foi informado!<br />";	
    }
        
    $title = null;
    if (isset($_POST["title"]) && $_POST["title"] != null){
        $title = $_POST["title"];
        $title = strip_tags($title); //remove tags html	
        $error2 = verificaTamanho($title, 150); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Titulo com tamanho maior que o permitido!<br />";
        }
    } else {
        $erro = 1;		
        $valid .= "Titulo não foi informado!<br />";	
    }
    $description = null;
    if (isset($_POST["description"]) && $_POST["description"] != null){
        $description = $_POST["description"];
        $description = strip_tags($description); //remove tags html	
        $error2 = verificaTamanho($description, 1000); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Descrição com tamanho maior que o permitido!<br />";
        }
    }else {
        $description = "";
    }

    $classname = "";

    $horainicio = null;
    if (isset($_POST["horainicio"]) && $_POST["horainicio"] != null){
        $horainicio = $_POST["horainicio"];
        $horainicio = strip_tags($horainicio);
        $error2 = verificaTamanho($horainicio, 8); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Data de início com tamanho maior que o permitido<br />";
        }
    }

    $start = null;
    if (isset($_POST["start"]) && $_POST["start"] != null){
        $classname = "event-single";
        if($_POST["horainicio"] == ""){
            $horainicio = "00:00:00";
        }
        $start = $_POST["start"].' '.$horainicio;
        $start = strip_tags($start); //remove tags html	
        $error2 = verificaTamanho($start, 19); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Data de início com tamanho maior que o permitido<br />";
        }
    } else {
        $erro = 1;		
        $valid .= "Data de início não foi informada<br />";	
    }

    $horafim = null;
    $end = null;
    if (isset($_POST["end"]) && $_POST["end"] != null){
        if (!isset($_POST["horainicio"]) && $_POST["horainicio"] == null){
            $erro = 1;		
            $valid .= "Hora de início não foi informada<br />";	
        }
        if (isset($_POST["horafim"]) && $_POST["horafim"] != null){
            $horafim = $_POST["horafim"];
            $horafim = strip_tags($horafim);
            $error2 = verificaTamanho($horafim, 8); //verifica tamanho do campo
            if ($error2 == 1){
                $erro = 1;
                $valid .= "Data de início com tamanho maior que o permitido<br />";
            }
        }else {
            $erro = 1;		
            $valid .= "Hora fim não foi informada<br />";	
        }
        $end = $_POST["end"].' '.$horafim;        
        $end = strip_tags($end); //remove tags html	
        $error2 = verificaTamanho($end, 19); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Data fim com tamanho maior que o permitido<br />";
        }
        $classname = "event-period";
    } else {
        if (isset($_POST["horafim"]) && $_POST["horafim"] != null){
            $end = $_POST["start"].' '.$_POST["horafim"];
        }else {
            $end = $start;
        }
    }

    $visivel = 0;
    if (isset($_POST["visivel"]) && $_POST["visivel"] != null){
        $visivel = $_POST["visivel"];
        $visivel = strip_tags($visivel); //remove tags html	
    }

    $acao = null;
    if (isset($_POST["acao"]) && $_POST["acao"] != null){
        $acao = $_POST["acao"];
        $acao = strip_tags($acao); //remove tags html	
    }

    $idPessoa = null;
    if (isset($_POST["idPessoa"]) && $_POST["idPessoa"] != null){
        $idPessoa = $_POST["idPessoa"];
        $idPessoa = strip_tags($idPessoa); //remove tags html	
    }

    $unidade;
    if (isset($_POST["unidade"]) && $_POST["unidade"] != null){
        $unidade = $_POST["unidade"];
        $unidade = strip_tags($unidade); //remove tags html	
        $error2 = verificaTamanho($unidade, 5); //verifica tamanho do campo
        if ($error2 == 1){
            $erro = 1;
            $valid .= "Unidade com tamanho maior que o permitido!<br />";
        }
    } else {
        $erro = 1;		
        $valid .= "Unidade não foi informada!<br />";	
    }

    $id = $_POST['id'];

    /*echo 'Título: ' . $title . "<br>"; 
    echo 'Descrição:' . $description . "<br>"; 
    echo 'Dia de início: ' . $start . "<br>"; 
    echo 'Hora de início: ' . $horainicio . "<br>";
    echo 'Dia do término: ' . $end . "<br>"; 
    echo 'Hora do término: ' . $horafim . "<br>"; 
    echo 'Visível: ' . $visivel . "<br>"; 
    echo 'Classname: ' . $classname . "<br>"; 
    echo 'Unidade: ' . $unidade . "<br>";
    echo 'Id Pessoa: ' . $idPessoa . "<br>";*/
   

    if($erro == 0){
        
        if(!updateEvento($id, $title, $description, $start, $end, $visivel, $classname, $unidade, $idPessoa, $acao)){
            $erro = 1;
            echo $erro;
            $valid .= "Não foi possível editar o evento!<br />";
        } 
    }

}

function updateEvento($id, $title, $description, $start, $end, $visivel, $classname, $unidade, $idPessoa, $acao){

    $salvo = false;
    $pdo = conecta();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if($pdo != null){
        try { 
            $pdo->beginTransaction();

            $query = "UPDATE agenda_autoridades.acesso_informacao_agenda SET (title, description, start, visivel, classname, unidade, idpessoa, ultimaatualizacao, \"end\") 
            = (:title, :description, :start, :visivel, :classname, :unidade, :idpessoa, now(), :end) 
            WHERE ag_id = '$id'";
            
            $result = executaSql($pdo, $query);
            $result->bindValue(':title', $title, PDO::PARAM_STR);
            $result->bindValue(':description', $description, PDO::PARAM_STR);
            $result->bindValue(':start', $start, PDO::PARAM_STR);
            //$result->bindValue(':starthorainicio', $horainicio, PDO::PARAM_STR);
            $result->bindValue(':end', $end, PDO::PARAM_STR);
            //$result->bindValue(':endhoratermino', $horafim, PDO::PARAM_STR);
            $result->bindValue(':visivel', $visivel, PDO::PARAM_BOOL);
            $result->bindValue(':classname', $classname, PDO::PARAM_STR);
            $result->bindValue(':idpessoa', $idPessoa, PDO::PARAM_STR);
            $result->bindValue(':unidade', $unidade, PDO::PARAM_STR);
                    
            $result->execute(); 

            $row = $result->fetch(PDO::FETCH_OBJ);

            $salvo = true;
            
            $pdo->commit();				
            
        } catch(PDOExecption $e) { 
            $pdo->rollBack(); 			
            $salvo = false;
        } 			
        
    }
    fecha($pdo, $result);
    return $salvo;
}

function validaDeleteEvento() {
    $valid = "";
    $erro = 0;
    
    global $valid, $erro;

    if (isset($_POST["unidade"]) && $_POST["unidade"] != null){
        $unidade = $_POST["unidade"];
        $unidade = strip_tags($unidade); 
    } else {
        $erro = 1;		
        $valid .= "Unidade não foi informado!<br />";	
    }

    if (isset($_POST["id"]) && $_POST["id"] != null){
        $id = $_POST["id"];
        $id = strip_tags($id); 
    } else {
        $erro = 1;		
        $valid .= "Id não foi informado!<br />";	
    }

    if($erro == 0){
        
        if(!deletaEvento($id, $unidade)){
            $erro = 1;
            echo $erro;
            $valid .= "Não foi possível apagar o evento!<br />";
        } 
    }
}


function deletaEvento($id, $unidade) {

    $pdo = conecta();	

    if($pdo != null){
        $query = "DELETE FROM agenda_autoridades.acesso_informacao_agenda WHERE ag_id = '$id'";

        $result = executaSql($pdo,$query);
        //$result->bindValue(':ag_id', $id);
        
        $result->execute();

        return $result->rowCount();
    }
}

function validaDisponibilizaEvento() {
    $valid = "";
    $erro = 0;
    
    global $valid, $erro;

    if (isset($_POST["id"]) && $_POST["id"] != null){
        $id = $_POST["id"];
        $id = strip_tags($id); 
    } else {
        $erro = 1;		
        $valid .= "Id não foi informado!<br />";	
    }

    if (isset($_POST["unidade"]) && $_POST["unidade"] != null){
        $unidade = $_POST["unidade"];
        $unidade = strip_tags($unidade); 
    } else {
        $erro = 1;		
        $valid .= "Unidade não foi informado!<br />";	
    }

    $visivel = 1;

    if($erro == 0){
        
        if(!disponibilizaEvento($id, $visivel, $unidade)){
            $erro = 1;
            echo $erro;
            $valid .= "Não foi possível apagar o evento!<br />";
        } 
    }
}


function disponibilizaEvento($id, $visivel, $unidade) {

    $pdo = conecta();	

    if($pdo != null){
        $query = "UPDATE agenda_autoridades.acesso_informacao_agenda SET (visivel) 
                        = (:visivel) 
                        WHERE ag_id = '$id'";

        $result = executaSql($pdo,$query);
        $result->bindValue(':visivel', $visivel);
        
        $result->execute();

        return $result->rowCount();
    }
}

function validaIndisponibilizaEvento() {
    $valid = "";
    $erro = 0;
    
    global $valid, $erro;

    if (isset($_POST["unidade"]) && $_POST["unidade"] != null){
        $unidade = $_POST["unidade"];
        $unidade = strip_tags($unidade); 
    } else {
        $erro = 1;		
        $valid .= "Unidade não foi informado!<br />";	
    }

    if (isset($_POST["id"]) && $_POST["id"] != null){
        $id = $_POST["id"];
        $id = strip_tags($id); 
    } else {
        $erro = 1;		
        $valid .= "Id não foi informado!<br />";	
    }

    $visivel = 0;

    if($erro == 0){
        
        if(!indisponibilizaEvento($id, $visivel, $unidade)){
            $erro = 1;
            echo $erro;
            $valid .= "Não foi possível disponibilizar o evento!<br />";
        } 
    }
}


function indisponibilizaEvento($id, $visivel, $unidade) {

    $pdo = conecta();	

    if($pdo != null){
        $query = "UPDATE agenda_autoridades.acesso_informacao_agenda SET (visivel) 
                        = (:visivel) 
                        WHERE ag_id = '$id'";

        $result = executaSql($pdo,$query);
        $result->bindValue(':visivel', $visivel);
        
        $result->execute();

        return $result->rowCount();
    }
}


    
