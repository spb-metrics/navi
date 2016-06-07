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

ini_set("display_errors",0);
error_reporting(E_ALL);
include("../interacao/chat/configuration.inc.php");
/** FUNCOES PARA ARQUIVO , APOIO e BIBLIOTECA
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 

    Funcoes : 
    - AcervoApaga -não é utilizada 
    - acervoAulaInterativa 
    - acervoAulaInterativaDia
    - listaAcervoAdm
    - AcervoVerificaAcesso
    - listaAcervo
    - AcervoAltera
    - AcervoInsere
    - AcervoExclue
    - AcervoCodigo
    - AcervoLocalInsere
    - AcervoLocalRemove
    - AcervoLocalAltera  
    - listaAcervoLocal
    - acervoAulas -não é utilizado
    - acervoCaminho
	  - listaAcervoLocal
**/

//======================================================================================================
// tools/acervo.asp
// tools/acervo_envio.asp
// Apaga imformações no Banco

function AcervoApaga($ARQ) {
	$strSQL = "DELETE FROM arquivo WHERE COD_ARQUIVO = " . ARQ;
	
	mysql_query($strSQL);
	
	if (! mysql_errno())
	{
		$strSQL = "DELETE FROM biblioteca WHERE COD_ARQUIVO = " . ARQ;
		
		mysql_query($strSQL);
		
		return (! mysql_errno());
	 }
	else
		return false;
 }

//======================================================================================================
// biblioteca/aula_interativa.asp//

function acervoAulaInterativa()  {

	$strSQL = "SELECT DATE_FORMAT(CM.DATA, '%d/%m/%Y') as DATA FROM ".getMessageTableName($_SESSION["codInstanciaGlobal"])." CM WHERE CM.COD_SALA='" . $_SESSION["codInstanciaGlobal"] . "' ORDER BY CM.DATA DESC";			

	return mysql_query($strSQL);
}

//======================================================================================================
// biblioteca/aula_interativa_mostrar.asp

function acervoAulaInterativaDia($DATA='',$param='')
{
	$strSQL = "SELECT COD_MENSAGEM, NOME_ENVIA, NOME_CHAT, NOME_RECEBE, MENSAGEM , DATE_FORMAT(DATA, '%T') as HOUR, RESERVADO, COD_SALA ".
			  "FROM ".getMessageTableName($_SESSION["codInstanciaGlobal"])." WHERE";
				$strSQL .="  COD_SALA = '" . $_SESSION["codInstanciaGlobal"] . "'";


		if(!empty($param['dataInicio'])&&!empty($param['dataFim'])){
			$strSQL .=" AND DATA < '" .$param['dataFim'] . "235959' AND DATA > '" .$param['dataInicio']. "000000'";

		}else{
			if(!empty($DATA)){
				$strSQL .=" AND DATA < '" . $DATA . "235959' AND DATA > '" . $DATA . "000000' "; 
			 }
		}
			
			 if(!empty($param['COD_PESSOA'])){
				  $strSQL.=" AND COD_PESSOA=".$param['COD_PESSOA'];

			  }
			$strSQL.=" ORDER BY COD_MENSAGEM";
			
	return mysql_query($strSQL);
 }

