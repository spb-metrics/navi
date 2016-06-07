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


/** FUNCOES PARA AVALIACAO
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
    
    Funcoes:
    - constroiAval
    - listaAval
    
*/

//======================================================================================================
// cadastro/insere_cadastro.asp;

//======================================================================================================
// avaliacao/mostra_2.asp
// Devolve recordset com todos os dados de uma avaliação.

function constroiAval($cod_aval)
{
  $strSQL = "SELECT A.COD_AVALIACAO, A.TEXTO_AVALIACAO, P.COD_PERGUNTA, P.TEXTO_PERGUNTA, P.AREA_PERGUNTA,".
      " R.COD_RESPOSTA, R.TEXTO_RESPOSTA, R.CORRECAO FROM AVALIACAO A, AVALIACAO_PERGUNTA P,".
      " AVALIACAO_RESPOSTA R WHERE A.COD_AVALIACAO = '". $cod_aval ."' AND ".
      " A.COD_AVALIACAO = P.COD_AVALIACAO AND R.COD_PERGUNTA = P.COD_PERGUNTA ORDER BY P.COD_PERGUNTA";
	
  return mysql_query($strSQL);	 
}
//=======================================================================================================
function listaAval($codArquivo="")
{
/* modificado por Gisele dia 9/8/2005
function listaAval($codArquivo,$cod_avaliacao, $cod_resposta, $opcao)*/
/* modificado por maicon -> tirei a juncao com turma e disciplina **/

	if ($codArquivo != "")
		$strSQL = "SELECT A.COD_PESSOA,AT.COD_ARQUIVO, AT.DESC_AVALIACAO_INSTANCIA, AT.COD_TIPO_ACESSO , A.DESC_ARQUIVO, AT.COD_INSTANCIA_GLOBAL,".
				  " A.CAMINHO_LOCAL_ARQUIVO, A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO ".
				  " FROM arquivo A, avaliacao_instancia_2 AT WHERE "./** arquivo_instancia ,*/
				  " A.COD_ARQUIVO = AT.COD_ARQUIVO ".
				  " AND A.COD_ARQUIVO =". $codArquivo;
	else
		$strSQL = "SELECT A.COD_PESSOA,A.COD_ARQUIVO, AT.DESC_AVALIACAO_INSTANCIA, AT.COD_TIPO_ACESSO, A.CAMINHO_LOCAL_ARQUIVO FROM arquivo A,".
          " avaliacao_instancia_2 AT WHERE AT.COD_INSTANCIA_GLOBAL = '" . $_SESSION["codInstanciaGlobal"] . "' AND".
          " AT.COD_ARQUIVO = A.COD_ARQUIVO ORDER BY DESC_AVALIACAO_INSTANCIA";

	return mysql_query($strSQL);
 }


/*=================================funcoes do tools Avaliação=============================*/

function listaAvaliacaoAdm($codInstanciaGlobal, $local, $quem)
{
  $strSQL = "SELECT DISTINCT A.DESC_ARQUIVO, A.COD_ARQUIVO";

  if ($local == "instanciaAtual")
    {
      $strSQL .= " FROM arquivo A, avaliacao_instancia_2 AT WHERE A.COD_ARQUIVO = AT.COD_ARQUIVO";
		
      if ($codInstanciaGlobal != "")
	$strSQL .= " AND AT.COD_INSTANCIA_GLOBAL = '" . $codInstanciaGlobal . "'";
    }

  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT AT.COD_ARQUIVO FROM avaliacao_instancia_2 AT");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";
    }

  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT AT.COD_ARQUIVO FROM avaliacao_instancia_2 AT");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";	
    }

  if ($local == "")
    $strSQL .= " FROM arquivo A, avaliacao_instancia_2 AT WHERE A.COD_ARQUIVO=AT.COD_ARQUIVO";

  if ($quem != "")
    $strSQL = "Select * FROM arquivo A, avaliacao_instancia_2 AT WHERE A.COD_ARQUIVO=AT.COD_ARQUIVO AND A.COD_PESSOA = '". $quem ."'";
		
  $strSQL .= " ORDER BY A.COD_ARQUIVO";

  return mysql_query($strSQL);					
}

//======================================================================================================

function AvaliacaoVerificaAcesso($cod_arquivo)
{
  $permite = false;
		
  if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1)
    $permite = true;
	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
    {
      // Verifica se a noticia é dele
		
      $strSQL = "SELECT COD_ARQUIVO FROM arquivo".
	" WHERE COD_ARQUIVO = '". $cod_arquivo ."'".
	" AND COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."'";
				  
      $rsCon = mysql_query($strSQL);
		
      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }

	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
    {
      // Verifica se a avaliacao é de alguma turma na qual ele é professor		  
			$strSQL = "SELECT AI.COD_ARQUIVO FROM avaliacao_instancia_2 AI WHERE ". 				"AI.COD_ARQUIVO=".quote_smart($cod_arquivo).
			" AND AI.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"];

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

