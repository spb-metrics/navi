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


/** FUNCOES PARA MANIPULACAO DE CASOS
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA
    
    Funcoes : 
    - caso
    - casoAcesso
    - casoAutores
    - casoAltera
    - casoApaga
    - casoComentario
    - casoEnvia
    - casoEnviaCom
    - casoLista
    - listaCaso

    Duvidas : pq casoLita e listaCaso???

**/


//======================================================================================================
// casos/lista.asp; casos/casos_mostrar.asp
// Estudo de casos

function caso($cod)
{
  $strSQL = "SELECT * FROM CASO WHERE COD_CASO = ". $cod; 
  return mysql_query($strSQL);	
}

//======================================================================================================
// casos/casos_mostrar.asp
// Estudo de casos

function casoAcesso($verifica_autor, $cod_caso)
{
  // Verifica se o caso que se quer esta acessando é da mesma turma onde a pessoa esta no momento
  if ($verifica_autor == "") 
    {
      $strSQL = "SELECT COD_CASO FROM CASO WHERE COD_CASO=". $cod_caso ." AND COD_TURMA=". $_SESSION["COD_TURMA"];
      $rsCon  = mysql_query($strSQL);
			
      if ($rsCon and (mysql_num_rows($rsCon) > 0) )
	return true;
      else
	return false;
    }
  else
    {
      // Verifica se a pessoa que quer esta acessando é um dos autores da noticia
      if ($_SESSION["COD_PESSOA"] == "")
	return false;
      else
	{
	  $strSQL = "SELECT COD_CASO FROM CASO_AUTOR WHERE COD_CASO=". $cod_caso ." AND COD_PESSOA=". $_SESSION["COD_PESSOA"];
	  $rsCon  = mysql_query($strSQL);
				
	  if ($rsCon and (mysql_num_rows($rsCon) > 0) )
	    return true;
	  else
	    return false;
	}
    }
}

//======================================================================================================
// casos/casos_mostrar.asp
// Estudo de casos

function casoAutores($cod)
{
  $strSQL = "SELECT P.NOME_PESSOA, P.COD_PESSOA".
    " FROM caso C, caso_autor CA, PESSOA P".
    " WHERE P.COD_PESSOA = CA.COD_PESSOA AND C.COD_CASO = CA.COD_CASO".
    " AND C.COD_CASO = ". $cod;
			 
  return mysql_query($strSQL);	
}

//======================================================================================================
// casos/alterar.asp
// Estudo de casos

function casoAltera($cod_caso, $autor, $titulo, $diagnostico, $objetivos, $metas, $equipe, $orcamento, $cronograma , $metodologia)
{
  $strSQL = "DELETE FROM caso_autor WHERE cod_caso =" . $cod_caso;
	
  $rsCon  = mysql_query($strSQL);
				
  if (! mysql_errno())
    {
      $strSQL = "UPDATE caso SET cod_turma=". $_SESSION["COD_TURMA"] .", titulo='". $titulo ."', diagnostico='". $diagnostico ."', objetivos='". $objetivos ."', metas='". $metas ."', equipe='". $equipe ."', orcamento='". $orcamento ."', cronograma='". $cronograma ."', metodologia='". $metodologia ."' WHERE cod_caso=" . $cod_caso;	

      $rsCon  = mysql_query($strSQL);
		
      if (! mysql_errno())
	{
	  $erro  = false;
				
	  for ($i=0; $i < count($autor); $i++)
	    {
	      $strSQL = "INSERT INTO caso_autor (COD_CASO, COD_PESSOA) VALUES (". $cod_caso .",". $autor[$i] . ")";
	      $rsCon  = mysql_query($strSQL);
		
	      if (mysql_errno())
		{
		  $erro = true;
		  break;
		}
	    }
				
	  if ($erro)
	    return -1;
	  else
	    return $cod_caso;
	}
      else 
	return -1;
    }
  else 
    return -1;
}

//======================================================================================================
// casos/casos_apagar.asp
// Estudo de casos

function casoApaga($cod)	
{
  if ($cod != "")
    {
      $strSQL = "SELECT C.COD_CASO FROM CASO C, CASO_AUTOR CA WHERE C.COD_CASO = CA.COD_CASO AND C.COD_CASO=". $cod ." AND CA.COD_PESSOA='". $_SESSION["COD_PESSOA"] . "'";
      $rsCon  = mysql_query($strSQL);
		
      if (! $rsCon)
	return false;
      else
	{
	  $strSQL = "DELETE FROM caso WHERE COD_CASO=". $cod; 
	  return mysql_query($strSQL);
	}
    }
  else
    return false;
}
 
//======================================================================================================
// casos/casos_mostrar.asp
// Estudo de casos

function casoComentario($cod) {
  $strSQL = "SELECT CM.COD_CASO , CM.COD_COM, CM.TEXTO, DATE_FORMAT(DATA, '%d/%m/%Y') AS DATA_MODIFICADA, P.NOME_PESSOA, P.COD_PESSOA".
    " FROM CASO_COMENTARIO CM, PESSOA P".
    " WHERE CM.COD_PESSOA = P.COD_PESSOA AND CM.COD_CASO = ". $cod; 
  return mysql_query($strSQL);
}

//======================================================================================================
// casos/casos_enviar.asp
// Estudo de casos

