define([],function(){

	var Login = function(html_id){
		"use strict";

		var main = this;
		this.html_id = html_id;
		this.dialog = $('[view_html_id="'+this.html_id+'"]');
		$('.title_sistema').text('Projeto EAD - Login');
		this.onclose = null;
		this.onsave = null;

		//elementos
		this.edt_usuario = this.dialog.find('.edt_usuario');
		this.edt_senha = this.dialog.find('.edt_senha');
		this.btn_entrar = this.dialog.find('.btn_entrar');
		this.btn_home = $('.btn_home');
		this.btn_clientes = $('.btn_clientes');
		this.btn_servicos = $('.btn_servicos');
		this.btn_logout = $('.btn_logout');

		//metodos
		this.show = function(){

			var md5_sessao = window.localStorage.getItem('md5_sessao');
			if(md5_sessao != null && md5_sessao != '' && md5_sessao.length == 32){
				main.btn_entrar.trigger('click');
			}
		}

		this.unload = function(){
			main.dialog.remove();
			if(main.onclose && typeof main.onclose == 'function'){
				main.onclose();
			}
		}

		//eventos
		this.btn_entrar.unbind('click');
		this.btn_entrar.click(function(e){
			
			Util.post
			(
				'usuario/login', 
				{
					usuario: main.edt_usuario.val(),
					senha: main.edt_senha.val(),
				}, 
				function(response){
					window.localStorage.setItem('md5_sessao', response);

					main.btn_home.unbind('click').removeClass('hidden');
					main.btn_home.click(function(e){
						$('[view_html_id]').remove();
						Util.load_pag(`contrato/listar`, function(id, obj){
							obj.show()
						});
					});

					main.btn_clientes.unbind('click').removeClass('hidden');
					main.btn_clientes.click(function(e){
						$('[view_html_id]').remove();
						Util.load_pag(`cliente/listar`, function(id, obj){
							obj.show()
						});
					});

					main.btn_servicos.unbind('click').removeClass('hidden');
					main.btn_servicos.click(function(e){
						$('[view_html_id]').remove();
						Util.load_pag(`servico/listar`, function(id, obj){
							obj.show()
						});
					});

					main.btn_logout.unbind('click').removeClass('hidden');
					main.btn_logout.click(function(e){
						Util.post
						(
							'usuario/logout', 
							null, 
							function(response){
								$('[view_html_id]').remove();
								window.localStorage.setItem('md5_sessao', null);
								main.btn_home.unbind('click').addClass('hidden');
								main.btn_clientes.unbind('click').addClass('hidden');
								main.btn_servicos.unbind('click').addClass('hidden');
								main.btn_logout.unbind('click').addClass('hidden');

								Util.load_pag(`login/login`, function(html_id, obj){
					                obj.show();
					            });
							}
						);
						
					});
					main.btn_home.trigger('click');

				}
			);
		});
		
	};

	return Login;

});