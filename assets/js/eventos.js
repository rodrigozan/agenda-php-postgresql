$(document).ready(function (){
			
    $('input.data').datepicker({
        format: "dd/mm/yyyy",
        clearBtn: true,
        language: "pt-BR",
        orientation: "top auto",
        todayHighlight: true
    })

    let dataAtual = $('#start').val()

    $('input#end').datepicker(
        'setStartDate', 
        dataAtual,
        {
            startDate: '-1y -1m',
        }
    )

    $('span[for=horainicio]').html('Hora inválida')
    $('span[for=horafim]').html('Hora inválida')

})

/*$(document).on("click", ".cadastrar", function(e){
    e.preventDefault()
    console.log('clicou no botão')

    let end = $('#end').val()
    let horafim = $('input[name=horafim]').val()
    console.log(horafim)


    if(end != "" && horafim == ""){
        $('#horafim i').html("Campo Obrigatório");
        $('#horafim i').css("margin-right", "20px");
        $('input[name=horafim]').addClass("error");	
    }
    
})*/

$(document).on("click", ".btn_form", function(e){
    //e.preventDefault()
    let start = $('#start').val()
    $('input[name=diaConsulta]').attr('value', start)    

    let classname = $('input[name=classname]').val()

    if(classname == "event-single"){
        $('#end').val(start)
    }
})

$(document).on("click", "#disponibilizar", function(){
    $('input[name=visivel]').attr('value', '1')
    //let title = $("input[name=title]").text()

    //$('strong').text(title)
})

$(document).on("click", ".editar", function(e){
    e.preventDefault()
    //declarando as variáveis e atribuindo o valor das td's
    let id = $(this).parent().parent().find(".id").text()
    let title = $(this).parent().parent().find(".title").text()
    let idpessoa = $(this).parent().parent().find(".title").text()
    let description = $(this).parent().parent().find(".description").text()
    let start = $(this).parent().parent().find(".start").text()
    let horainicio = $(this).parent().parent().find(".horainicio").text()
    let horafim = $(this).parent().parent().find(".horafim").text()
    let end = $(this).parent().parent().find(".end").text()
    let classname = $(this).parent().parent().find(".classname").text()
    let visivel = $(this).parent().parent().find(".visivel").text()

    start = start.substr(0, 10)
    end = end.substr(0, 10)

    if(start == end){
        end = ""
    }
    
    let diaConsulta = start

    if(horainicio == "01:00"){
        horainicio = ""
    }
    if(horafim == "01:00"){
        horafim = ""
    }
    $("#disponibilizar").attr('style', 'display:none')

    if(classname == "event-period"){
        $("#eventoPeriodo").prop( "checked", true )
        $('#startHoraTermino div').removeClass('campo')
        $('.startHoraTermino').attr('style', 'display:none')
        $('#dataEventoPeriodo').attr('style', 'display:block')
        $('input[name=classname]').val('event-period')
        $('#classname').val('event-period')
        $('.startHoraTermino input').attr('name', '')
        $('#endHora input').attr('name', 'horafim')
    }else {
        $("#eventoUnico").prop( "checked", true )
        $('#startHoraTermino div').addClass('campo')
        $('.startHoraTermino').attr('style', 'display:block')
        $('#dataEventoPeriodo').attr('style', 'display:none')
        $('input[name=classname]').val('event-single')
        $('#classname').val('event-single')
        $('.startHoraTermino input').attr('name', 'horafim')
        $('#endHora input').attr('name', '')
    }

    //atribui os valores das variáveis aos campos do formulário
    $('input[name=diaConsulta').attr('value', diaConsulta)
    $('#id').attr('value', id)
    $('#idpessoa').attr('value', idpessoa)
    $('#acao').attr('value', 'atualiza')
    $('input[id=title]').val(title)
    $('textarea[name=description]').text(description)
    $('input[id=start]').val(start)
    $('input[name=horainicio]').val(horainicio)
    $('input[name=horafim]').val(horafim)
    $('input[name=end_horafim]').val(horafim)
    $('input[id=end]').val(end)
    $('input[name=classname]').val(classname)
    $('input[name=visivel]').val(visivel)
    $('button[type="submit"]').text('Confirmar')
    $('button[type="submit"]').attr('id', 'editar')

    $('input[id=title]').focus()

})

$(document).on("click", ".deletar", function(){
    //declarando as variáveis e atribuindo o valor das td's
    let id = $(this).parent().parent().find(".id").text()
    let title = $(this).parent().parent().find(".title").text()

    //chamando o modal do formulário
    $('#deleteModal').modal()

    //atribui os valores das variáveis aos campos do formulário
    $('input[id=id]').attr('value', id)
    $('strong').text(title)
})

$(document).on("click", ".disponibilizar", function(){
    //declarando as variáveis e atribuindo o valor das td's
    let id = $(this).parent().parent().find(".id").text()
    let title = $(this).parent().parent().find(".title").text()
    let unidade = $(this).parent().parent().find(".unidade").text()

    //chamando o modal do formulário
    $('#disponibilizaModal').modal()

    //atribui os valores das variáveis aos campos do formulário
    $('input[id=id]').attr('value', id)
    $('input[id=unidade3]').attr('value', unidade)
    $('strong').text(title)
})

$(document).on("click", ".disponibilizado", function(){
    //declarando as variáveis e atribuindo o valor das td's
    let id = $(this).parent().parent().find(".id").text()
    let title = $(this).parent().parent().find(".title").text()
    let unidade = $(this).parent().parent().find(".unidade").text()

    //chamando o modal do formulário
    $('#indisponibilizaModal').modal()

    //atribui os valores das variáveis aos campos do formulário
    $('input[id=unidade3]').attr('value', unidade)
    $('input[id=id]').attr('value', id)
    $('strong').text(title)
})

function mascaraGenerica(evt, campo, padrao) {  
    //testa a tecla pressionada pelo usuario  
    var charCode = (evt.which) ? evt.which : evt.keyCode;  
    if (charCode == 8) return true; //tecla backspace permitida  
    if (charCode != 46 && (charCode < 48 || charCode > 57)) return false; //apenas numeros            
    campo.maxLength = padrao.length; //Define dinamicamente o tamanho maximo do campo de acordo com o padrao fornecido  
    //aplica a mascara de acordo com o padrao fornecido  
    entrada = campo.value;  
    if (padrao.length > entrada.length && padrao.charAt(entrada.length) != '#') {  
         campo.value = entrada + padrao.charAt(entrada.length);                 
    }  
    return true;  
}  

