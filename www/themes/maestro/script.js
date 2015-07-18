function showHide(id){
	$('#' + id).toggle();
}


function saveForm(id,path) {
	$.post(path + '?ajax=1', $('#' + id).serialize())
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