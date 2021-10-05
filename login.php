<?php 

error_reporting(E_ALL);
ini_set("display_errors", 1);

include($_SERVER['DOCUMENT_ROOT']."/login_institucional/include/functions-login.php"); 
include($_SERVER['DOCUMENT_ROOT']."/include/functions-validacoes.php");

$status = getDadosLogin();


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" dir="ltr">

<head>
	<title>INPE - Instituto Nacional de Pesquisas Espaciais</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/img/favicon.png" />

    <link media="screen" href="/css/plone.css" type="text/css" rel="stylesheet" id="plone-css" />    
    <link media="all" href="/css/main.css" type="text/css" rel="stylesheet" id="main-css" />  
    <link media="all" href="/css/style.css" type="text/css" rel="stylesheet" id="style-css" />
        
    <link media="all" href="/css/css-intranet-inpe.css" rel="stylesheet" id="intranet-css" /> 
    <link media="all" href="/css/css-menu.css" rel="stylesheet" id="menu-css" />    
    
    <!-- CONTRASTE -->
    <link media="all" href="/css/css-intranet-inpe-contraste.css" rel="stylesheet" id="intranet-css-contraste" /> 
    <link media="all" href="/css/css-menu-contraste.css" rel="stylesheet" id="menu-css-contraste" /> 
    
    
	<script src="/js/jquery/jquery-1.9.1.js" type="application/javascript"></script>  
	<script src="/js/jquery/jquery.cookie.js" type="application/javascript"></script>    
       
    <script src="/js/functions.js" type="application/javascript"></script>
    
       
    
</head>

<body>
	<!-- TOPO -->    
	<?php include($_SERVER['DOCUMENT_ROOT']."/acessoainformacao/institucional/agenda/topo.php"); ?>
    
    
    <!-- CONTEUDO -->
    <div id="main" role="main">
    <div id="plone-content">

        <div id="portal-columns" class="row">
        
            

            <!-- Conteudo -->
            <div id="portal-column-content"  class="cell width-full position-0">
            
                <div id="main-content">    
                    <div id="content">
                    
                     <div class="documentByLine right">
                        <a href="/acessoainformacao/institucional/agenda/" title="voltar para Login">&laquo; voltar para Login</a>                        
                     </div>
                    
                    	<h1 class="documentFirstHeading">Login</em></h1>
                        
                        <?php if ($status->codigo){ ?>
                                               
                        <strong class="sucesso">Login realizado com sucesso. </strong>
                        
                        <?php } else { ?>
                        
                        <strong class="alerta">Não foi possível realizar o Login. 
                        <br />
                        <?= $status->mensagem ?>
                        </strong>                        
                        
                        <?php } ?>
                        
                        <div class="clear"><!-- --></div>
                        
                         
					</div>
				</div>
			</div>
            <!-- Fim do Conteudo -->

            <div class="clear"><!-- --></div>
            <div id="voltar-topo"><a href="#topo" title="Acesse Voltar para o topo">Voltar para o topo</a></div>

               
		</div>
	</div>
	</div>
	<!-- FIM CONTEUDO -->
        
	<div class="clear"><!-- --></div>

<!-- Footer -->
	<?php include($_SERVER['DOCUMENT_ROOT']."/rodape.php"); ?>
<!-- /Footer-->

</body>  
</html>
