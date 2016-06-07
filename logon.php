<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de dúvidas e/ou sugestões, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, portão K. Porto Alegre - RS. CEP: 90035-003

Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU conforme publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (a seu critério) qualquer versão posterior.

    Este programa é distribuído na expectativa de que seja útil, porém, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implícita de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.
    Consulte a Licença Pública Geral do GNU para mais detalhes.
    

    Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto com este programa;
    se não, escreva para a Free Software Foundation, Inc., 
    no endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

//include_once("funcoes_bd.php");
include_once("config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");

if ((isset($_SESSION['userRole']) && ($_SESSION['userRole'] == PUBLICO)) || (empty($_SESSION['userRole']))) {$sp = 1;} else {$sp = 0;}

session_name(SESSION_NAME); session_start(); security($sp);

if (isset($_REQUEST['Logon']))
{
  $chaveRecebida = $_REQUEST['Logon'];
}
else
{
  $chaveRecebida = '';  
}

if (empty($chaveRecebida)) { //estamos recebendo dados do navi
  
  //para compatibilidade com os recursos antigos
  $_SESSION["COD_CURSO"] = "";
  $_SESSION["COD_DIS"]   = "";
  $_SESSION["COD_TURMA"] = "";
  $_SESSION["PAGINA_ATUAL"] = "logon";
  
  //primeiro, garantimos que a pessoa nao tenha deixado em branco os campos de usuario e senha
  if ( (! isset($_REQUEST["USER_PESSOA"])) OR ($_REQUEST["SENHA_PESSOA"] == "") ) {
  	echo "<script> alert('Entre com usuário e senha.'); location.href='./index.php';</script>";
  	exit();
  	die();
  }
  $_SESSION['criarPessoa']=0; //desabilita por padrao
  //aqui verificamos se existe este usuario na plataforma
  //caso exista, guarda o ultimoAcesso exibi-lo em cada um dos casos	
  $pessoa = existeUsuario($_REQUEST["USER_PESSOA"]);
 
  //Aqui a pessoa nao está cadastrada ainda!
  if (empty($pessoa)) {
    if ($incluiUsuariosExternos) {  //variavel global setada no config.php 
      $tentaIncluir=1; //pessoa ainda nao existe, verificara se existe no ambiente externo (ex. ufrgs)
      $_SESSION['criarPessoa']=1;
      $_SESSION['novoUser']= $_REQUEST["USER_PESSOA"];
      $_SESSION['pessoa']["USER_PESSOA"]= $_REQUEST["USER_PESSOA"];    
    }
    else {
     usuarioSenhaInvalido();
    }
  }
  else  if (!$pessoa['ativa']) { //a pessoa esta cadastrada mas esta inativa
    usuarioInativo();  
  }
  
  //usuario sem vinculo: verifica no BD do NAVi a senha
  if (!$pessoa['vinculoUfrgs'] && ($pessoa['SENHA_PESSOA'] == md5($_REQUEST["SENHA_PESSOA"]) ) ) {
    verificaLogin();
    
    //guarda em sessao os dados necessarios da pessoa para uso no ambiente  
    $_SESSION["NOME_PESSOA"] = $pessoa["NOME_PESSOA"];
    $_SESSION["COD_PESSOA"]  = $pessoa["COD_PESSOA"];
    if (!empty($pessoa["EMAIL_PESSOA"])) {
      $_SESSION["CORREIO_MAIL_PESSOA"] = $pessoa["EMAIL_PESSOA"];
    }
    //essas funcoes foram mantidas para compatibilidade!
    verificaAluno();
    verificaProf();
    verificaAdm();
    nivelAcesso();
    nivelAcessoFuturo();
    guardaUltimaEntrada($pessoa['COD_PESSOA']);
  }
  else if($pessoa['vinculoUfrgs'] || $tentaIncluir) {     
    if ($pessoa['vinculoUfrgs']) {
      $_SESSION['pessoa'] = $pessoa; //guarda em sessao os dados necessarios da pessoa para uso no ambiente  
    }
    /*
     *  Aqui enviamos os dados para autenticacao no bd da UFRGS
     */
    $senha      = $_REQUEST["SENHA_PESSOA"];
    $cartaoUfrgs= $_REQUEST["USER_PESSOA"];
    $destino    = $url.'/logon.php';
    echo "<div align='center'>Autenticando seu cart&atilde;o UFRGS...</div>";
    echo "<form name='loginNavi' method ='POST' action='https://www1.ufrgs.br/logongenerico/logongenericovalida.php'>";
    //echo "<form name='loginNavi' method ='POST' action='https://www.ead.ufrgs.br/navi/logon.php?Logon=222'>";
    echo "<input type='hidden' name='usuario' value='".$cartaoUfrgs."'>";
    echo "<input type='hidden' name='senha' value='".$senha."'>";
    echo "<input type='hidden' name='Destino' value='".$destino."'>";
    echo "</form>";
    echo "<script>document.loginNavi.submit();</script>";
  } 
  else {
    usuarioSenhaInvalido();
  }
}
else {
  $cartaoUfrgs = str_repeat('0',8-strlen($_SESSION['pessoa']["USER_PESSOA"])).$_SESSION['pessoa']["USER_PESSOA"];
  $hashNavi = '{+}[-](&)';
  //$hashNavi = '';
  $min=date('z'); // Minuto
  $chaveCalculada=md5($cartaoUfrgs.$min.$hashNavi).$cartaoUfrgs;  //chave geral
  //echo "<pre><br>esperado: ". $chaveCalculada.' cartao ufrgs: '.$cartaoUfrgs ;
  //echo "<br>recebido: ". $chaveRecebida;
  //exit;
  if ($chaveCalculada != $chaveRecebida) {
    usuarioSenhaInvalido();
  }
  //pessoa nao tinha senha no navi, verificando na ufrgs
  else if ($_SESSION['criarPessoa']) {
    //codigo ok, mas sem nome e email
    //$sql = 'INSERT INTO pessoa (USER_PESSOA,vinculoUfrgs) VALUES ('.$_SESSION['novoUser'].',1)';

    //busca nome e email da view, ja incluindo a pessoa com os dados corretos
		$conex = mssql_connect('xxx', 'xxx', 'xxx');
		mssql_select_db('ENSINOPESQUISA', $conex);
    $result=mssql_query("SELECT NomePessoa,EMail FROM v_PESSOA_EAD WHERE CodPessoa=".$_SESSION['novoUser']);
    if (mssql_num_rows($result)) {
      $linha=mssql_fetch_assoc($result);     
      $nomePessoa = $linha['NomePessoa'];
      $email = $linha['EMail'];
    }
    $sql = 'INSERT INTO pessoa (USER_PESSOA,NOME_PESSOA,EMAIL_PESSOA,vinculoUfrgs) VALUES ('.quote_smart($_SESSION['novoUser']).','.quote_smart($nomePessoa).','.quote_smart($email).',1)';
    mysql_query($sql);
    usuarioUfrgsCriado();
  }

  else {
    //usuario autenticado corretamente na ufrgs...
    $_SESSION["NOME_PESSOA"] = $_SESSION['pessoa']["NOME_PESSOA"];
    $_SESSION["COD_PESSOA"]  = $_SESSION['pessoa']["COD_PESSOA"];
    $_SESSION["USER_PESSOA"]  = $_SESSION['pessoa']["USER_PESSOA"];
    if (!empty($_SESSION['pessoa']["EMAIL_PESSOA"])) {
      $_SESSION["CORREIO_MAIL_PESSOA"] = $_SESSION['pessoa']["NOME_PESSOA"]." (correio NAVi) "."<".$_SESSION['pessoa']["EMAIL_PESSOA"].">";
    }  

    verificaAluno();
    verificaProf();
    verificaAdm();
    guardaUltimaEntrada($_SESSION['pessoa']['COD_PESSOA']);
    //essas funcoes foram mantidas para compatibilidade!
    nivelAcesso();
    nivelAcessoFuturo();  
  }
}

//Inicia a navegacao
if ( (! isset($_REQUEST["PROX_PAG"])) || ($_REQUEST["PROX_PAG"] == "" ) ) {
  echo "<script> location.href='./index.php?iniciarNavegacao=1';</script>";  
}
else {
  echo "<script> location.href='./" . $_REQUEST["PROX_PAG"] . "';</script>"; 	
}

function usuarioSenhaInvalido() {
  //echo '<pre>'; print_r(debug_backtrace()); die;
  echo "<script> alert('Usuario ou senha invalidos.');location.href='./index.php';</script>";
  exit(); die();
}

function usuarioInativo() {
  echo "<script> alert('Seu usuario nao foi ativado ainda. Não e possível acessar a ferramenta.');location.href='./index.php';</script>";
  exit(); die();
}

function usuarioUfrgsCriado() {
  echo "<script> alert('Seu usuario foi criado no NAVi, mas voce; ainda nao tem inscricao em nenhuma Atividade. Inscreva-se e/ou peca ao professor!');location.href='./index.php';</script>";
  exit(); die();
}

/* Verifica se o usuario já nao está logado */
function verificaLogin() {

}
?>