//===================================================================================================
function listaAcervoAdm($codInstanciaGlobal, $local, $quem)
{
	$strSQL = "SELECT DISTINCT A.COD_PESSOA, A.DESC_ARQUIVO, A.COD_ARQUIVO";

	if ($local == "instanciaAtual")
	{
		$strSQL .= " FROM arquivo A, biblioteca B WHERE A.COD_ARQUIVO = B.COD_ARQUIVO";
		
		if ($codInstanciaGlobal != "")
			$strSQL .= " AND B.COD_INSTANCIA_GLOBAL = '" . $codInstanciaGlobal . "'";
	 }

	if ($local == "nenhum")
	{
		$rsCon = mysql_query("SELECT B.COD_ARQUIVO FROM biblioteca B");
		
		$strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO NOT IN (";
		
		while ($linha = mysql_fetch_array($rsCon))
			$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
		$strSQL .= "0)";
	 }

	if ($local == "algo")
	{
		$rsCon = mysql_query("SELECT B.COD_ARQUIVO FROM biblioteca B");
		
		$strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO IN (";
		
		while ($linha = mysql_fetch_array($rsCon))
			$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
		$strSQL .= "0)";	
	 }

	if ($local == "")
		$strSQL .=" FROM arquivo A, biblioteca B WHERE A.COD_ARQUIVO = B.COD_ARQUIVO";

	if ($quem != "")
		$strSQL = "SELECT * FROM arquivo A, biblioteca B WHERE A.COD_ARQUIVO = B.COD_ARQUIVO AND A.COD_PESSOA = '". $quem ."'";
		
	$strSQL .= " ORDER BY A.COD_ARQUIVO";
  //echo $strSQL;
	return mysql_query($strSQL);
 }

//======================================================================================================

function AcervoVerificaAcesso($cod_arquivo)
{
	$permite = false;
		
	if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1)
		$permite = true;
	
	if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
	{
		// Verifica se o acervo é dele
		
		$strSQL = "SELECT COD_ARQUIVO FROM arquivo".
				  " WHERE COD_ARQUIVO = '". $cod_arquivo ."'".
				  " AND COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."'";
				  
		$rsCon = mysql_query($strSQL);
		
		if ($rsCon)
		{
			if ( $linha = mysql_fetch_array($rsCon) )
				$permite = true;
		}
	 }
	
	if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
	{
		// Verifica se a noticia é de alguma turma na qual ele é professor			  
		$strSQL = "SELECT B.COD_ARQUIVO FROM biblioteca B WHERE AI.COD_ARQUIVO=".quote_smart($cod_arquivo).
					" AND AI.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"];

		$rsCon = mysql_query($strSQL);

		if ($rsCon)
		{
			if ( $linha = mysql_fetch_array($rsCon) )
				$permite = true;
		}
	 }

	if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 ) and (! $permite) )
	{
		// Verifica se a noticia é da alguma turma cujo curso ele é adm
		$strSQL = "SELECT B.COD_ARQUIVO FROM biblioteca B, turma T, disciplina D, administrador_curso AC".
				  " WHERE B.COD_ARQUIVO = '". $cod_arquivo ."'".
				  " AND AC.COD_ADM = '". $_SESSION["COD_ADM"] ."' AND AC.COD_CURSO = D.COD_CURSO".
				  " AND D.COD_DIS = T.COD_DIS AND T.COD_TURMA = B.COD_TURMA";
				  
		$rsCon = mysql_query($strSQL);

		if ($rsCon)
		{
			if ($linha = mysql_fetch_array($rsCon))
				$permite = true;
		}
	 }
	 
	return $permite;
 }

//======================================================================================================
// Retorna um ResultSet com os textos

function listaAcervo($cod_arquivo, $codInstanciaGlobal, $acesso)
{
  if ($codInstanciaGlobal != "")    {	
    $strSQL = "SELECT DISTINCT A.COD_PESSOA, A.COD_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.TAMANHO_ARQUIVO, A.TIPO_ARQUIVO, A.DESC_ARQUIVO, B.COD_ARQUIVO, B.COD_TIPO_ITEM_BIB, B.DESC_ARQUIVO_INSTANCIA, B.COD_TIPO_ACESSO, T.DESC_TIPO_ITEM_BIB" . 		
  	" FROM arquivo A, biblioteca B, tipo_item_biblioteca T" .
  	" WHERE A.COD_ARQUIVO = B.COD_ARQUIVO" .
  	" AND B.COD_TIPO_ITEM_BIB = T.COD_TIPO_ITEM_BIB" .
  	" AND B.COD_ARQUIVO = " . $cod_arquivo.
  	"  AND B.COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal;

    if ($cod_arquivo != "")
    	$strSQL .= " AND A.COD_ARQUIVO =" . $cod_arquivo;
    else	{		
  	  if ($acesso != "")
  	    $strSQL .= " AND (B.COD_TIPO_ACESSO =" . $acesso . " OR B.COD_TIPO_ACESSO=3 )";	
    }
				
    $strSQL .= " ORDER BY A.COD_ARQUIVO DESC";
  }
  else   {   
    $strSQL = "SELECT A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO".
  	" FROM arquivo A" .
  	" WHERE A.COD_ARQUIVO = " . $cod_arquivo;
		
    $strSQL .= " ORDER BY A.COD_ARQUIVO DESC";	
  }
  //echo($strSQL);
  return mysql_query($strSQL);					
}

