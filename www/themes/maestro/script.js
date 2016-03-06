$( document ).ready(function() {
    $('.modal-close').click(function() {
		closeModal();
	});
});

function showHide(id){
	$('#' + id).toggle();
}


function closeModal(reload) {
	$('#modal').hide();
	$('.modal-overlay').hide();
	if(reload) window.location.reload();
}

function modal(path,params) {
	$.post(path, params)
	.done(function( data ) {
		$('#modal .modal-body').html(data);
		$('#modal').show();
		$('.modal-overlay').show();
	});
}


function saveForm(id,path) {
	$.post(path, $('#' + id).serialize())
	.done(function( data ) {
		$('#' + id + '_savemsg').show(500);
		setTimeout(function() {
			$('#' + id + '_savemsg').hide(500); 
			//closeModal(true);
			var obj = jQuery.parseJSON(data);
			if(obj.redirect) {
			//	window.location = obj.redirect;
			}
		},3000);	
	});
}

function sendGetForm(id,path) {
	$.get(path, $('#' + id).serialize())
		.done(function( data ) {
			$('#' + id + '_savemsg').show(500);
			setTimeout(function() {
				$('#' + id + '_savemsg').hide(500); 
				var obj = jQuery.parseJSON(data);
				if(obj.redirect) {
					window.location = obj.redirect;
				}
			},3000);	
	});
}


function getNic(id){
	var nicE = new nicEditors.findEditor(id);
	$('#' + id).val(nicE.getContent());
}

function conf(action, text) {
	if(confirm(text)){
		$.get(action + '?ajax=1')
		.done(function( data ) {
			var obj = jQuery.parseJSON(data);
			if(obj.redirect) {
				if(obj.redirect == 'reload') {
					window.location.reload();
				} else {
					window.location = obj.redirect;
				}
			}
		});
	}
}