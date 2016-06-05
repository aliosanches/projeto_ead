
function alertModal(title, text, fnLoad, utilizaConfirm, titleConfirm, 
					fnConfirm, utilizaCancel, titleCancel, fnCancel){

	var params = {
		title: title,
		text: text,
		fnLoad: fnLoad,
		utilizaConfirm: utilizaConfirm,
		titleConfirm: titleConfirm,
		fnConfirm: fnConfirm,
		utilizaCancel: utilizaCancel,
		titleCancel: titleCancel,
		fnCancel: fnCancel,
	}

	if(typeof title == 'object'){
		params = title;
	}

	params.title = (typeof params.title != "undefined" ? params.title : 'Alert'); 
	params.text = (typeof params.text != "undefined" ? params.text : '');
	params.fnLoad = (typeof params.fnLoad == "function" ? params.fnLoad : null);
	params.utilizaConfirm = (typeof params.utilizaConfirm != "undefined" ? params.utilizaConfirm : false);
	params.titleConfirm = (typeof params.titleConfirm != "undefined" ? params.titleConfirm : 'Confirmar');
	params.fnConfirm = (typeof params.fnConfirm == "function" ? params.fnConfirm : null);
	params.utilizaCancel = (typeof params.utilizaCancel != "undefined" ? params.utilizaCancel : true);
	params.titleCancel =  (typeof params.titleCancel != "undefined" ? params.titleCancel : 'Ok');
	params.fnCancel = (typeof params.fnCancel == "function" ? params.fnCancel : null);

	var modal_id = Util.gerar_hash();
	var modal = $('<div class="modal" modal-id="'+modal_id+'" role="dialog">');
	var dialog = $('<div class="modal-dialog">');
	var content = $('<div class="modal-content">');
	var header = $('<div class="modal-header">');
	var body = $('<div class="modal-body">');
	var footer = $('<div class="modal-footer">');		
	var btn_close = $('<button type="button" class="close" data-dismiss="modal">&times;</button>');
	var title = $('<h4 class="modal-title">');
	var btn_cancelar = $('<button type="button" class="btn btn-default">');
	var btn_confirmar = $('<button type="button" class="btn btn-primary">');
	

	content.append(header);
	content.append(body);
	content.append(footer);
	dialog.append(content);
	modal.append(dialog);
	modal.appendTo($('.container'));

	btn_close.unbind('click');
	btn_close.click(function(e){

		if(params.utilizaCancel == true){
			if(typeof params.fnCancel == 'function'){
				params.fnCancel(modal_id, modal, e);
			}
		}

		modal.remove();
	});
	
	title.text(params.title);
	header.append(btn_close);
	header.append(title);

	body.html(params.text);

	if(params.utilizaConfirm == true){
		btn_confirmar.html(params.titleConfirm);
		btn_confirmar.unbind('click');
		btn_confirmar.click(function(e){

			if(typeof params.fnConfirm == 'function'){
				params.fnConfirm(modal_id, modal, e);
			}
			modal.remove();
		});

		footer.append(btn_confirmar);
	}

	if(params.utilizaCancel == true){
		btn_cancelar.html(params.titleCancel);
		btn_cancelar.unbind('click');
		btn_cancelar.click(function(e){

			if(typeof params.fnCancel == 'function'){
				params.fnCancel(modal_id, modal, e);
			}

			modal.remove();

		});

		footer.append(btn_cancelar);
	}

	if(typeof params.fnLoad == 'function'){
		params.fnLoad(modal_id, modal);
	}

}