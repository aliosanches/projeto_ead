define([],function(){

	var DetalheCliente = function(html_id){
		"use strict";

		var main = this;
		this.html_id = html_id;
		this.dialog = $('[view_html_id="'+this.html_id+'"]');
		this.onclose = null;
		this.onsave = null;

		//elementos
		this.edt_nome_cliente = this.dialog.find('.edt_nome_cliente');

		this.btn_salvar = this.dialog.find('.btn_salvar');
		this.btn_cancelar = this.dialog.find('.btn_cancelar');
		this.btn_excluir = this.dialog.find('.btn_excluir');

		//vasriaveis
		this.cliente_id = null;

		//metodos
		this.show = function(cliente_id){

			main.cliente_id = cliente_id;
			if(main.cliente_id > 0){
				main.preencher();
			}
		}

		this.preencher = function(){
			Util.get
			(
				'cliente/dados', 
				{
					cliente_id: main.cliente_id,
				}, 
				function(response){

					main.edt_nome_cliente.val(response.nome);

					main.btn_excluir.removeClass('hidden');
					main.btn_excluir.unbind('click');
					main.btn_excluir.click(function(e){
						
						Util.post
						(
							'cliente/excluir', 
							{
								cliente_id: main.cliente_id,
							}, 
							function(response){
								main.unload();
							}
						);
					});
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
		this.btn_cancelar.unbind('click');
		this.btn_cancelar.click(function(e){
			main.unload();
		});

		this.btn_salvar.unbind('click');
		this.btn_salvar.click(function(e){
			
			if(main.edt_nome_cliente.val() == ''){
				return;
			}
			Util.post
			(
				'cliente/salvar', 
				{
					cliente_id: main.cliente_id,
					nome: main.edt_nome_cliente.val(),
				}, 
				function(response){
					main.unload();
				}
			);
		});
	};

	return DetalheCliente;

});