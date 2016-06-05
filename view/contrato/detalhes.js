define([],function(){

	var DetalheContrato = function(html_id){
		"use strict";

		var main = this;
		this.html_id = html_id;
		this.dialog = $('[view_html_id="'+this.html_id+'"]');
		this.onclose = null;
		this.onsave = null;

		//elementos
		this.cbo_cliente = this.dialog.find('.cbo_cliente');
		this.cbo_servico = this.dialog.find('.cbo_servico');
		this.edt_data_inicial = this.dialog.find('.edt_data_inicial');
		this.edt_data_final = this.dialog.find('.edt_data_final');

		this.btn_salvar = this.dialog.find('.btn_salvar');
		this.btn_cancelar = this.dialog.find('.btn_cancelar');
		this.btn_excluir = this.dialog.find('.btn_excluir');

		//vasriaveis
		this.contrato_id = null;

		//metodos
		this.show = function(contrato_id){

			main.contrato_id = contrato_id;
			if(main.contrato_id > 0){
				main.preencher();
			}else{
				main.carregar_clientes();
				main.carregar_servico();
			}
		}

		this.carregar_clientes = function(value){

			main.cbo_cliente.find('option').remove();
			var option = $('<option value="">Selecione</option>');
			main.cbo_cliente.append(option);

			Util.get
			(
				'cliente/listar', 
				null, 
				function(response){
					
					for (var i = 0; i < response.length; i++) {
						var item = response[i];
						var optionClone = option.clone();
						if(item.id == value){
							optionClone.prop('selected', true);
						}
						optionClone.attr("value", item.id).text(item.nome);
						main.cbo_cliente.append(optionClone);
					}
				}
			);
		}

		this.carregar_servico = function(value){

			main.cbo_servico.find('option').remove();
			var option = $('<option value="">Selecione</option>');
			main.cbo_servico.append(option);

			Util.get
			(
				'servico/listar', 
				null, 
				function(response){
					
					for (var i = 0; i < response.length; i++) {
						var item = response[i];
						var optionClone = option.clone();
						if(item.id == value){
							optionClone.prop('selected', true);
						}
						optionClone.attr("value", item.id).text(item.nome);
						main.cbo_servico.append(optionClone);
					}
				}
			);
		}

		this.preencher = function(){
			Util.get
			(
				'contrato/dados', 
				{
					contrato_id: main.contrato_id,
				}, 
				function(response){

					main.carregar_clientes(response.cliente_id);
					main.carregar_servico(response.servico_id);

					main.btn_excluir.removeClass('hidden');
					main.btn_excluir.unbind('click');
					main.btn_excluir.click(function(e){
						
						Util.post
						(
							'contrato/excluir', 
							{
								contrato_id: main.contrato_id,
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
			

			Util.post
			(
				'contrato/salvar', 
				{
					contrato_id: main.contrato_id,
					cliente_id: main.cbo_cliente.val(),
					servico_id: main.cbo_servico.val(),
					data_inicio: main.edt_data_inicial.val(),
					data_fim: main.edt_data_final.val(),
				}, 
				function(response){
					main.unload();
				}
			);
		});
	};

	return DetalheContrato;

});