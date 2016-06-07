/*
 * Funcoes da tela de administracao de pessoas
 */


/*
 * Habilita selecao de papel para os adms e 
 * caso o professor tenha direitos para tanto
 */
function ajustaInserirPessoa(isAdm) {
 
  //habilita insercao para adm
	if (isAdm>0) {return true; }

  //se nao for adm eh professor, entao habilita apenas se 
  //o 5o parametro indicar que o professor tem direito de gerenciar este papel
  if (document.form.papel.options.selectedIndex>=0){
		var str = document.form.papel.options[document.form.papel.options.selectedIndex].value;
		str = str.split('-');

		if (str[5]!=1) {  
			document.form.papel.options[document.form.papel.options.selectedIndex].selected=false;
			alert('Este papel nao pode ser gerenciado por Adm Basico (professor)');
  	}
  }
}

/*
 * Habilita seleciona ou nao selecao de pessoas a fim de retira-las da instancia
 * permite aos administradores e ao professor tenha direitos para isso
 */
function ajustaRetirarPessoa(isAdm, el) {
  //habilita insercao para adm
	if (isAdm>0) { return true;  }
  var str='';
  var i=0;
  var isNotAllowed=0;
 
  //se nao for adm eh professor, entao habilita apenas se 
  //o 5o parametro indicar que o professor tem direito de gerenciar este papel
  for(i=0; i<=el.options.length;i++) {
		if (el.options[i]!=null && el.options[i].selected==true){
			str = el.options[i].value;
			str = str.split('-');

			if (str[3]!=1) {  
				el.options[i].selected=false;  
				isNotAllowed++;
		 	}
		}
	}
  if (isNotAllowed==1) {
	  alert('Esta pessoa nao pode ser gerenciada por Adm Basico (Professor)\nVeja na relacao ao lado quais sao os papeis permitidos!'); 
  }
  else if (isNotAllowed>1) {
	  alert(isNotAllowed+' pessoas foram desmarcadas.\nAlguns papeis nao podem ser gerenciados por Adm Basico (Professor)\nVeja na relacao ao lado quais sao os papeis permitidos!'); 
  }
}

function validaNovaPessoa(){
	var msg="";
	var erro = false;

	// Valida senha - text
	if ((document.form.senhaPessoa.value == null) || (document.form.senhaPessoa.value == ""))
	{
    if (document.form.senhaPessoa.style.display!='none') {
			msg += "=> Preencher as senhas;\n";
			erro = true;
    }
	}
	if ((document.form.senhaPessoa2.value == null) || (document.form.senhaPessoa2.value == ""))
	{
    if (document.form.senhaPessoa2.style.display!='none') {
			msg += "=> Preencher a confirmação de senha;\n";
			erro = true;
		}
	}
	if(!erro && (document.form.senhaPessoa.value != document.form.senhaPessoa2.value))
	{
		if (document.form.senhaPessoa.style.display!='none') {
			msg += "=> Confirmar novamente a sua senha;\n";
      erro = true;
    }
	}

	//valida nomePessoa
	if ((document.form.nomePessoa.value == null) || (document.form.nomePessoa.value == ""))
	{
		msg += "=> Preencher o nome;\n";
		erro = true;
	}
	//valida usuarioPessoa
	if ((document.form.usuarioPessoa.value == null) || (document.form.usuarioPessoa.value == ""))
	{
		msg += "=> Preencher o usuário;\n";
		erro = true;
	}
	//valida emailPessoa
	if ((document.form.emailPessoa.value == null) || (document.form.emailPessoa.value == ""))
	{
		msg += "=> Preencher o email;\n";
		erro = true;
	}
  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.form.emailPessoa.value))) {
		msg += "=> É necessário o preenchimento de um endereço de e-mail válido.\n";
		erro = true;
	}			
	if(erro) {
	  return msg;
	}
	else	{
	  return true;
	}
	
}

function validaInsereInstancia(flagFiltro) {

  var msg="Por Favor você precisa:\n\n";
  var erro=false;
  var tmp;
	 
  //Validacoes da insercao da pessoa
  if (flagFiltro == "A_criaNovaPessoa") {
    tmp=validaNovaPessoa();
    
    if(tmp != true) {
      erro=true;
      msg+=tmp;
    }  
  }
  
  if(flagFiltro == "A_incluiPessoaNivel") {
    el = document.getElementById("pessoaNivelEscolhido[]")
    if(el==null || el.selectedIndex == -1) {
      msg += "=>Escolher uma pessoa.\n";
      erro = true;      
    }
  }
  
  if(document.form.papel.value == null || document.form.papel.value == ""){
    msg += "=> Escolher uma autoridade para essa pessoa.\n";
    erro = true;
  }
  
  
  if (erro) {
    alert (msg);
    return false;
  }
  else {
    if(flagFiltro != "A_criaNovaPessoa"){
      verificaSePessoaJaExisteNaInstancia();
    }
    document.form.submit();
  }
}

function validaFormAdicionaPapel(){
  if(document.adicionaPapel.novoPapel.value== null || document.adicionaPapel.novoPapel.value== "" ){
    alert('Papel não preenchido\n');
    return false;  
  }
  else {
    document.adicionaPapel.submit();
  }
}

function selecionaCodNivelCodInstanciaNivel(el,link) {
  instanciaSelecionada = el[el.selectedIndex].value;
  
  variaveis = instanciaSelecionada.split("|");
  
  redir = link+'?codNivel='+variaveis[1]+"&codInstanciaNivel="+variaveis[0];
  
  location.href=redir; 
}

function validaExcluiRegistro(){
  if(document.getElementById("pessoaNivelEscolhido[]").selectedIndex == -1){
   alert('Por favor escolha uma pessoa do quadro "Pessoas" !\n')   ;
   return false;
  }
  else{
    if(confirm('Você realmente quer EXCLUIR o registro desta pessoa? \n TODOS OS DADOS SERÃO PERDIDOS!!\n')){
      document.form.submit();
    }
    else { 
      return false;
    }
  }
}

function validaRetiraInstancia(){
  if(document.getElementById("pessoaNivelAtual[]").selectedIndex == -1){
    alert('Por favor escolha uma pessoa para ser retirada!\n')   ;
    return false;
  }
  else{
    if(confirm('Você realmente quer retirar essa pessoa dessa instância? \n')){
      document.form.submit();
    }
    else {
      return false;
    }
  }
}


function verificaSePessoaJaExisteNaInstancia(){
  var pessoasNivelEscolhido=  document.getElementById("pessoaNivelEscolhido[]");
  var pessoasNivelAtual=      document.getElementById("pessoaNivelAtual[]");
  
  
  for(var i=0; i<pessoasNivelEscolhido.options.length; i++){
    if(pessoasNivelEscolhido.options[i].selected){
      for(var j=0; j<pessoasNivelAtual.options.length; j++){
              
        variaveis=pessoasNivelAtual.options[j].value.split("-");
        
        if(pessoasNivelEscolhido.options[i].value==variaveis[2]){
          alert("A pessoa "+pessoasNivelEscolhido.options[i].text+ " já está inserida nesta Instância \n");
          pessoasNivelEscolhido.options[i].selected=false; 
        }
      }
    }
  }  
}