function casoEnvia($autor, $titulo, $diagnostico, $objetivos, $metas, $equipe, $orcamento, $cronograma , $metodologia)
{
  $strSQL = "INSERT INTO caso (cod_turma, titulo, diagnostico, objetivos, metas, equipe, orcamento, cronograma , metodologia)" .
    " VALUES (" . $_SESSION["COD_TURMA"] . ", '". $titulo ."','". $diagnostico ."','". $objetivos ."','". $metas ."','". $equipe ."','". $orcamento ."','". $cronograma ."','". $metodologia ."')";

  mysql_query($strSQL);

  if (!mysql_errno())
    {	
      $strSQL = "SELECT COD_CASO FROM CASO WHERE COD_TURMA=". $_SESSION["COD_TURMA"] ." AND TITULO='". $titulo ."' AND DIAGNOSTICO='". $diagnostico ."' AND OBJETIVOS='". $objetivos ."' AND METAS='". $metas ."' AND EQUIPE='". $equipe ."' AND ORCAMENTO='". $orcamento ."' AND CRONOGRAMA='". $cronograma ."' AND METODOLOGIA='". $metodologia ."'";
      $rsCon  = mysql_query($strSQL);
		
      if (! $rsCon)
	return -2;
      else
	{
	  $linha = mysql_fetch_array($rsCon);		
	  $erro  = false;
				
	  for ($i=0; $i < count($autor); $i++)
	    {
	      $strSQL = "INSERT INTO caso_autor (COD_CASO, COD_PESSOA) VALUES (". $linha["COD_CASO"] .",". $autor[$i] . ")";
	      mysql_query($strSQL);
				
	      if (mysql_errno())
		{
		  echo "ERRO na Inserção<br> <a href=\"javascript:history.back()\">Voltar</a>";
		  $erro = true;
		  break;
		}
	    }
			
	  if ($erro)
	    return -3;
	  else
	    return $linha["COD_CASO"];
	}
    }
  else 
    return -1;
}

//======================================================================================================
// casos/envia_com.asp
// Estudo de casos

function casoEnviaCom($cod, $text)
{
  $strSQL = "INSERT INTO caso_comentario (COD_CASO, COD_PESSOA, TEXTO)" .
    " VALUES (". $cod ."," . $_SESSION["COD_PESSOA"] . ", '" . $text . "')";
	
  mysql_query($strSQL);		
	
  return (! mysql_errno());
}

//======================================================================================================
// casos/casos_listar.asp;
// Estudo de casos

function casoLista($order)
{

  // Lista os estudos de caso pelo nome das pessoas
  if ($order == "nome")
    {
      $strSQL = "SELECT CA.COD_PESSOA, C.COD_CASO, C.TITULO, P.NOME_PESSOA".
	" FROM CASO C".
	" LEFT JOIN".
	" PESSOA P, CASO_AUTOR CA".
	" ON C.COD_CASO = CA.COD_CASO".
	" WHERE C.COD_TURMA = '" . $_SESSION["COD_TURMA"] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IS NOT NULL ORDER BY P.NOME_PESSOA";
    }				 
  else
    {
      // Lista os estudos de caso pelo seu titulo
      //		$strSQL = "SELECT CA2.COD_PESSOA AS COD_MEU_CASO, CA.COD_PESSOA, C.COD_CASO, C.TITULO, P.NOME_PESSOA".
      //				  " FROM CASO C".
      //				  " LEFT JOIN".
      //						" PESSOA P, CASO_AUTOR CA".
      //				  " ON C.COD_CASO = CA.COD_CASO".
      //				  " LEFT JOIN CASO_AUTOR CA2".
      //				  " ON CA2.COD_CASO = CA.COD_CASO AND CA2.COD_PESSOA =". $_SESSION["COD_PESSOA"].
      //				  " WHERE C.COD_TURMA = '" . $_SESSION["COD_TURMA"] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IS NOT NULL ORDER BY C.COD_CASO DESC";
      $strSQL = "SELECT CA.COD_PESSOA, C.COD_CASO, C.TITULO, P.NOME_PESSOA".
	" FROM CASO C".
	" LEFT JOIN".
	" PESSOA P, CASO_AUTOR CA".
	" ON C.COD_CASO = CA.COD_CASO".
	" WHERE C.COD_TURMA = '" . $_SESSION["COD_TURMA"] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IS NOT NULL ORDER BY C.COD_CASO DESC";

    }
  echo $strSQL;
  return mysql_query($strSQL);		
}


function listaCaso($order)
{
  if ($order == "nome")
    $strSQL = "SELECT C.COD_CASO, C.TITULO, P.NOME_PESSOA FROM CASO C, CASO_ALUNO CA, ALUNO A, ALUNO_TURMA AT, PESSOA P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = AT.COD_AL AND A.COD_AL = CA.COD_AL AND C.COD_CASO = CA.COD_CASO AND AT.COD_TURMA = '". $_SESSION["COD_TURMA"] ."' ORDER BY P.NOME_PESSOA";
  else
    {
      $strSQL = "SELECT C.COD_CASO  FROM CASO C, CASO_ALUNO CA, ALUNO A, ALUNO_TURMA AT, PESSOA P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = AT.COD_AL AND A.COD_AL = CA.COD_AL AND AT.COD_TURMA = '". $_SESSION["COD_TURMA"] ."' group BY C.COD_CASO";
      $rsCon  = mysql_query($strSQL);
			
      $casos = "(";
			
      while ($linha = mysql_fetch_array($rsCon))
	$casos = $casos . $linha["COD_CASO"] . ",";
			
      $strSQL = "SELECT C.COD_CASO, C.TITULO, P.NOME_PESSOA  FROM CASO C, CASO_ALUNO CA, ALUNO A, PESSOA P WHERE A.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IN ". $casos ."0) AND A.COD_AL = CA.COD_AL AND C.COD_CASO = CA.COD_CASO ".
	" ORDER BY C.TITULO";
    }

  return mysql_query($strSQL);
}
 

?>