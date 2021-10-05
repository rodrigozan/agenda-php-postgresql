<?php
include($_SERVER['DOCUMENT_ROOT']."/include/functions.php");
include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/institucional/agenda/include/functions-agenda.php";
include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/institucional/agenda/include/login-inpe.php";

ini_set('display_errors',0);
ini_set('display_startup_erros',0);
error_reporting(E_ALL);

getDadosLogin()

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" dir="ltr">
<head>
	<title>INPE - Portal de Acesso à Informação</title>
	<meta http-equiv="Content-Type" content="text/html charset=utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
	<link rel="apple-touch-icon" href="/img/favicon.png" />

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">

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
    
    <!-- BOOTSTRAP3 -->
    <link rel="stylesheet" type="text/css" href="/js/bootstrap3/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/js/bootstrap3/css/bootstrap-theme.css" />
	<script type="text/javascript" src="/js/bootstrap3/js/bootstrap.min.js"></script>
    
	<!-- BOOTSTRAP - DATEPICKER -->
	<script src="/js/bootstrap-datepicker-1.5.1/js/bootstrap-datepicker.min.js"></script>
	<link href="/js/bootstrap-datepicker-1.5.1/css/bootstrap-datepicker3.css" rel="stylesheet"/>
    <script src="/js/bootstrap-datepicker-1.5.1/locales/bootstrap-datepicker.pt-BR.min.js"></script>    
	<script src="/js/functions.js" type="application/javascript"></script>
	<!-- Bootstrap - Fullcalendar - Style -->
	<link rel="stylesheet" href="assets/fullcalendar/dist/fullcalendar.css">

    <script src='assets/moment/min/moment.min.js'></script>
    <script src='assets/fullcalendar/dist/fullcalendar.js'></script>
	<script src='assets/fullcalendar/dist/locale-all.js'></script>

	<!-- Validação -->
	<script src="/js/jquery/jquery.validate.js" type="application/javascript"></script>
	<script type="text/javascript" src="/js/jquery/jquery.validate-additional-methods.js"></script>
	<script type="text/javascript" src="/js/forms/functions-validacoes.js"></script>
	
	<script src="assets/js/validacao.js"></script>
	
	<link rel="stylesheet" href="assets/css/style.css">

	<script>
		var logado;
		<?php if ($logado == false) {?>
			logado = false;
		<?php } else { ?>
			logado = true;
		<?php } ?>
	</script>

	<script src="assets/js/functions-agenda.js"></script>


</head>

<body>
	<!-- TOPO -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . "/topo.php"?>


	<!-- CONTEUDO -->
	<div id="main" role="main">
		<div id="plone-content">

			<div id="portal-columns" class="row">

				<!-- RASTRO -->
				<div id="viewlet-above-content">
					<div id="portal-breadcrumbs">
						<span id="breadcrumbs-you-are-here">
							Você está aqui:
							<span>
								<?=rastro()?>
							</span>
						</span>
						<span class="editar-agenda">
						<?php if ($logado == false) {?>
							<button type="button" data-toggle="modal" data-target="#modalLogin">Editar Agenda</a>
						<?php } else {
							echo "<a id=\"logout\" href=\"logout.php\" type=\"button\">sair <i class=\"fas fa-power-off\"></i></a>";
						} ?>
						</span>
					</div>
				</div>

				<!-- Column 1 - MENU -->
				<?php if ($logado == false) {
					include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/menu.php";
				} ?>

				<!-- Conteudo -->
				<?php if ($logado == false) {?>
					<div id="portal-column-content" class="cell width-3:4 position-1:4">
				<?php } else { ?>
					<div id="portal-column-content" class="cell width-full position-0">
				<?php } ?>

					<div id="main-content">
						<div id="content">

							<h1 class="documentFirstHeading">Acesso à Informação</h1>

							<div class="documentByLine">
								<span class="documentAuthor">Publicado Por: <a href="/" title="Acesse Publicado Por">INPE</a></span>
								<?=dataModificacao()?>
							</div>
							
							<?php if ($logado == true) {?>
								<h2 id="titulo"></h2>
						
								<strong>Área de Edição <span class="txVermelho">(Acesso Restrito)</span></strong>
								<br />
								Você está na área de "Edição" das agendas das Coordenações. Para inserir um evento, 
								é necessário selecionar a autoridade e clicar no dia correspondente.
							
								<br /><br />
								<label for="assunto"><i class="txVermelho">*</i> Autoridades disponíveis para o seu perfil de edição:</label>
								<i class="menor11 txVermelho right">* Campos Obrigatórios!</i>
								<select name="unidades" id="unidades">
									<option value="all">Selecione a Autoridade</option>
									<?= getUnidades($unidades) ?>
								</select>
								<span for="unidades"></span>
							<?php } else { ?>
								<h2 id="titulo">Agenda do Diretor do INPE</h2>
								<!--label for="assunto">Autoridades disponíveis para consulta:</label-->
							<?php } ?>
							<div class="clear"><!-- --></div>
							<br /><br />
                            <div id="calendar"></div>

                            <div class="clear"></div>

							<form id="formAgenda" action="eventos.php" method="POST" style="display:none">
								<input type="text" name="diaConsulta" id="diaConsulta">
								<input type="number" name="unidade" id="unidade">
								<input type="text" name="autoridade" id="autoridade">
								<input type="text" name="idPessoa" id="idPessoa" value="<?php echo $idPessoa; ?>">
							</form>


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
	<?php include $_SERVER['DOCUMENT_ROOT'] . "/rodape.php"?>
	<!-- /Footer-->

	<!-- Modal Calendário -->
	<div class="modal fade" id="fullModal" tabindex="-1" role="dialog" aria-labelledby="fullModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h3 class="modal-title" id="modal-title"></h3>
                </div>

				<div>
                <div class="modal-body">
					<p>Início: <span id="modal-data-start"></span> - <span id="modal-hora-start"></span> <span id="start-hora-fim"></span></p>
						
					<p id="texto-fim">Fim: <span id="modal-data-end"></span> - <span id="modal-hora-end"></span></p>
					<!--h5 id="modal-description-title"></h5-->
					<p id="modal-description"></p>
				</div>
				<!--p id="modal-body" class="modal-body"></p-->
                </div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-xs searchButton" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /Modal Calendário -->

	<?php include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/institucional/agenda/modal-login.php" ?>
	

    <noscript>
    Habilite o JavaScript do navegador para que a busca de colaboradores funcione corretamente.
    </noscript>

</body>

</html>