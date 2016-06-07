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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set("display_errors",1);

/*
 * Configuracoes do ambiente
 */
//Constantes

define('CLASSE_PHP_PADRAO','defaultpage');
define('CSS_PADRAO','cssnavi.css');
define('NIVEL_INICIAL',1);
define('INSTANCIA_INICIAL',1);
define('SEPARADOR',': ');
define('LOGOTIPO_PADRAO',1);
define('TAMANHO_MAXIMO_ARQUIVO',20000000);
define('SERVIDOR_SMTP','');
define('GRUPO_PADRAO',1);
//tempo maximo para o usuario sinalizar que estß online
define("USER_TIMEOUT",80); 
//endereço que será a origem dos emails enviados automaticamente no controle de inscrições
define('ENDERECO_INSCRICOES','');

//defines de PAPEIS estao em defineSession.php

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
define('SERVER','localhostnavi2011');
define('SEGMENTAR_TABELAS_CHAT',0);

$caminhoVideo="ftp://";
$caminhoRoot="/var/www/navi";
$caminhoUpload=$caminhoRoot."/upload_navi";
$caminhoBiblioteca=$caminhoRoot."/lib";
$caminhoImagem=$caminhoRoot."/imagens";
$caminhoTipoProfessor=$caminhoRoot."/imagensTipoProfessor/";
$cam_httpAuxiliar="";

//urls
$url = "http://localhost/navi";
$urlImagem = $url."/imagens";
$urlCss = $url."/css";
$urlJs = $url."/js";

/*
 * arquivo utilizado por toda a plataforma: nome da sessao, papeis e permissao de interacao
 */ 
require_once('defineSession.php');

/*
 * FTP PARA O SEGUNDO SERVIDOR, CASO SEJA UTILIZADO
*/
$ftp_server = '';
$ftp_user   = '' ;
$ftp_pass   = '';


/* 
 * Configura se o navi deve incluir automaticamente usuarios cuja autenticacao esteja ok na ufrgs 
 */
$incluiUsuariosExternos=0;

//fim das configuracoes


/*
 * Inicia Conexao com o Banco de Dados
 */

define(BD_HOST,"localhost");
define(BD_USER,"");
define(BD_SENHA,"");
define(BD_NAME,"navi");

$db = mysql_connect(BD_HOST, BD_USER, BD_SENHA);
if (! $db) {
  echo "Estamos em manuten&ccedil;&atilde;o.";
  die();
}
$ok = mysql_select_db(BD_NAME);
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

/*
 * Seguranca no acesso, chamada em todos os itens
 */
function security($scriptPublico=0,$controlaInstancia=0) { //scriptPublico: index (as vezes) e noticia (as vezes)
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
    $codInstanciaGlobal = (int)$_SESSION['codInstanciaGlobal'];    
    //funcao javascript que confere a instancia global atual com a gerada no topo
    if (!$controlaInstancia) {
      echo '<script>';
      echo 'if (window.top.document.getElementById("instanciaGlobal").innerHTML!='.$codInstanciaGlobal.') {'; 
      echo '  alert("Erro de acesso. Voce pode ter aberto mais de uma janela do NAVi."); window.top.location.href="'.$url.'/logoff.php"';
      echo '}';
      echo '</script>';
    } 
  }  
}  
?>
