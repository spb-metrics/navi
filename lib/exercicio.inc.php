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


/** FUNCOES PARA MANIPULACAO DE EXERCICIOS
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
    Funcoes : 
      - listaExerAdm
      - listaExer
      - ExerAulas
      - ExerVerificaAcesso
      - ExerLocalInsere
      - listaExerLocal
      - ExerAltera
      - ExerInsere
      - ExerCodigo
      - ExerExclue
      - ExerLocalRemove
      - ExercicioAltera
      - ExercicioInsere
      - ExercicioApaga

    Verificar funcoes possivelmente duplicadas : 
      - ExerAltera / ExercicioAltera
      - ExerInsere / ExercicioInsere
      - ExerExclue / ExercicioApaga
**/

//======================================================================================================
function listaExerAdm($codInstanciaGlobal, $local, $quem)
{
  $strSQL = "SELECT DISTINCT A.DESC_ARQUIVO, A.COD_ARQUIVO";

  if ($local == "instanciaAtual")
    {
      $strSQL .= " FROM arquivo A, exercicio_instancia ET WHERE A.COD_ARQUIVO = ET.COD_ARQUIVO";
		
      if ($codInstanciaGlobal != "")
	$strSQL .= " AND ET.COD_INSTANCIA_GLOBAL = '" . $codInstanciaGlobal . "'";
    }

  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT ET.COD_ARQUIVO FROM exercicio_instancia ET");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";
    }

  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT ET.COD_ARQUIVO FROM exercicio_instancia ET");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";	
    }

  if ($local == "")
	
    $strSQL .= " FROM arquivo A, exercicio_instancia ET WHERE A.COD_ARQUIVO = ET.COD_ARQUIVO";

  //		$strSQL .= " FROM arquivo A";

  if ($quem != "")
    $strSQL = "SELECT * FROM arquivo A WHERE A.COD_PESSOA = '". $quem ."'";
		
  $strSQL .= " ORDER BY A.COD_ARQUIVO";

  return mysql_query($strSQL);					
}

//======================================================================================================
// Retorna um ResultSet com os textos

