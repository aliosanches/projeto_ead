define([],function(){

	var ListCliente = function(html_id){
		"use strict";

		var main = this;
		this.html_id = html_id;
		$('.title_sistema').text('Projeto EAD - Clientes');
		this.dialog = $('[view_html_id="'+this.html_id+'"]');
		this.onclose = null;
		this.onsave = null;

		//elementos
		this.btn_novo = this.dialog.find('.btn_novo');
		this.tbl_cliente = this.dialog.find('.tbl_cliente');
		this.btn_refresh = this.dialog.find('.btn_refresh');

		//metodos
		this.show = function(){
			main.listar();
		}

		this.listar = function(){

			var table_body = main.tbl_cliente.find('tbody');
			var template_row = $(table_body).find('[template-row="cliente"]');

			var add_row = function(i, item){

				var new_row = template_row.clone();
				new_row.css('display', '')
				       .removeAttr('template-row')
				       .addClass('rows')
				       .attr('id', item.id);

				var field_nome = $(new_row.find('[template-field="nome"]'));
				field_nome.text(item.nome);

				var btn_editar = $(new_row.find('[template-button="btn_editar"]'));
				btn_editar.unbind('click');
				btn_editar.click(function(e){
					Util.load_pag(`cliente/detalhes`, function(id, obj){
						obj.show(item.id);
						var onclose = function(){
							main.listar();
						};
						obj.onclose = onclose;
					});
				});

				new_row.appendTo(table_body);
			}


			Util.get
			(
				'cliente/listar', 
				null, 
				function(response){
					main.tbl_cliente.find('.rows').remove();
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
			Util.load_pag(`cliente/detalhes`, function(id, obj){
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

	return ListCliente;

});