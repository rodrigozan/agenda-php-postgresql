$(document).ready(function() {
	var $ = jQuery; 	
	
	var validacao = $('#addEvento').validate({
		ignore : [],
		onkeyup: function(element) {
			$(element).valid()
		},
		errorPlacement : function(label, element) {
			label.insertAfter(element);
		},
		rules : {
			"usuario" : {
				required: true,
				maxlength: 50,
			},
			"senha" : {
				required: true,
				maxlength: 12,
				minlength: 8,
			}
		},
		messages : {
			"usuario" : {
				required: "Campo é obrigatório!",
				maxlength: "Máximo de {0} caractéres!"
			},
			"senha" : {
				required: "Campo é obrigatório!",
				maxlength: "Máximo de {0} caractéres!"
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});

	/*$('#cf_send').on('submit' ,function(){
		if ($('#addEvento').valid()){	
			$('#addEvento').submit();		
			console.log('deu certo')
		} else {
			$('#addEvento').validate().focusInvalid();		
			console.log('deu ruim')
		}
	});*/
});

$(document).ready(function() {
	var $ = jQuery; 	
	
	var validacao = $('#editEvento').validate({
		ignore : [],
		onkeyup: function(element) {
			$(element).valid()
		},
		errorPlacement : function(label, element) {
			label.insertAfter(element);
		},
		rules : {
			"title" : {
				required: true,
				maxlength: 150,
			},
			"start" : {
				required: true,
				maxlength: 10,
			}
		},
		messages : {
			"title" : {
				required: "Campo é obrigatório!",
				maxlength: "Máximo de {0} caractéres!"
			},
			"start" : {
				required: "Campo é obrigatório!",
				maxlength: "Máximo de {0} caractéres!"
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});

	$('#cf_send').on('submit' ,function(){
		if ($('#editEvento').valid()){	
			$('#editEvento').submit();		
			console.log('deu certo')
		} else {
			$('#editEvento').validate().focusInvalid();		
			console.log('deu ruim')
		}
	});
});