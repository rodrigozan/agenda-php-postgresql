<?php

include $_SERVER['DOCUMENT_ROOT'] . "/include/connect-serv-agenda.php";
include $_SERVER['DOCUMENT_ROOT'] . "/include/functions-email.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/functions-ws.php";

function getDadosLogin() {

/* Declarando as variáveis globais */
    $msg = '';
    $error = false;
    $status = null;

/* Validando o usuário */
    if (isset($_POST["usuario"]) && $_POST["usuario"] != null) {
        $usuario = $_POST['usuario'];
        $usuario = strip_tags($usuario);
        $inpe = true;
        /*=if (verificaEmail($usuario) == 1) {
        $error = true;
        $msg .= "Usuário inválido! <em class='menor11'>(Invalid User!)</em><br />";
        }*/if (verificaTamanho($usuario, 50)) {
            $error = true;
            $msg .= "Usuário com tamanho maior que o permitido! <em class='menor11'>(User with size bigger than allowed!)</em><br />";
        }
    } else {
        $error = true;
        $msg .= "Usuário não foi informado! <em class='menor11'>(User was not entered!)</em><br />";
    }

/* Validando a senha */
    if (isset($_POST["senha"]) && $_POST["senha"] != null) {
        $senha = $_POST['senha'];
        //$senha = md5($senha);
        if (verificaTamanho($senha, 12)) {
            $error = true;
            $msg .= "Senha com tamanho maior que o permitido! <em class='menor11'>(Password with size bigger than allowed!)</em><br />";
        }
    } else {
        $error = true;
        $msg .= "Senha não foi informada! <em class='menor11'>(Password was not entered!)</em><br />";
    }

/* Validando o captcha */
    if (isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"] != null) {
        $recaptcha = $_POST["g-recaptcha-response"];
        $erroCP = verificaCaptcha($recaptcha);
        if ($erroCP == false) {
            $error = true;
            $msg .= "Captcha inválido! <em class='menor11'>(Invalid Captcha!)</em><br />";
        }
    } else {
        $error = true;
        $msg .= "Captcha não foi selecionado! <em class='menor11'>(Captcha was not selected!)</em><br />";
    }

/* Chama a função que realiza o login */
    if (!$error) {
        $status = login($usuario, $senha);
    } else {
        $status = (object) array('codigo' => '0', 'mensagem' => $msg);
    }
    return $status;

}