function listaExer($cod_arquivo, $codInstanciaGlobal, $acesso)
{    
                 
  if ($codInstanciaGlobal != "")
    {
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO, ET.COD_TIPO_ACESSO, ET.DESC_EXERCICIO_INSTANCIA " . 		
	" FROM arquivo A, exercicio_instancia ET" .
	" WHERE A.COD_ARQUIVO = ET.COD_ARQUIVO" .
	" AND ET.COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal.
	" AND ET.COD_ARQUIVO = " . $cod_arquivo;

      if ($cod_arquivo != "")
	$strSQL .= " AND A.COD_ARQUIVO =" . $cod_arquivo;
      else
	{		
	  if ($acesso != "")
	    $strSQL .= " AND (ET.COD_TIPO_ACESSO =" . $acesso . " OR ET.COD_TIPO_ACESSO=3 )";	
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
// exercicio/index.asp; exercicio/corpo.asp; exercicio/topo.asp
// tools/exercicio_operacao.asp
//tirei join com turma e disciplina
function ExerAulas($codArquivo) 
{	
	 
  if ($codArquivo != "")
    {
      $strSQL = "SELECT ET.COD_ARQUIVO, ET.DESC_EXERCICIO_INSTANCIA, A.DESC_ARQUIVO, ET.COD_INSTANCIA_GLOBAL,".
	" A.COD_PESSOA,A.CAMINHO_LOCAL_ARQUIVO, A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO ".
	" FROM arquivo A, exercicio_instancia ET WHERE ".
	" A.COD_ARQUIVO = ET.COD_ARQUIVO ".
	" AND A.COD_ARQUIVO =". $codArquivo;
    }
  else
    $strSQL = "SELECT A.COD_PESSOA,A.COD_ARQUIVO, ET.COD_INSTANCIA_GLOBAL, ET.DESC_EXERCICIO_INSTANCIA, ET.COD_TIPO_ACESSO,   A.CAMINHO_LOCAL_ARQUIVO FROM arquivo A, exercicio_instancia ET WHERE ET.COD_INSTANCIA_GLOBAL = '" . $_SESSION["codInstanciaGlobal"] . "' AND ET.COD_ARQUIVO = A.COD_ARQUIVO ORDER BY DESC_EXERCICIO_INSTANCIA";

  return mysql_query($strSQL);					
}


//======================================================================================================
function ExerVerificaAcesso($cod_arquivo)
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
      // Verifica se o exercicio é de alguma turma na qual ele é professor
			$strSQL = "SELECT EI.COD_ARQUIVO FROM exercicio_instancia EI WHERE EI.COD_ARQUIVO=".quote_smart($cod_arquivo).
					" AND EI.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"];
				
      $rsCon = mysql_query($strSQL);

      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }

  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 ) and (! $permite) )
    {
      // Verifica se o exercicio é da alguma turma cujo curso ele é adm
      $strSQL = "SELECT ET.COD_ARQUIVO FROM exercio_turma ET, turma T, disciplina D, administrador_curso AC".
	" WHERE ET.COD_ARQUIVO = '". $cod_arquivo ."'".
	" AND AC.COD_ADM = '". $_SESSION["COD_ADM"] ."' AND AC.COD_CURSO = D.COD_CURSO".
	" AND D.COD_DIS = T.COD_DIS AND T.COD_TURMA = ET.COD_TURMA";
				  
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
function ExerLocalInsere($cod_arquivo, $inst, $acesso, $desc_arquivo)
{
  mysql_query("INSERT INTO exercicio_instancia (COD_ARQUIVO, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO, DESC_EXERCICIO_INSTANCIA) VALUES (' $cod_arquivo ',' $inst ',' $acesso ',' $desc_arquivo  ')");
  return (! mysql_errno());
}

//======================================================================================================
/*function listaExerLocal($cod_arquivo)
{
  $strSQL = "SELECT DISCIPLINA.COD_CURSO, EXERCICIO_TURMA.COD_TURMA, TURMA.COD_DIS, COD_TIPO_ACESSO AS ACESSO, DESC_EXERCICIO_TURMA FROM EXERCICIO_TURMA , TURMA, DISCIPLINA " .
    " WHERE DISCIPLINA.COD_DIS = TURMA.COD_DIS AND TURMA.COD_TURMA = EXERCICIO_TURMA.COD_TURMA AND COD_ARQUIVO = ". $cod_arquivo .
    " ORDER BY EXERCICIO_TURMA.COD_TURMA, COD_TIPO_ACESSO";
	
  return mysql_query($strSQL);
}*/
//lista os locais em q determinado exercicio esta publicado
function listaExerLocal($codArquivo) {
	return listaLocal("exercicio_instancia","COD_ARQUIVO",$codArquivo,"DESC_EXERCICIO_INSTANCIA");
}

//======================================================================================================
function ExerAltera($cod_arquivo, $desc_arquivo, $caminho, $tamanho, $tipo)
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
function ExerInsere($desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "INSERT INTO arquivo (COD_PESSOA, CAMINHO_LOCAL_ARQUIVO, TAMANHO_ARQUIVO, TIPO_ARQUIVO, DESC_ARQUIVO) " .
    " VALUES (". $_SESSION["COD_PESSOA"] .",'". $caminho . "','". $tamanho ."','". $tipo ."','". $desc_arquivo ."')";
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

//======================================================================================================
function ExerCodigo($desc_arquivo, $caminho, $tamanho, $tipo)
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
function ExerExclue($cod_arquivo)
{
  $strSQL = "DELETE FROM arquivo WHERE COD_ARQUIVO = " . $cod_arquivo;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

function ExerLocalRemove($cod_arquivo, $inst, $acesso)
{
  $strSQL = "DELETE FROM exercicio_instancia WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

//======================================================================================================
// tools/exercicio_envio.asp
// Grava imformações no Banco

// ATENCAO - PQ apagar todas referencias e criar uma nova? nao seria somente atualizar?

function ExercicioAltera($ARQ, $INST, $PESSOA, $DESC, $DESCINST, $CAMINHO, $TAM, $TIPO)
{
  $strSQL = "UPDATE arquivo SET " .
    " COD_PESSOA  = '" . $PESSOA . "', " .
    " CAMINHO_LOCAL_ARQUIVO = '" . $CAMINHO . "', " .
    " TIPO_ARQUIVO  = '" . $TIPO . "', " .
    " DESC_ARQUIVO  = '" . $DESC . "', " .
    " TAMANHO_ARQUIVO  = " . $TAM . " " .
    " WHERE COD_ARQUIVO  = " . $ARQ ;

  mysql_query($strSQL);
	
  if (! mysql_errno())
    {
      $strSQL = "DELETE FROM exercicio_instancia WHERE COD_ARQUIVO = " . $ARQ;
		
      mysql_query($strSQL);
		
      if (! mysql_errno())
	{
	  $strSQL = "INSERT INTO exercicio_instancia (COD_ARQUIVO, COD_INSTANCIA_GLOBAL, DESC_EXERCICIO_INSTANCIA ) " .
	    " VALUES (". $ARQ .",". $INST . ",'". $DESCINST . "')";
			
	  mysql_query($strSQL);
	  return (! mysql_errno());
	}
      else
	return false;
    }
  else
    return false;
}

//======================================================================================================
// tools/exercicio_envio.asp; tools/exercicio.asp
// Apaga informações no Banco

// ATENCAO - apagar as refenrencias nao é feito pelo banco de dados?

function ExercicioApaga($ARQ)
{
  $strSQL = "DELETE FROM arquivo WHERE COD_ARQUIVO = " . $ARQ;
  mysql_query($strSQL);
	
  if (! mysql_errno())
    {
      $strSQL = "DELETE FROM exercicio_instancia WHERE COD_ARQUIVO = " . $ARQ;
      mysql_query($strSQL);

      return (! mysql_errno());
    }
  else
    return false;
}
 
//======================================================================================================
// tools/exercicio_envio.asp
// Grava informações no Banco

function ExercicioInsere($INST, $PESSOA, $DESC, $DESCINST, $CAMINHO, $TAM, $TIPO)
{
  $strSQL = "SELECT COD_ARQUIVO FROM arquivo ORDER BY COD_ARQUIVO DESC LIMIT 0, 1 ";
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
	
  $ARQ = $linha["COD_ARQUIVO"] + 1;

  $strSQL = "INSERT INTO arquivo (COD_ARQUIVO, COD_PESSOA, CAMINHO_LOCAL_ARQUIVO, TIPO_ARQUIVO, DESC_ARQUIVO, TAMANHO_ARQUIVO)" .
    " VALUES (". $ARQ ."," . $PESSOA . ", '" . $CAMINHO . "', '" . $TIPO . "', '" . $DESC . "', " . $TAM . ") ";

  mysql_query($strSQL);
				  
  if (! mysql_errno())
    {
      $strSQL = "INSERT INTO exercicio_instancia (COD_ARQUIVO, COD_INSTANCIA_GLOBAL, DESC_EXERCICIO_INSTANCIA ) " .
	" VALUES (". $ARQ .",". $INST . ",'". $DESCINST . "')";
				  
      mysql_query($strSQL);
				  
      if (mysql_errno())
	{
	  mysql_query("DELETE FROM arquivo WHERE COD_ARQUIVO = " . $ARQ);

	  return false;
	}
      else
	return true;
    }
  else
    return false;
}
//=================================================================================================
function ExerLocalAltera($cod_arquivo, $inst, $desc_exercicio_inst, $acesso, $tipo_acesso_novo)
{
  $strSQL = "UPDATE exercicio_instancia SET DESC_EXERCICIO_INSTANCIA = '". $desc_exercicio_inst ."', COD_TIPO_ACESSO=".$tipo_acesso_novo .
    " WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}
?>