function listaAvaliacao($cod_arquivo, $codInstanciaGlobal, $acesso) {
	
  if ($codInstanciaGlobal != "")
    {	
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO, AT.COD_TIPO_ACESSO, AT.DESC_AVALIACAO_INSTANCIA, AT.COD_INSTANCIA_GLOBAL" . 		
	" FROM arquivo A, avaliacao_instancia_2 AT" .
	" WHERE AT.COD_INSTANCIA_GLOBAL =".$codInstanciaGlobal;
		

  if ($cod_arquivo != "") {
	  $strSQL .= "  AND A.COD_ARQUIVO=AT.COD_ARQUIVO" .
			   " AND AT.COD_ARQUIVO = " . $cod_arquivo;
  }
  else	{		
	  if ($acesso != "")
	    $strSQL .= " AND (AT.COD_TIPO_ACESSO =" . $acesso . " OR AT.COD_TIPO_ACESSO=3 )";	
	}
				
      $strSQL .= " ORDER BY A.COD_ARQUIVO DESC";
    }
  else
    {   
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO".
	" FROM arquivo A" .
	" WHERE A.COD_ARQUIVO = " . $cod_arquivo;	
    }

  return mysql_query($strSQL);					
}

//======================================================================================================

function AvaliacaoAltera($cod_arquivo, $desc_arquivo, $caminho, $tamanho, $tipo)
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

function AvaliacaoInsere($desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "INSERT INTO arquivo (COD_PESSOA, CAMINHO_LOCAL_ARQUIVO, TAMANHO_ARQUIVO, TIPO_ARQUIVO, DESC_ARQUIVO) " .
    " VALUES (". $_SESSION["COD_PESSOA"] .",'". $caminho . "','". $tamanho ."','". $tipo ."','". $desc_arquivo ."')";
  
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

//======================================================================================================

function AvaliacaoExclue($cod_arquivo)
{
  $strSQL = "DELETE FROM arquivo WHERE COD_ARQUIVO = " . $cod_arquivo;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function AvaliacaoCodigo($desc_arquivo, $caminho, $tamanho, $tipo)
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

function AvaliacaoLocalInsere($cod_arquivo, $inst, $acesso, $desc_arquivo)
{
  $strSQL = "INSERT INTO avaliacao_instancia_2 (COD_ARQUIVO, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO , DESC_AVALIACAO_INSTANCIA) " .
    " VALUES (". $cod_arquivo .",". $inst .",". $acesso .", '". $desc_arquivo . "' )";
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function AvaliacaoLocalRemove($cod_arquivo, $inst, $acesso)
{
  $strSQL = "DELETE FROM avaliacao_instancia_2 WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function AvaliacaoLocalAltera($cod_arquivo, $inst, $desc_arquivo_instancia, $acesso, $tipo_acesso_novo)
{
  $strSQL = "UPDATE avaliacao_instancia_2 SET DESC_AVALIACAO_INSTANCIA = '". $desc_arquivo_instancia ."', COD_TIPO_ACESSO=".$tipo_acesso_novo .
    " WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}


//======================================================================================================

/*function listaAvaliacaoLocal($cod_arquivo)
{
  $strSQL = "SELECT DISCIPLINA.COD_CURSO, AVALIACAO_TURMA_2.COD_TURMA, TURMA.COD_DIS, COD_TIPO_ACESSO AS ACESSO, DESC_AVALIACAO_TURMA FROM AVALIACAO_TURMA_2 , TURMA, DISCIPLINA " .
    " WHERE DISCIPLINA.COD_DIS = TURMA.COD_DIS AND TURMA.COD_TURMA = AVALIACAO_TURMA_2.COD_TURMA AND COD_ARQUIVO = ". $cod_arquivo .
    " ORDER BY AVALIACAO_TURMA_2.COD_TURMA, COD_TIPO_ACESSO";
	
  return mysql_query($strSQL);					
}*/

//lista os locais em q determinado video esta publicado
function listaAvaliacaoLocal($codArquivo) {
	return listaLocal("avaliacao_instancia_2","COD_ARQUIVO",$codArquivo,"DESC_AVALIACAO_INSTANCIA");
}



//======================================================================================================
// aulas/index.asp
// Retorna os videos disponiveis para uma turma

function AvaliacaoAulas($acesso)
{
  $strSQL = "SELECT A.COD_ARQUIVO, A.DESC_ARQUIVO, AT.DESC_AVALIACAO_INSTANCIA FROM arquivo A, avaliacao_instancia_2 AT WHERE AT.COD_INSTANCIA_GLOBAL = " .  $_SESSION["codInstanciaGlobal"] . " AND A.COD_ARQUIVO = AT.COD_ARQUIVO AND (AT.COD_TIPO_ACESSO =" . $acesso . " OR AT.COD_TIPO_ACESSO=3 ) GROUP BY COD_ARQUIVO ORDER BY AT.DESC_AVALIACAO_INSTANCIA ";

  return mysql_query($strSQL);	
}
 
//======================================================================================================
// tools/video_operacao.asp;
// aulas/download.asp; aulas/redireciona.asp; aulas/topo.asp; aulas/video.asp
// Retorna o caminho para um determinado cod_video
//retirei join com turma e disciplina
function AvaliacaoCaminho($cod_arquivo)
{	
  $strSQL = "SELECT A.COD_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.DESC_ARQUIVO, AT.COD_TIPO_ACESSO, AT.COD_INSTANCIA_GLOBAL, AT.DESC_AVALIACAO_INSTANCIA, A.TIPO_ARQUIVO".
    " FROM arquivo A, avaliacao_instancia_2 AT".
    " WHERE A.COD_ARQUIVO = AT.COD_ARQUIVO AND AT.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"].
  	" AND A.COD_ARQUIVO = " . $cod_arquivo;
			  
  if ($cod_arquivo == "")
    $strSQL = "SELECT * FROM arquivo WHERE 0";
		
  return mysql_query($strSQL);	
}


//===================================================================================================

?>