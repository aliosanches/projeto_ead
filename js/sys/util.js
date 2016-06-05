
var Util = {
	view_path: 'view/',
  webservice_path: '/projeto_ead/webservice/index.php/' 
};

Util.gerar_hash = function(text){ 
  return md5(Math.random()+new Date()+Math.random()+text);
};

Util.define_language = function(){

  var idioma = (navigator.browserLanguage!= undefined)?  navigator.browserLanguage : navigator.language;
  $('#container').find('label').attr('language', idioma);
  
}

Util.load_pag = function(caminho, callback){

	caminho = Util.view_path+caminho;

  var html_id = Util.gerar_hash(caminho);
  $('#container').append('<div view_html_id="'+html_id+'" path="'+caminho+'">');
	$('[view_html_id="'+html_id+'"]').load(caminho+'.html', function(html_string, error){
        
    if(error == 'error'){
      console.log('erro ao carregar pag');
      return;
    }
    Util.define_language();
    require([caminho+'.js'], function(obj_class){
        
        if(typeof obj_class == 'function'){
        	var object = new obj_class(html_id);
          if(typeof callback != 'undefined'){
            callback(html_id, object);
          }
        }
        
    });
	});
}

Util.validResponse = function(response){

  try{

    var arr_mensagem = JSON.parse(response.mensagem);
    var span = '';
    for (var i = 0; i < arr_mensagem.length; i++) {
      span = span+'<span class="text-info" style="padding-left: 15px;">'+arr_mensagem[i]+'</span><br>';
    }

    var params = {
      title: 'Validacao',
      text: span,
      fnLoad: null,
      utilizaConfirm: false
    }

    alertModal(params);

  }catch(error){

    var span = '<span class="text-info" style="padding-left: 15px;">'+response.mensagem+'</span>';
    var params = {
      title: 'Validacao',
      text: span,
      fnLoad: null,
      utilizaConfirm: false
    }

    alertModal(params);
  }
}

Util.post = function(route, params, fn_success){

  var parametros = {
    route: route,
    params: params,
    fn_success: fn_success
  }
  
  if(typeof route == 'object'){
    parametros = route;
  }

  if(!parametros.params){
    parametros.params = {};
  }
  parametros.params.route = parametros.route;
  parametros.params.md5_sessao = window.localStorage.getItem('md5_sessao');

  $.ajax(
    {
      url: Util.webservice_path+route,
      data: parametros.params,
      dataType: 'JSON',
      method: 'POST',
      success: function(response, status, xhr){

        if(
            response 
            && typeof response.mensagem == "undefined" 
            && typeof response.err_code == "undefined"
          ){
          
          if(typeof parametros.fn_success == 'function'){
            parametros.fn_success(response);
          }
        }else{

          Util.validResponse(response);
        }
      }
    }
  );
}

Util.get = function(route, params, fn_success){

  var parametros = {
    route: route,
    params: params,
    fn_success: fn_success
  }
  
  if(typeof route == 'object'){
    parametros = route;
  }

  if(!parametros.params){
    parametros.params = {};
  }
  parametros.params.route = parametros.route;
  parametros.params.md5_sessao = window.localStorage.getItem('md5_sessao');
  
  $.ajax(
    {
      url: Util.webservice_path+route,
      data: parametros.params,
      dataType: 'JSON',
      method: 'GET',
      success: function(response, status, xhr){

        if(
            response 
            && typeof response.mensagem == "undefined" 
            && typeof response.err_code == "undefined"
          ){

          if(typeof parametros.fn_success == 'function'){
            parametros.fn_success(response);
          }
        }else{

          Util.validResponse(response);
        }
      }
    } 
  );
}

