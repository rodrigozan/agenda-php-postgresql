<?php include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/institucional/agenda/include/functions-agenda.php";?>

<?php

ini_set('display_errors', 0);
ini_set('display_startup_erros', 0);
error_reporting(E_ALL);

session_start();

$redirect = "index.php";


$unidade = $_POST["unidade"];
$autoridade = $_POST["autoridade"];
$diaConsulta = $_POST["diaConsulta"];

if (!isset($_SESSION)) {
    // Destrói a sessão por segurança
    session_destroy();
    header("location:$redirect");
}

$logado = $_SESSION['logado'];

if (isset($logado)) {
    $valid = "";
    $erro = null;
    $sucesso = "";

    function mensagem($texto, $redireciona)
    {
        echo "<div class=\"boxAzul padding center\"><strong>Evento " . $texto . "do com sucesso!</strong><br>" . $redireciona . "</div>";
    }

    function acao()
    {
        $acao = "";
        if (isset($_POST['acao'])) {
            $acao = $_POST['acao'];
        }

        $link = "<a href='index.php?unidade=". $_POST['unidade']."'>Confira o evento na agenda clicando aqui</a>";

        if ($acao == 'cadastra') {
            validaEvento();
            if ($_POST["visivel"] == 1) {
                mensagem('disponibiliza', $link);
            } else {
                mensagem($acao, "");
            }

        }

        if ($acao == 'deleta') {
            validaDeleteEvento();
            mensagem($acao, $redireciona = "");
        }

        if ($acao == 'atualiza') {
            validaUpdateEvento();
            mensagem($acao, $redireciona = "");
        }

        if ($acao == 'disponibiliza') {
            validaDisponibilizaEvento();
            mensagem($acao, $link);
        }

        if ($acao == 'indisponibiliza') {
            validaIndisponibilizaEvento();
            mensagem($acao, $redireciona = "");
        }
    }

} else {
    // Destrói a sessão por segurança
    session_destroy();
    header("location:$redirect");
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" dir="ltr">
<head>
	<title>INPE - Portal de Acesso à Informação</title>
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

    <!-- BOOTSTRAP3 -->
    <link rel="stylesheet" type="text/css" href="/js/bootstrap3/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/js/bootstrap3/css/bootstrap-theme.css" />
	<script type="text/javascript" src="/js/bootstrap3/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="assets/css/style.css">

	<!-- BOOTSTRAP - DATEPICKER -->
	<script src='assets/moment/min/moment.min.js'></script>
	<script src="/js/bootstrap-datepicker-1.5.1/js/bootstrap-datepicker.min.js"></script>
	<link href="/js/bootstrap-datepicker-1.5.1/css/bootstrap-datepicker3.css" rel="stylesheet"/>
    <script src="/js/bootstrap-datepicker-1.5.1/locales/bootstrap-datepicker.pt-BR.min.js"></script>
	<script src="/js/functions.js" type="application/javascript"></script>

	<script src="assets/js/eventos.js"></script>

	<style>
		h2#titulo {
			color: #1a4096;
		}
	</style>


</head>