//======================================================================================================

function AcervoAltera($cod_arquivo, $desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "UPDATE arquivo SET " .
    "CAMINHO_LOCAL_ARQUIVO = '" . $caminho . "', " .
    "DESC_ARQUIVO = '" . $desc_arquivo . "', " .
    "TAMANHO_ARQUIVO = '" . $tamanho . "', " .
    "TIPO_ARQUIVO = '" . $tipo . "', " .
    "COD_PESSOA = " . $_SESSION["COD_PESSOA"] .
    " WHERE COD_ARQUIVO = " . $cod_arquivo; 
	
  mysql_query($strSQL);
				 
  return (! mysql_errno());
}

//======================================================================================================

function AcervoInsere($desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "INSERT INTO arquivo (COD_PESSOA, CAMINHO_LOCAL_ARQUIVO, TAMANHO_ARQUIVO, TIPO_ARQUIVO, DESC_ARQUIVO) " .
    " VALUES (". $_SESSION["COD_PESSOA"] .",'". $caminho . "','". $tamanho ."','". $tipo ."','". $desc_arquivo ."')";
  mysql_query($strSQL);

  return (! mysql_errno());
}

//======================================================================================================

function AcervoExclue($cod_arquivo)
{
  $strSQL = "DELETE FROM arquivo WHERE COD_ARQUIVO = " . $cod_arquivo;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function AcervoCodigo($desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "SELECT COD_ARQUIVO FROM arquivo WHERE".
    " COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."' AND".
    " CAMINHO_LOCAL_ARQUIVO = '". $caminho ."' AND".
    " TAMANHO_ARQUIVO = '". $tamanho ."' AND".
    " TIPO_ARQUIVO = '". $tipo ."' AND".			  			  
    " DESC_ARQUIVO = '". $desc_arquivo . "' ";
  " ORDER BY COD_ARQUIVO DESC";

  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
		
  return $linha["COD_ARQUIVO"];
}

//======================================================================================================

function AcervoLocalInsere($cod_arquivo, $codInstanciaGlobal, $acesso, $desc_arquivo,$cod_tipo_item_bib)
{   
   
   
   
  $strSQL = "INSERT INTO biblioteca (COD_INSTANCIA_GLOBAL, COD_ARQUIVO, COD_TIPO_ACESSO , DESC_ARQUIVO_INSTANCIA, COD_TIPO_ITEM_BIB) " .
    " VALUES (". $codInstanciaGlobal .",". $cod_arquivo .",". $acesso .", '". $desc_arquivo . "','" . $cod_tipo_item_bib . "'  )";

  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function AcervoLocalRemove($cod_arquivo, $codInstanciaGlobal, $acesso, $cod_tipo_item_bib)
{
  $strSQL = "DELETE FROM biblioteca WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $codInstanciaGlobal . " AND COD_TIPO_ITEM_BIB=". $cod_tipo_item_bib;
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function AcervoLocalAltera($cod_arquivo, $codInstanciaGlobal, $desc_arquivo_instancia, $acesso, $tipo_acesso_novo,$cod_tipo_item_bib,$cod_tipo_item_bib_novo)
{
	
  $strSQL = "UPDATE biblioteca SET DESC_ARQUIVO_INSTANCIA ='". $desc_arquivo_instancia ."', COD_TIPO_ACESSO='".$tipo_acesso_novo . "', COD_TIPO_ITEM_BIB='" . $cod_tipo_item_bib_novo."' WHERE COD_ARQUIVO = '". $cod_arquivo ."' AND COD_TIPO_ACESSO = '". $acesso ."' AND COD_INSTANCIA_GLOBAL='". $codInstanciaGlobal . "' AND COD_TIPO_ITEM_BIB='" . $cod_tipo_item_bib."'";
	

  mysql_query($strSQL);

		
	
  return (! mysql_errno());
	
}


//======================================================================================================

/*function listaAcervoLocal($cod_arquivo)
{
  $strSQL = "SELECT DISCIPLINA.COD_CURSO,biblioteca.COD_TURMA, TURMA.COD_DIS, COD_TIPO_ACESSO AS ACESSO, DESC_ARQUIVO_TURMA FROM biblioteca , TURMA, DISCIPLINA " .
    " WHERE DISCIPLINA.COD_DIS = TURMA.COD_DIS AND TURMA.COD_TURMA = biblioteca.COD_TURMA AND COD_ARQUIVO = ". $cod_arquivo .
    " ORDER BY biblioteca.COD_TURMA, COD_TIPO_ACESSO";
	
  return mysql_query($strSQL);					
}*/

//lista os locais em q determinado arquivo esta publicado
function listaAcervoLocal($codArquivo) {
	return listaLocal("biblioteca","COD_ARQUIVO",$codArquivo,"DESC_ARQUIVO_INSTANCIA");
}

//======================================================================================================
// aulas/index.asp
// Retorna os videos disponiveis para uma turma

function acervoAulas($acesso)
{
  $strSQL = "SELECT A.COD_ARQUIVO, A.DESC_ARQUIVO, AT.DESC_ARQUIVO_INSTANCIA FROM arquivo A, arquivo_instancia AT WHERE AT.COD_INSTANCIA_GLOBAL = " .  $_SESSION["codInstanciaGlobal"] . " AND A.COD_ARQUIVO = AT.COD_ARQUIVO AND (AT.COD_TIPO_ACESSO =" . $acesso . " OR AT.COD_TIPO_ACESSO=3 ) GROUP BY COD_ARQUIVO ORDER BY AT.DESC_ARQUIVO_INSTANCIA ";

  return mysql_query($strSQL);	
}
 
//======================================================================================================
// tools/video_operacao.asp;
// aulas/download.asp; aulas/redireciona.asp; aulas/topo.asp; aulas/video.asp
// Retorna o caminho para um determinado cod_video
//a principio nao esta sendo usada
/*function acervoCaminho($cod_arquivo)
{	
  $strSQL = "SELECT A.COD_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.DESC_ARQUIVO, AT.COD_TIPO_ACESSO, AT.COD_INSTANCIA_GLOBAL, AT.DESC_ARQUIVO_INSTANCIA, T.COD_DIS, D.COD_CURSO, A.TIPO_ARQUIVO".
    " FROM arquivo A, ARQUIVO_TURMA AT, TURMA T, DISCIPLINA D".
    " WHERE A.COD_ARQUIVO = AT.COD_ARQUIVO AND AT.COD_TURMA = T.COD_TURMA AND T.COD_DIS = D.COD_DIS".
    " AND A.COD_ARQUIVO = " . $cod_arquivo;
			  
  if ($cod_arquivo == "")
    $strSQL = "SELECT * FROM arquivo WHERE 0";
		
  return mysql_query($strSQL);	
}*/
//======================================================== 
function existeMsgAulaInterativa()  {

	$strSQL = "SELECT * FROM ".getMessageTableName($_SESSION["codInstanciaGlobal"])." CM WHERE CM.COD_SALA='" . $_SESSION["codInstanciaGlobal"] . "' ORDER BY CM.DATA DESC";			
if(@mysql_num_rows(mysql_query($strSQL)) > 0)
	 return 1 ;
else 
	return 0;
}
?>
