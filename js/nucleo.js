//para poder depois "desfazer" o estilo de recurso ativado no menu
var recursoAnterior;
//detecção de browser para manter a compatbilidade com IE e firefox 
var ie = (navigator.appName == 'Microsoft Internet Explorer' );


//funcoes para gerenciamento da janela de instancias
function aumenta(id) {
  //janela
  el = document.getElementById(id); el.style.display = ''; 
  //altura do titulo da janela
  hTitulo=35;

  //adapta a altura da janela de acordo com o navegador
  //no caso do IE altura=0 ajusta automaticamente ao necessário
  if (ie) { el.style.height = '0'; } 

  //Ajuste automático feito sempre em navegadores diferentes do IE e   
  //no IE verifica se a altura da janela nao ultrapassou o limite da tela. 
  //adiciona pixels para considerar o titulo da janela, margem superior e bordas
  // 
  if ((!ie) || ((el.clientHeight+hTitulo) > document.body.clientHeight ) ) {
    el.style.height = document.body.clientHeight-hTitulo;
  }
  
}

function diminui(id) { 
  el = document.getElementById(id); el.style.display = '';  el.style.height = '65'; 
}    
function fecha(id) { 
  el = document.getElementById(id); el.style.display = 'none'; 
}    

function trocaExibicao(antigo,antigoTitulo,novo,novoTitulo) { 
  el = document.getElementById(antigo); el.style.display = 'none';
  el = document.getElementById(antigoTitulo); el.style.display = 'none'; 
  el = document.getElementById(novo); el.style.display = '';
  el = document.getElementById(novoTitulo); el.style.display = '';               
}

function alturaRecurso() {
  //123 é a soma dos widgets do topo (estrutura sistêmica, menu, bordas) mais alguns pixels
  //"de ajuste"
  altura = document.body.clientHeight-123;
  //Ajusta a altura de acordo com a tela
  document.getElementById('recurso').style.height = altura;
}
  
//---------------------------------------------
function entraSenha(teclapres) {
	var tecla = teclapres.keyCode;	
	if (tecla == 13){document.form1.submit();}
}

//destaca o recurso (item de menu)
function recursoAtivado(el) {
  if (recursoAnterior!=null) { 
    recursoAnterior.style.border='';
    el.style.backgroundColor='';
  }
  recursoAnterior=el;
  if (navigator.appName == 'Microsoft Internet Explorer') {
    //el.style.border='1px #EFEFEF outset';
    el.style.border='';    
  }
  else {
    //el.style.borderBottom='1px darkred solid';
    el.style.border='';
  }
  
}

//muda o source de um iframe
function changeIframeSrc(iframe_name,src) {
   if (document.all)
      el = document.all(iframe_name);
   else if (document.getElementById)
      el = document.getElementById(iframe_name);

   el.src = src;
}

function carregaComunidade(el,codNivel,link) {
  comunidadeSelecionada = el[el.selectedIndex].value;
  
  variaveis = comunidadeSelecionada.split("|");
  redir = link+'?iniciarNavegacao=1&codNivel='+codNivel+"&codInstanciaNivel="+variaveis[0]+"&userRole="+variaveis[1];
  
  location.href=redir; 
}


//marca o usuario como estando online
function alive() {
  //Instancia o objeto xmlhttprequest apenas 1 vez 
  try{
    xmlhttp = new XMLHttpRequest();
  }catch(ee){
    try{
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e){
      try{
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }catch(E){
          xmlhttp = false;
      }
    }
  }
  if (!xmlhttp) {
    alert('Seu navegador bloqueou o sistema de torpedos. Voce nao podera enviar nem receber torpedos nem os outros verão que voce está online. Verifique seu navegador.');
  }
  else {
    //roda uma primeira vez arbritariamente, para o usuario ja ser marcado como online
    //e tambem ver os torpedos pendentes imediatamente
    setAlive(xmlhttp); 
    //prepara a funcao para ser executada a cada 60seg
    window.setInterval("setAlive(xmlhttp)",60000);    
  }
}

/*
 * Ativado pelo botao de ver torpedos
 */ 
function showTorpedos() {
  el = document.getElementById('torpedo');
  if (el.style.display=='inline') {
    el.style.display='none';
    //desabilita o icone de torpedos novos, pois o usuario já terá lido as msgs 
    el = document.getElementById('iconeTorpedoNovo');  el.style.display='none';
    el = document.getElementById('iconeTorpedo');      el.style.display='inline';    
    //hack para IE6, desabilita/habilita o combo para adm´s poderem ver o torpedo...
    el = document.getElementById('instanciasSubNivel');
    if (el!=null) {     el.style.display='inline'; }
  }
  else  {
    el.style.display='inline';
    //hack para IE6, desabilita/habilita o combo para adm´s poderem ver o torpedo...
    el = document.getElementById('instanciasSubNivel');
    if (el!=null) { el.style.display='none'; }    
  }
}

//marca usuario como estando online
//e busca os novos torpedos
function setAlive(xmlhttp) {
  xmlhttp.open("GET","alive.php",true); 
  xmlhttp.setRequestHeader("Cache-Control", "no-store, no-cache, must-revalidate");
  xmlhttp.setRequestHeader("Cache-Control", "post-check=0, pre-check=0");
  xmlhttp.setRequestHeader("Pragma", "no-cache");
 

  //Função para tratamento do retorno
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4){
      //Mostra os torpedos recebidos
      retorno=unescape(xmlhttp.responseText);
      
      if (retorno!='') {        
        el = document.getElementById('torpedo');        
        el.innerHTML = retorno;
        //Mostra os torpedos, pois estarão marcados como lidos
        el = document.getElementById('iconeTorpedoNovo');   el.style.display='inline';
        el = document.getElementById('iconeTorpedo');       el.style.display='none';
        showTorpedos();
        //el = document.getElementById('torpedo');            el.style.display='inline';
      }
    }
  }
  //Executa
  xmlhttp.send(null);  
}