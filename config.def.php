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
//ob_start("ob_gzhandler");
//ini_set("display_errors",1);
//error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors",1);
//ob_start("ob_gzhandler"); 
/*
 * Configuracoes do ambiente
 */
//Constantes
define('CLASSE_PHP_PADRAO','DefaultPage');
define('CSS_PADRAO','cssnavi.css');
define('NIVEL_INICIAL',1);
define('INSTANCIA_INICIAL',1);
define('SEPARADOR',': ');
define('LOGOTIPO_PADRAO',1);
define('TAMANHO_MAXIMO_ARQUIVO',5000000);
define('SERVIDOR_SMTP','pincel.adm.ufrgs.br');
define('GRUPO_PADRAO',2);
//tempo maximo para o usuario sinalizar que estß online
define("USER_TIMEOUT",80); 
//endereþo que serß a origem dos emails enviados automaticamente
//no controle de inscriþ§es
define('ENDERECO_INSCRICOES','noreply@ea.ufrgs.br');
//papeis/tipos de usuario a cada momento
define("PUBLICO",1);
define("ALUNO",2);
define("PROFESSOR",3);
define("ADM_NIVEL",4);
define("ADMINISTRADOR_GERAL",5);
//numero de niveis mostrados ao professor
define("NUM_NIVEIS_PROFESSOR",2);
//numero de niveis mostrados ao professor
define("PASTA_LOGOTIPOS",'logotiposNiveis');
//constantes das fotos
define("ALTURA_FOTO",'100');
define("LARGURA_FOTO",'90');
define("ALTURA_FOTO_PEQUENA",'40');
define("LARGURA_FOTO_PEQUENA",'30');
define("ALTURA_LOGOTIPO",'46');
define("LARGURA_LOGOTIPO",'80');
//ID DO SERVIDOR PARA SEGURANÃA
//para criar o cookie
define('SERVER','berlineta.ea.ufrgs.br');
//configuracoes de FTP
/*
$ftp_server = "143.54.31.1";
$ftp_user = "upload_v2";
$ftp_pass = "ja53&9k@c1";
*/
//caminhos fisicos
/*$caminhoUpload="/home/www/navi/upload_navi";
$caminhoBiblioteca="/home/www/navi/lib";
$caminhoImagem="/home/www/navi/imagens";
$caminhoRoot="/home/www/navi";
$caminhoArquivo="/home/www/navi/arquivos";
$caminhoTipoProfessor = "/home/www/navi/imagensTipoProfessor/";*/
$caminhoUpload="C:/upload_v2";
$caminhoUpload1="ftp://143.54.31.2/upload_v2";
$caminhoVideo="ftp://barraforte.ea.ufrgs.br/videos/";
$caminhoRoot="C:/eavirtual_v2/";
$caminhoBiblioteca="C:/eavirtual_V2/lib";
$caminhoImagem="C:/eavirtual_v2/imagens";
$caminhoTipoProfessor="C:/eavirtual_v2/imagensTipoProfessor/";
$cam_httpAuxiliar="mms://barraforte.ea.ufrgs.br/videos/";
$caminhoVideo="ftp://barraforte.ea.ufrgs.br/videos/";
//urlŠs
$url = "http://www.eavirtual.ea.ufrgs.br";
$urlImagem = "http://www.eavirtual.ea.ufrgs.br/imagens";
$urlCss = "http://www.eavirtual.ea.ufrgs.br/css";
$urlJs = "http://www.eavirtual.ea.ufrgs.br/js";
//configuraþ§es SCORM
$caminhoUploadScorm = 'C:/eavirtual_V2/scormUpload';
$caminhoScorm = $caminhoRoot.'/scorm';
$urlUploadScorm = $url.'/scormUpload';
$urlScorm = $url.'/scorm'; 
require_once('defineSession.php');
/*
//FTP PARA O SEGUNDO SERVIDOR
$ftp_server = '143.54.31.2';
$ftp_user   = 'upload_v2' ;
$ftp_pass   = 'ja53&9k@c1';
*/
//fim das configuracoes
//=================================================================================================================
// Inicia Conexao com o Banco de Dados
//	$db = mysql_connect("localhost", "cursosnavi", "kt8mg76i");
$db = mysql_connect("192.168.101.1", "negcol-user","38Nj7p:Mr59J:hYV");
if (! $db) {
  echo "Estamos em manuten&ccedil;&atilde;o.";
  die();
}
$ok = mysql_select_db("negcol");
if (!$ok) {
  echo "Estamos em manuten&ccedil;&atilde;o.";
  die();
}
//incluir cldb por padrao
include($caminhoBiblioteca."/CLDb.inc.php");
include($caminhoBiblioteca."/nucleo.inc.php");
//include("lib/autenticacao.inc.php");
//retorna o nivel atual
function &getNivelAtual() {
	$instGlobal = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);
	$nivel = new Nivel($instGlobal->codNivel);
	return $nivel;
}
function getCodInstanciaNivelAtual() { 
 $instGlobal = getInstanciaGlobalAtual(); 
 return $instGlobal->codInstanciaNivel; 
}
function &getInstanciaGlobalAtual() { 
 $instGlobal = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]); 
 return $instGlobal; 
}
/**
*  Funcao generica de listagem de locais de publicacao
*
*
*/
function listaLocal($tableTool,$pkTool,$codInstanceTool,$labelTool="") {
	$sql = "SELECT f.{$pkTool}, ig.codInstanciaGlobal, ig.codNivel, ig.codInstanciaNivel, COD_TIPO_ACESSO AS ACESSO ";
	if (!empty($labelTool))
		$sql.= ",".$labelTool;
	$sql.= " FROM {$tableTool} as f".
				 " INNER JOIN instanciaglobal AS ig ON (f.COD_INSTANCIA_GLOBAL = ig.codInstanciaGlobal)".
				 " WHERE f.{$pkTool} = ".quote_smart($codInstanceTool);		
	return mysql_query($sql);
}
function imprimeLocais($listaLocaisResult,$url,$pk,$valuePK,$numNiveisImprime=100,$instanciaGlobalAtual="",&$publicadaNivelAtual) {
  $html = "<table>";
  while ($local = mysql_fetch_object($listaLocaisResult)) {
    $nivel = new Nivel($local->codNivel);
    $instanciaNivel = new InstanciaNivel($nivel,$local->codInstanciaNivel);
    //verifica se jß foi publicado na instancia atual passada como parÔmetro
    if (!$publicadaNivelAtual && !empty($instanciaGlobalAtual) &&  $instanciaNivel->codInstanciaGlobal == $instanciaGlobalAtual) {
      $publicadaNivelAtual=1;
    }
    //Lista os pais atÚ chegar no topo ou no numero de niveis requerido,
    //caso nao seja uma instancia 'comunidade'
    $nomesInstancias = array();
    $numNivel = 1;
    while(!$instanciaNivel->nivel->isFirst && $numNivel <= $numNiveisImprime && !$instanciaNivel->nivel->nivelComunidade) {
        array_unshift($nomesInstancias,$instanciaNivel->nome);
        $instanciaNivel = $instanciaNivel->getPai();
        $numNivel++;
    }
    $html.= "<tr><td height=\"22px\"><a  href='".$url."?OPCAO=Remover&codInstanciaGlobal=". $local->codInstanciaGlobal ."&".$pk."=" . $valuePK ."&TIPO_ACESSO=". $local->ACESSO."'><img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>&nbsp;&nbsp;&nbsp;".
     "<a href=\"".$url."?OPCAO=Alterar&codInstanciaGlobal=". $local->codInstanciaGlobal ."&".$pk."=" . $valuePK."&TIPO_ACESSO=". $local->ACESSO ."\"><img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\"></a></td>\n";
    $html.= "<td>";
    foreach($nomesInstancias as $nomeInst) {
      $html.= strtoupper($nomeInst)."&nbsp;";
    }
    $html.= "</td>";
    $html.= "<td align=\"center\">";
    if ( $local->ACESSO == 1 )
      $html.= "Publico";
    elseif ( $local->ACESSO == 2 )
      $html.= "Restrito";
    elseif( $local->ACESSO == 3 )
      $html.= "Publico e Restrito";
  
    $html.= "</td></tr>\n";
  
	}
	$html.= "</table>";
	return $html;
}
//======================================================================================================
// UnificaþÒo da letra da mensagem que aparece em cada mµdulo (ex: acesso restrito a alunos cadastrados)!
function msg($msg) {
  echo "<div class=\"mensagemModulo\"><b>" . $msg . "</b></div>";
}
function security($scriptPublico=0) { //scriptPublico: index (as vezes) e noticia (as vezes)
  global $url;
  if (!$scriptPublico)   {
    if (
        (empty($_SESSION['COD_PESSOA'])) ||  //teste basico da sessao
        //Dados do cliente para conferir
        ($_SESSION['REMOTE_ADDR']!=$_SERVER['REMOTE_ADDR']) || 
        ($_SESSION['HTTP_USER_AGENT']!=$_SERVER['HTTP_USER_AGENT']) ||
        (SERVER!=$_COOKIE['server']) ||
        ($_SESSION['COD_PESSOA']!=$_COOKIE['codPessoa']) ||
        ($_COOKIE['id']!=md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].SERVER.$_SESSION['COD_PESSOA']) )     
       )  {
       //note($_SESSION); note($_COOKIE); note($_SERVER); note($_REQUEST); exit;
       echo "<script>alert('Por favor informe novamente seu usuário e senha'); window.top.location.href='".$url."/logoff.php';</script>";  
    }
  }
}
  
?>
