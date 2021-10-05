$(document).ready(function () {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        views: {
            month: {
                titleFormat: 'MMMM YYYY',
                timeFormat: 'HH:mm',
            },
            agendaWeek: {
                listDayAltFormat: 'DD/MM/YYYY'
            },
            agendaDay: {
                timeFormat: 'HH:mm'
            },
            day: {
                timeFormat: 'HH:mm'
            },
            agenda: {
                eventLimit: 2
            }
        },
        axisFormat: 'HH:MM',
        locale: 'pt-br',
        viewRender: function (event, element, view) {
            if (logado == true) {
                $('.fc-day').css('postion', 'relative')
                $('td').css('cursor', 'pointer')
                $('.fc-day').html('<a href="#dialog" name="modal" style="float:right;position:absolute;margin-bottom:2%;margin-right:2px;"><i class="fas fa-edit"></i></a>')
            }
        },
        eventRender: function (event, element) {

            var dataInicio = moment(event.start._i).format('DD/MM/YYYY')
            var horaInicio = ""
            horaInicio = moment(event.start._i).format('HH:mm')
           
            let horaTermino = ""
            if (event.classname == 'event-single') {
                if (event.end) {
                    //horaTermino = event.endhoratermino.substring(5, -3)
                    horaTermino = moment(event.end._i).format('HH:mm')
                }
                if (horaTermino != "") {
                    horaTermino = " até " + horaTermino
                    return $('<a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable" style="color:#ffffff;"><div class="fc-content"><span class="fc-title">' + event.title + '</span> ' + horaInicio + horaTermino + '</div></a>');
                    
                    //horaTermino = ""
                }
                if(horaInicio == "00:00"){
                    return $('<a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable" style="color:#ffffff;"><div class="fc-content"><span class="fc-title">' + event.title + '</span></div></a>');
                }
    
                return $('<a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable" style="color:#ffffff;"><div class="fc-content"><span class="fc-title">' + event.title + '</span> ' + horaInicio + horaTermino + '</div></a>');
            } else {
                let dataTermino = moment(event.end._i).format('DD/MM/YYYY')
                let horaTermino = moment(event.end._i).format('HH:mm')
                //let horaTermino = event.endhoratermino.substring(5, -3)
                return $('<a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable" style="color: rgb(255, 255, 255); background-color: #316886; border-color: rgb(153, 153, 153);"><div class="fc-content"><span class="fc-title">' + event.title + ':</span> ' + dataInicio + ' às ' + horaInicio + ' até ' + dataTermino + ' às ' + horaTermino + '</div></a>');
            }
        },
        selectable: true,
        selectHelper: true,
        editable: true,
        showNonCurrentDates: false,
        displayEventEnd: false,
        dayClick: function (event, element, view) {
            if (logado == true) {
                let dataInicio = moment(event).format('DD/MM/YYYY')
                let dataInicioEn = moment(event).format('YYYY-MM-DD')
                sessionStorage.setItem('data', dataInicio)
                sessionStorage.setItem('dataEn', dataInicioEn)

                let unidade = $('#unidades').val()
                let autoridade = $('#titulo').text()

                console.log(unidade)


                if (unidade === "all" || unidade === null) {
                    console.log('entrou no if')
                    $('span[for=unidades]').html('Campo Obrigatório')
                    $('span[for=unidades]').addClass('txVermelho');
                    $('span[for=unidades]').addClass('menor11')
                    $('span[for=unidades]').addClass('right')
                    $('span[for=unidades]').css('font-style', 'italic')
                    $('select[name=unidades]').addClass('error')
                } else {
                    let form = document.getElementById('formAgenda')
                    $('input[id=diaConsulta]').val(dataInicio)
                    $('input[id=unidade]').val(unidade)
                    $('input[id=autoridade]').val(autoridade)
                    form.submit()
                }
            }

        },
        eventClick: function (event, element, view) {

            $('#start-hora-fim').html("")

            let dataInicio = moment(event.start._i).format('DD/MM/YYYY')
            let horaInicio = moment(event.start._i).format('HH:mm')

            if (event.classname == 'event-single') {

                let horaTermino = ""
                if (event.end) {
                    horaTermino = moment(event.end._i).format('HH:mm')
                    //horaTermino = event.endhoratermino.substring(5, -3)
                }


                $('#texto-fim').css('display', 'none')

                if (horaTermino == "01:00") {
                    horaTermino = ""
                    $('#start-hora-fim').html("")
                } else {
                    if (horaTermino != "") {
                        horaTermino = " até " + horaTermino
                        $('#start-hora-fim').html(horaTermino)
                    }
                }
            } else {

            }

            if (event.classname == 'event-period') {
                let dataTermino = moment(event.end._i).format('DD/MM/YYYY')
                let horaTermino = moment(event.end._i).format('HH:mm')
                //let horaTermino = event.endhoratermino.substring(5, -3)

                $('#texto-fim').css('display', 'block')
                $('#modal-data-end').html(dataTermino)
                $('#modal-hora-end').html(horaTermino)

                horaTermino = ""
                $('#start-hora-fim').html("")
            }


            $('#fullModal').modal()
            $('#modal-title').html(event.title)
            $('#modal-title').css('text-align', 'justify')
            $('.fc-title').css('text-align', 'justify')
            $('#modal-data-start').html(dataInicio)
            $('#modal-hora-start').html(horaInicio)
            if(horaInicio == "00:00"){
                $('#modal-hora-start').html("")
            }

            if (event.description) {
                $('#modal-description-title').html('Descrição:')
            }
            $('#modal-description').html(event.description)
            $('#modal-form-evento').css('z-index', '-1')
            $('#modal-form-evento').css('display', 'none')
            $('#modal-form-evento').modal('hide')

        },
        events: 'include/eventos.php',
        rendering: 'background',
        eventTextColor: '#ffffff',
        timeFormat: 'HH:mm',
        displayEventTime: true
    })

    $(document).ready(function (e) {
        if (logado == false) {
            $.ajax({
                type: "POST",
                url: 'include/eventos.php',
                data: 'unidade=' + 1,
                success: function (data) {
                    let eventos = data
                    eventos.forEach(insereNoCalendario)
                }
            })
        } else {

            let url = window.location.search.replace("?", "");
            let items = url.split("unidade=");
            let unidade = items[1]

            $("#unidades option").filter(function () {
                return $(this).val() == unidade;
            }).prop("selected", true);

            getEventos(unidade)

        }

    })

    const insereNoCalendario = evento => {
        if (evento) $("#calendar").fullCalendar('renderEvent', evento, true)
    }

    $('#unidades').click(function (e) { 
        e.preventDefault();
        $('#unidades option[value=all]').attr('disabled','disabled')
    });

    $('#unidades').change(function (e) {
        e.preventDefault()
        $("#calendar").fullCalendar('removeEvents')
        let unidade = $(this).val()
        getEventos(unidade)
    })

    function getEventos(unidade){
        let texto = $("#unidades option:selected").text()
        $.ajax({
            type: "POST",
            url: 'include/eventos.php',
            data: 'unidade=' + unidade,
            success: function (data) {
                $('span[for=unidades]').html('')
                $('span[for=unidades]').removeClass('txVermelho');
                $('span[for=unidades]').removeClass('menor11')
                $('span[for=unidades]').removeClass('right')
                $('select[name=unidades]').removeClass('error')
                const eventos = data
                eventos.forEach(insereNoCalendario)
                $("#titulo").html(texto);
                /*if (texto == "Patricia Marciano Leite: DIR") {
                    texto = "(Diretor do INPE) Darcton Policarpo Damião"
                    $("#titulo").html(texto);
                } else {
                    $("#titulo").html(texto);
                }*/
                $('span[for=unidades]').empty();
            }
        })
    }

    $('#logout').click(function (e) {
        e.preventDefault();
        $('#modalLogout').modal()
    });

    $('#btn_logout').click(function (e) {
        e.preventDefault();
        window.location.href = "logout.php";
    });

    /*$('#login').click(function (e) {
        let usuario = $("input[name=usuario]").val()
        let n = usuario.indexOf("@");

        if (n > -1) {
            console.log('tem arroba')
            $('.campo span').html('Formato Inválido')
            $('.campo span').css('color', 'red')
            $('.campo span').css('font-style', 'italic')
            $('.campo span').css('margin-left', '10px')
        } else {
            $('.campo span').html('')
        }
    });*/


})