/* Função que realiza o login */
function login($usuario, $senha) {
    session_start();
    $login = WsFunctions::loginInstitucional($usuario, strtoupper(md5($senha)));
    $time = time() + 1200;

    if ($login) {
        if ($login->Pessoa->pess_id != null && $login->Pessoa->pess_id != "") {
            $url = "/acessoainformacao/institucional/agenda/index.php";
            
            $_SESSION['logado'] = true;
            $_SESSION["sessiontime"] = $time;
            
            //Declara o array de unidades
            $arrayUnidade = array();

            //declara a variável do nome do perfil da agenda e atribui o valor
            $perfil_agenda = $login->Perfis->Perfil->perfil_nome;
            //declara a variável do id da pessoa e atribui o valor
            $idPessoa = (int) $login->Pessoa->pess_id;

            $pess_nome = (string) $login->Pessoa->pess_nome;

            $unid_sigla;
            $unid_nome;           


            //se o perfil da agenda existir e for igual a perfil_agenda
            if ($perfil_agenda = 'perfil_agenda') {
                $pdo = conecta();

                if ($pdo != null) {
                    $query = "SELECT * FROM agenda_autoridades.acesso_informacao_perfil_unidade";

                    $result = executaSql($pdo, $query);
                    $result->execute();

                    while ($row = $result->fetch(PDO::FETCH_OBJ)) {

                        $unid_id = $row->unid_id;
                        $pessoa = $row->pess_id;

                        $autoridadeByUnidade = WsFunctions::getAutoridadebyUnidade($unid_id);
                        $titularDaUnidade = (string) $autoridadeByUnidade->Autoridade->pess_nome;
                        $unid_sigla = (string) $autoridadeByUnidade->Autoridade->unid_sigla;

                        $unidade = WsFunctions::getUnidadeBySigla($unid_sigla);	
                        $unid_nome = (string) $unidade->unid_nome;

                        $getAutoridade = WsFunctions::getAutoridadebyUnidade($unid_id);
                        foreach ($getAutoridade as $valor) {
                            $func_id = $valor->func_id;
                            if($func_id == "1"){
                                $titularDaUnidade = (string) $valor->pess_nome;
                            }
                        }
                        

                        $arrayAux = array(
                            'id' => $unid_id, 
                            'nome' => $pess_nome, 
                            'nomeUnidade' => $unid_nome, 
                            'sigla' => $unid_sigla, 
                            'autoridade' => $titularDaUnidade
                        );

                        if ($idPessoa == $pessoa) {
                            array_push($arrayUnidade, $arrayAux);
                        }

                    }

                    fecha($pdo, $result);

                }
            } 
            


            //adiciona o retorno do método getAutoridadeByPessoa à variáveil autoridade
            //$autoridade = WsFunctions::getAutoridadeByPessoa($idPessoa); 
            //id da Patrícia - 2484
            //id do diretor - 8017
            $autoridade = WsFunctions::getAutoridadeByPessoa($idPessoa); 
            if (isset($autoridade) && $autoridade != null) {
                //adiciona a unidade da autoridade à variável auto_unid_sigla
                $auto_unid_id = (string) $autoridade->Autoridade->unid_id;
                $auto_unid_sigla = (string) $autoridade->Autoridade->unid_sigla;
                $unidadeBySiglaDaAutoridade = WsFunctions::getUnidadeBySigla($auto_unid_sigla);
                $auto_unid_nome = (string) $unidadeBySiglaDaAutoridade->unid_nome;
                $auto_unid_pess_nome = "";
                //se a unidade for diferente de null
                if ($auto_unid_sigla != null) {
                    if($auto_unid_id == "1"){
                        $getAutoridade = WsFunctions::getAutoridadebyUnidade($auto_unid_id);
                        foreach ($getAutoridade as $valor) {
                            $func_id = $valor->func_id;
                            if($func_id == "1"){
                                $auto_unid_pess_nome = (string) $valor->pess_nome;
                            }
                        }
                    }else {
                        $auto_unid_pess_nome = (string) $unidadeBySiglaDaAutoridade->unid_nome;
                    }
                    //adiciona a unidade no array de unidades
                    array_push($arrayUnidade, array(
                        'id' => $auto_unid_id, 
                        'nome' => $auto_unid_pess_nome, 
                        'nomeUnidade' => $auto_unid_nome, 
                        'sigla' => $auto_unid_sigla, 
                        'autoridade' => $auto_unid_pess_nome)
                    );
                }
            }
            
            $substituto = WsFunctions::getSubstitutoByPessoa($idPessoa);
            if (isset($substituto) && $substituto != null) {
                $sub_unid_id = (string) $substituto->Substituto->unid_id;
                $sub_unid_sigla = (string) $substituto->Substituto->unid_sigla;
                $unidadeBySiglaDoSubstituto = WsFunctions::getUnidadeBySigla($sub_unid_sigla);
                $sub_unid_nome = (string) $unidadeBySiglaDoSubstituto->unid_nome;
                $sub_unid_pess_nome = (string) $substituto->Substituto->pess_nome;
                if (isset($sub_unid_id) && $sub_unid_id != null) {
                    $sub_autoridadeByUnidade = WsFunctions::getAutoridadebyUnidade($sub_unid_id);
                    $sub_titularDaUnidade = (string) $sub_autoridadeByUnidade->Autoridade->pess_nome;
                    //se a unidade for diferente de null
                    if ($auto_unid_sigla != null) {
                        //adiciona a unidade no array de unidades
                        array_push($arrayUnidade, array(
                            'id' => $sub_unid_id, 
                            'nome' => $sub_titularDaUnidade, 
                            'nomeUnidade' => $sub_unid_nome, 
                            'sigla' => $sub_unid_sigla, 
                            'autoridade' => $sub_unid_pess_nome)
                        );
                    }
                }
            }

            //Adicionar o array de unidades e o id da pessoa  à sessão 
            $_SESSION['unidades'] = $arrayUnidade;
            $_SESSION["idPessoa"] = $idPessoa;
            
            //Status

            $status = (object) array('codigo' => '1', 'redirect' => $url);
            header("Location: ".$url); exit;

        } else {
            session_destroy();
            $erro = "";
            foreach ($login as $e) {
                $erro .= $e . "<br />";
            }
            $status = (object) array('codigo' => '0', 'mensagem' => $erro);
        }
    } else {
        session_destroy();
        $status = (object) array('codigo' => '0', 'mensagem' => 'Serviço WS-Internet indisponível!');
    }

    return $status;
}
/*Funcção para destruir as sessões e deslogar */
function logout() {
    session_destroy();
    header("location:logout.php");
}
