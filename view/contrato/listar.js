define([],function(){

	var ListContrato = function(html_id){
		"use strict";

		var main = this;
		this.html_id = html_id;
		$('.title_sistema').text('Projeto EAD - Contratos');
		this.dialog = $('[view_html_id="'+this.html_id+'"]');
		this.onclose = null;
		this.onsave = null;

		//elementos
		this.btn_novo = this.dialog.find('.btn_novo');
		this.tbl_contrato = this.dialog.find('.tbl_contrato');
		this.btn_refresh = this.dialog.find('.btn_refresh');

		//metodos
		this.show = function(){
			main.listar();
		}

		this.listar = function(){

			var table_body = main.tbl_contrato.find('tbody');
			var template_row = $(table_body).find('[template-row="contrato"]');

			var add_row = function(i, item){

				var new_row = template_row.clone();
				new_row.css('display', '')
				       .removeAttr('template-row')
				       .addClass('rows')
				       .attr('id', item.id);

				var field_cliente = $(new_row.find('[template-field="cliente"]'));
				field_cliente.text(item.cliente_nome);

				var field_servico = $(new_row.find('[template-field="servico"]'));
				field_servico.text(item.servico_nome);

				var field_dias = $(new_row.find('[template-field="dias"]'));
				field_dias.text(item.dias);

				new_row.appendTo(table_body);
			}


			Util.get
			(
				'contrato/listar', 
				null, 
				function(response){
					main.tbl_contrato.find('.rows').remove();
					for (var i = 0; i < response.length; i++) {
						var item = response[i];
						add_row(i, item);
					}
				}
			);
		}

		this.unload = function(){
			main.dialog.remove();
			if(main.onclose && typeof main.onclose == 'function'){
				main.onclose();
			}
		}

		//eventos
		this.btn_novo.unbind('click');
		this.btn_novo.click(function(e){
			Util.load_pag(`contrato/detalhes`, function(id, obj){
				obj.show();
				var onclose = function(){
					main.listar();
				};
				obj.onclose = onclose;
			});
		});

		this.btn_refresh.unbind('click');
		this.btn_refresh.click(function(e){
			main.listar();
		});
		
		
	};

	return ListContrato;

});