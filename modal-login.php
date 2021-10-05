<!-- Modal Login -->
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLogin" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header" style="text-align:center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h2>Área Restrita</h2>
                <p>Este serviço está disponível apenas para pessoas autorizadas</p>
            </div>

            <div class="modal-body modal-atencao">
                <div id="main-content">
                    <div id="content">

                        <div class="margin-auto" style="text-align:left">

                            <i class="menor11 txVermelho right nomargin">
                            * Campos Obrigatórios! <em class="menor11">(* Required fields!)</em>
                            </i>

                            <form id="logar-login" class="cadastro" action="/acessoainformacao/institucional/agenda/" method="post" accept-charset="utf-8" name="logar-login" >
                                <fieldset>
                                    <legend>Login</legend>
                                    <i class="obs">Realize o Login para acessar.</i>

                                    <label for="usuario"><i class="menor11 txVermelho">*</i> Login Institucional: </label>
                                    <input type="text" id="usuario" name="usuario" value="" maxlength="50" placeholder="Login Institucional" />
                                    <br />

                                    <label for="senha"><i class="menor11 txVermelho">*</i> Senha: </label>
                                    <input type="password" id="senha" name="senha" value="" maxlength="12" />

                                    <br />

                                    <label><i class="menor11 txVermelho">*</i> Campo de Verificação de Segurança: </label>
                                    <input type="hidden" name="captcha" />
                                    <script src="/js/functions-captcha.js"></script>
                                    <div id="div-captcha-google"></div>

                                    <button id="login" type="submit" name="send" class="searchButton maior">Login</button>
                                </fieldset>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="clear"><!-- --></div>
            </div>

            <div class="modal-footer">
                <div style="text-align:center">
                <h4>ATENÇÃO</h4>
                <p>Você está entrando num sistema do Governo Federal.</p>
                <p>
                    Este sistema é para uso exclusivo de usuários autorizados. Todas as atividades realizadas
                    neste sistema serão passíveis de monitorização e gravação; portanto, aquele que se utilizar
                    deste sistema estará concordando com este procedimento. Quaisquer evidências de
                    atividades ilícitas sujeitarão os respectivos responsáveis às penalidades legais cabíveis.
                    Se você não concorda com os termos acima, por favor cancele a sua conexão.<br>
                </p>
                </div>

                <button type="button" class="btn btn-primary btn-xs searchButton" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>




	