<body>
	<!-- TOPO -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . "/topo.php";?>


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
								<?=rastro();?>
                                Eventos
							</span>
						</span>
					</div>
				</div>


				<!-- Column 1 - MENU -->
				<?php include $_SERVER['DOCUMENT_ROOT'] . "/acessoainformacao/menu.php";?>

				<!-- Conteudo -->
				<div id="portal-column-content" class="cell width-3:4 position-1:4">

					<div id="main-content">
						<div id="content">

							<div class="documentByLine voltar"><a href="index.php?unidade=<?php echo $_POST["unidade"]; ?>" title="Voltar">« Voltar para a Agenda</a></div>
							<h1 class="documentFirstHeading">Acesso à Informação</h1>

							<div class="documentByLine">
								<span class="documentAuthor">Publicado Por: <a href="/" title="Acesse Publicado Por">INPE</a></span>
								<?=dataModificacao();?>
							</div>

							<h2 id="titulo"><?php echo $autoridade ?></h2>
							<?php 
								if ($erro == 0) {
									acao();
								} else {
									echo "<strong class=\"alerta\">Não foi possível cadastrar o evento. <br /><br /> $valid</strong>";
								}
							?>
							<p>
							Para cadastrar um novo evento, utilize o formulário abaixo.<br>
							Para conferir os eventos disponíveis no dia <?php echo $diaConsulta ?>, <a href="#listaDeEventos">clique aqui</a>.
							</p>
							<div class="clear"></div>
                            <i class="menor11 txVermelho right">* Campos Obrigatórios!</i>
                            <form id="addEvento" class="cadastro" action="eventos.php" method="post" accept-charset="utf-8" name="form" enctype="multipart/form-data">
                            	<fieldset>
									<legend>Adicionar Evento na Agenda</legend>
									<i class="obs">Preencha os dados corretamente</i>
									<div class="clear"></div>

									<input type="hidden" id="id" name="id">
									<input style="display:none" id="acao" name="acao" value="cadastra">
									<input style="display:none" type="text" id="novodiaConsulta" name="diaConsulta" value="<?php echo $diaConsulta ?>">
									<input type="hidden" id="unidade" name="unidade" value="<?=$unidade;?>">
									<input type="hidden" id="visivel" name="visivel">

									<label for="title"><i class="menor11 txVermelho">*</i> Título: </label>
									<input type="text" id="title" name="title" maxlength="100">
									<div class="clear"></div>

									<input type="radio" id="eventoUnico" name="tipoEvento" ckecked=true>
									<label for="eventoUnico" class="menor"> Evento de data única</label>
									<input type="radio" id="eventoPeriodo" name="tipoEvento">
									<label for="eventoPeriodo" class="menor"> Evento de período</label>

									<input style="display:none" id="className" name="className">

									<div class="clear"></div>

									<div id="dataEventos">
										<div class="campo">
											<label for="start">Início: </label>
											<input type="text" id="start" name="start" class="data">

											<div id="startHoraTermino">
												<div class="campo">
													<label for="startHoraInicio"> Hora do Início: </label>
													<input type="text" name="startHoraInicio" pattern="([01][0-9]|2[0-3]):[0-5][0-9]" placeholder="00:00">
												</div>
												<div class="campo startHoraTermino">
													<label for="startHoraTermino"> Hora do Término: </label>
													<input type="text" name="startHoraTermino" pattern="([01][0-9]|2[0-3]):[0-5][0-9]" placeholder="00:00">
												</div>
											</div>
										</div>

										<div class="campo" id="dataEventoPeriodo" style="display:none">
											<label for="end">Término: </label>
											<input type="text" id="end" name="end" class="data">
											<div id="endHoraInicio">
												<label for="endHoraTermino"> Hora do Término: </label>
												<input type="text" name="endHoraTermino" pattern="([01][0-9]|2[0-3]):[0-5][0-9]" placeholder="00:00">
											</div>
										</div>
									</div>

									<div class="clear"></div>

									<label for="description">Descrição: </label>
									<textarea name="description" id="description" cols="50" rows="5" maxlength="1000"></textarea>
									<div class="clear"></div>

									<div class="campo">
										<label><i class="menor11 txVermelho">*</i> Campo de Verificação de Segurança: </label>
										<input type="hidden" name="captcha" />
										<script src="/js/functions-captcha.js"></script>
										<div id="div-captcha-google"></div>
									</div>

									<div class="clear"></div>

									<button id="cf_send" type="submit" name="send" class="searchButton cadastrar">Salvar</button>
									<button id="disponibilizar" type="submit" name="send" class="searchButton">Disponibilizar</button>
								</fieldset>
                            </form>

							<div class="clear"><!-- --></div>
							<br /><br />

							<div class="documentByLine voltar"><a href="index.php?unidade=<?php echo $_POST["unidade"]; ?>" title="Voltar">« Voltar para a Agenda</a></div>

							<div class="clear"><!-- --></div>
							<br />

							<div id="listaDeEventos"></div>

							Para disponibilizar o evento na Agenda é necessário clicar no botão <button class='cinza'><span class='glyphicon glyphicon-ok' aria-hidden='true'></button> na linha do evento.

							<div class="clear"><!-- --></div>
							<br /><br />

							<table id="listaEventos" class="cinza borda">
								<caption>Lista de Eventos do dia <?php echo $diaConsulta ?></caption>
								<thead>
									<tr>
										<th>Título</th>
										<th>Descrição</th>
										<th>Data de Início</th>
										<th>Data de Término</th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tbEventos">
									<?php listaEventos($diaConsulta);?>
								</tbody>


							</table>




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
	<?php include $_SERVER['DOCUMENT_ROOT'] . "/rodape.php";?>
	<!-- /Footer-->

	<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
				<h4 class="modal-title">Excluir evento </h4>
			</div>
			<div class="modal-body">
				<p id="text-title">Confirma a exclusão do evento <strong></strong>?</p>
				<form id="deletaEvento" class="cadastro" action="eventos.php" method="post" accept-charset="utf-8" name="form" enctype="multipart/form-data">
					<input type="hidden" id="id" name="id">
					<input style="display:none" id="acao" name="acao" value="deleta">
					<input style="display:none" id="1diaConsulta" name="diaConsulta" value="<?=$diaConsulta;?>">
					<input type="hidden" id="unidade2" name="unidade" value="<?=$unidade;?>">
					<button id="cf_send" type="submit" name="send" class="btn btn-primary">Confirmar</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>

	<div id="disponibilizaModal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
				<h4 class="modal-title">Disponibilizar evento </h4>
			</div>
			<div class="modal-body">
				<p id="text-title">Deseja disponibilizar o evento <strong></strong>?</p>
				<form id="disponibiliza" class="cadastro" action="eventos.php" method="post" accept-charset="utf-8" name="form" enctype="multipart/form-data">
					<input type="hidden" id="id" name="id">
					<input style="display:none" id="acao" name="acao" value="disponibiliza">
					<input style="display:none" id="3diaConsulta" name="diaConsulta" value="<?=$diaConsulta;?>">
					<input type="hidden" id="unidade3" name="unidade">
					<button id="cf_send" type="submit" name="send" class="btn btn-primary">Confirmar</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>

	<div id="indisponibilizaModal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
				<h4 class="modal-title">Indisponibilizar evento </h4>
			</div>
			<div class="modal-body">
				<p id="text-title">Deseja indisponibilizar o evento <strong></strong>?</p>
				<form id="indisponibiliza" class="cadastro" action="eventos.php" method="post" accept-charset="utf-8" name="form" enctype="multipart/form-data">
					<input type="hidden" id="id" name="id">
					<input style="display:none" id="acao" name="acao" value="indisponibiliza">
					<input style="display:none" id="2diaConsulta" name="diaConsulta" value="<?=$diaConsulta;?>">
					<input type="hidden" id="unidade4" name="unidade" value="<?=$unidade;?>">
					<button id="cf_send" type="submit" name="send" class="btn btn-primary">Confirmar</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>

	<script>
		var dataGravada = sessionStorage.getItem('data');
		console.log(dataGravada);
		
		$('input[id=start]').attr('value', dataGravada);
		$('input[id=start]').val(dataGravada);
		$('#data').html(dataGravada);

		$(document).ready(function() {
			if ($.trim($('tbody').html()) == '') {
				$("thead").remove();
				$("tbody").remove();
				$("caption").remove();
				$("#listaEventos").prepend("<caption>Não há eventos cadastrados para o dia <?php echo $diaConsulta; ?></caption>");
				console.log("vazio");
			} else {
				console.log("tbody não está vazio");
			}

		})



	</script>
	<script src="/js/jquery/jquery.validate.js" type="application/javascript"></script>
	<script type="text/javascript" src="/js/jquery/jquery.validate-additional-methods.js"></script>
	<script type="text/javascript" src="/js/forms/functions-validacoes.js"></script>
	<script src="assets/js/validacao.js"></script>

	<noscript>
	Habilite o JavaScript do navegador para que a busca de notícias funcione corretamente.
	</noscript>

</body>
</html>
