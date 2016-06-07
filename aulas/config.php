<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

ini_set("display_errors",1);
error_reporting(E_ALL ^ E_NOTICE);
/*
 * Configuracoes do ambiente
 */
//Constantes
define("CLASSE_PHP_PADRAO","DefaultPage");
define("CSS_PADRAO","navi.css");
define("NIVEL_INICIAL",1);
define("INSTANCIA_INICIAL",1);
define("SEPARADOR",": ");
//papeis/tipos de usuario a cada momento
define("ADM_NIVEL",1);
define("PROFESSOR",2);
define("ALUNO",3);
define("ADMINISTRADOR_GERAL",4);
define("PUBLICO",5);

//numero de niveis mostrados ao professor
define("NUM_NIVEIS_PROFESSOR",2);
//numero de niveis mostrados ao professor
define("PASTA_LOGOTIPOS",'logotiposNiveis');


//caminhos fisicos
$caminhoUpload="/home/www/navi/upload_navi";
$caminhoBiblioteca="/home/www/navi/lib";
$caminhoImagem="/home/www/navi/imagens";
$caminhoRoot="/home/www/navi/";
$caminhoArquivo="/home/www/navi/arquivos";
$caminhoTipoProfessor="/home/www/navi/imagensTipoProfessor/";
$cam_httpAuxiliar="mms://multimidia.ufrgs.br/conteudo/";
$caminhoVideo="ftp://barraforte.ea.ufrgs.br/videos/";


//url�s
$url = "https://ead.ufrgs.br/navi";
$urlImagem = "https://ead.ufrgs.br/navi/imagens";

//fim das configuracoes
//=================================================================================================================

// Inicia Conexao com o Banco de Dados
//	$db = mysql_connect("localhost", "cursosnavi", "kt8mg76i");
$db = mysql_connect("localhost", "navi" ,"N@v13Ea#05");
if (! $db) {
  echo "Nao foi possivel conectar ao banco de dados.";
  die();
}
$DBName = "navi";
$ok = mysql_select_db($DBName);
if (! $ok) {
  echo "Nao foi possivel encontrar o banco de dados " . $DBName;
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

    //verifica se j� foi publicado na instancia atual passada como par�metro
    if (!$publicadaNivelAtual && !empty($instanciaGlobalAtual) &&  $instanciaNivel->codInstanciaGlobal == $instanciaGlobalAtual) {
      $publicadaNivelAtual=1;
    }

    //Lista os pais at� chegar no topo ou no numero de niveis requerido
    $nomesInstancias = array();
    $numNivel = 1;
    while(!$instanciaNivel->nivel->isFirst && $numNivel <= $numNiveisImprime ) {
        array_unshift($nomesInstancias,$instanciaNivel->nome);
        $instanciaNivel = $instanciaNivel->getPai();
        $numNivel++;
    }

    $html.= "<tr><td height=\"22px\"><a  href=\"javascript:open1('".$url."?OPCAO=Remover&codInstanciaGlobal=". $local->codInstanciaGlobal ."&".$pk."=" . $valuePK ."&TIPO_ACESSO=". $local->ACESSO."')\"><img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>&nbsp;&nbsp;&nbsp;".
     "<a href=\"javascript:open1('".$url."?OPCAO=Alterar&codInstanciaGlobal=". $local->codInstanciaGlobal ."&".$pk."=" . $valuePK."&TIPO_ACESSO=". $local->ACESSO ."')\"><img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\"></a></td>\n";

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
    //elseif( $local->ACESSO == 3 )
     // $html.= "Publico e Restrito";
  
    $html.= "</td></tr>\n";
  
	}
	$html.= "</table>";

	return $html;

}

//======================================================================================================
// Unifica��o da letra da mensagem que aparece em cada m�dulo (ex: acesso restrito a alunos cadastrados)!

function msg($msg) {
  echo "<div class=\"mensagemModulo\"><b>" . $msg . "</b></div>";
}


?>