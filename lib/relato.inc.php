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

   
/** FUNCOES PARA A MANIPULACAO DE RELATOS
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA
    Funcoes : 
      - Relato
      - RelatoAluno
      - RelatoCom
      - RelatoEnvia
      - RelatoEnviaAluno
      - RelatoEnviaCom  
      - listaRelato
**/
/*reformulação
function Relato($cod)
{
	echo $cod."to no funcoes";
  $strSQL = "SELECT R.COD_RELATO, R.TITULO, R.TEXTO  FROM relato R WHERE R.COD_RELATO = ". $cod; 

  return mysql_query($strSQL);
}

//======================================================================================================
// relatos/mostra.asp;
// Estudo de casos

function RelatoAluno($cod)
{
  $strSQL = "SELECT P.NOME_PESSOA, A.COD_AL, P.COD_PESSOA FROM relato R, RELATO_ALUNO RA, ALUNO A, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = RA.COD_AL AND R.COD_RELATO = RA.COD_RELATO AND R.COD_RELATO = ". $cod;

  return mysql_query($strSQL);
}

//======================================================================================================
// relatos/mostra.asp
// Estudo de casos

function RelatoCom($cod)
{
  $strSQL = "SELECT RM.COD_RELATO , RM.COD_COM, RM.TEXTO, RM.DATA, P.NOME_PESSOA, P.COD_PESSOA FROM relato_comentario RM, pessoa P WHERE RM.COD_PESSOA = P.COD_PESSOA AND RM.COD_RELATO = ". $cod ;

  return mysql_query($strSQL);
}

//======================================================================================================
// relatos/envia_relato.asp
// Estudo de casos

function RelatoEnvia($autor, $titulo, $texto)


{
	 $strSQL = "INSERT INTO relato (COD_RELATO, TITULO, TEXTO, codInstanciaGlobal)" .
    " VALUES (". $COD .",'". str_replace("'","''",$titulo) ."','". str_replace("'","''",$texto)  ."','".$_SESSION["codInstanciaGlobal"]."')";
  mysql_query($strSQL);

if (! mysql_errno()) 
	{echo "passei do erro";
		$strSQL = "SELECT R.COD_RELATO FROM relato WHERE codInstanciaGlobal=".$_SESSION["codInstanciaGlobal"]."AND TITULO=".$titulo."AND TEXTO=".$texto."ORDER BY COD_RELATO DESC LIMIT 0, 1 ";
		$rsCon = mysql_query($strSQL);
		if(!$rsCon)
			return -2;
		else
		{
		$linha = mysql_fetch_array($rsCon);		
		$erro  = false;
				
		for ($i=0; $i < count($autor); $i++)
			{
				$strSQL = "INSERT INTO RELATO_ALUNO (COD_RELATO, COD_AL) VALUES (". $linha["COD_RELATO"] .",". $autor[$i] . ")";
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
			return $linha["COD_RELATO"];
		}
    }
  else 
    return -1; 
  
}
//======================================================================================================
// relatos/envia_relato.asp
// Estudo de casos

function RelatoEnviaAluno($cod, $al)
{
  $strSQL = "INSERT INTO RELATO_ALUNO (COD_RELATO, COD_AL)" .
    " VALUES (". $cod .",". $al . ")";
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

//======================================================================================================
// relatos/envia_com.asp
// Estudo de casos

function RelatoEnviaCom($cod, $text)
{
  $strSQL = "INSERT INTO relato_comentario (COD_RELATO, COD_PESSOA, TEXTO)" .
    " VALUES (". $cod ."," . $_SESSION["COD_PESSOA"] . ", '" . str_replace("'","''",$text) . "')";
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


function listaRelato($order)
{echo $order."to no funcoes 3";
  if ($order == "nome")
    $strSQL = "SELECT R.COD_RELATO, R.TITULO , P.NOME_PESSOA FROM relato R, RELATO_ALUNO RA, ALUNO A, ALUNO_TURMA AT, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = AT.COD_AL AND A.COD_AL = RA.COD_AL AND R.COD_RELATO = RA.COD_RELATO AND AT.codInstanciaGlobal = '". $_SESSION["codInstanciaGlobal"] ."' ORDER BY P.NOME_PESSOA";
  else
    {
      $strSQL = "SELECT R.COD_RELATO ,R.TITULO FROM relato R, RELATO_ALUNO RA, ALUNO A, ALUNO_TURMA AT, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = AT.COD_AL AND A.COD_AL = RA.COD_AL AND AT.codInstanciaGlobal = '". $_SESSION["codInstanciaGlobal"] ."' group BY R.COD_RELATO";
      $rsCon = mysql_query($strSQL);
	 if($rsCon)
		{		
		  $relatos = "(";
				
		  while ($linha = mysql_fetch_array($rsCon))
				 $relatos .= $linha["COD_RELATO"] . ",";
				
		  $strSQL = "SELECT R.COD_RELATO, R.TITULO, P.NOME_PESSOA  FROM relato R, RELATO_ALUNO RA, ALUNO A, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND R.COD_RELATO IN ". $relatos ."0) AND A.COD_AL = RA.COD_AL AND R.COD_RELATO = RA.COD_RELATO ORDER BY R.titulo";
		}
   }

  return mysql_query($strSQL);					
}

*/


/*=================================================================================================================
 funções para relatos estilo casos, um caso em específico(cópia de casos)
=================================================================================================================*/

function relatoAcesso($verifica_autor, $cod_relato)
{
  // Verifica se o relato que se quer esta acessando é da mesma turma onde a pessoa esta no momento
  if ($verifica_autor == "") 
    {
      $strSQL = "SELECT COD_RELATO FROM relato WHERE COD_RELATO=". $cod_relato ." AND codInstanciaGlobal=". $_SESSION["codInstanciaGlobal"];
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
	        $strSQL = "SELECT COD_RELATO FROM relato_autor WHERE COD_RELATO=". $cod_relato ." AND COD_PESSOA=". $_SESSION["COD_PESSOA"];
	        $rsCon  = mysql_query($strSQL);
				
       	  if ($rsCon and (mysql_num_rows($rsCon) > 0) )
	          return true;
	        else
	          return false;
	}
    }
}
//=================================================================================================================
function relatoAltera($cod_relato, $autor, $titulo, $texto,$emConstrucao)
{
  //arruma os autores, somente se for necessário
  if (!empty($autor)) {
    $strSQL = "DELETE FROM relato_autor WHERE cod_relato =" . $cod_relato;

	  for ($i=0; $i < count($autor); $i++) {
	    $strSQL = "INSERT INTO relato_autor (COD_RELATO, COD_PESSOA) VALUES (". $cod_relato .",". $autor[$i] . ")";
	    $rsCon  = mysql_query($strSQL);
		
	    if (mysql_errno())	{
		    $erro = true;
		    break;
		  }
    }
  }
  $rsCon  = mysql_query($strSQL);

        
  $strSQL = "UPDATE relato SET codInstanciaGlobal=". $_SESSION["codInstanciaGlobal"] .", titulo='". $titulo ."', texto='". $texto ."', emConstrucao=".$emConstrucao;
  $strSQL.=" WHERE COD_RELATO=".$cod_relato;

  $rsCon  = mysql_query($strSQL);
		
  if (! mysql_errno())	{
	  $erro  = false;			
	}
				
	if ($erro) {
	    return -1;
  } 
	else {
	    return $cod_relato;
	}
}
//=================================================================================================================

function relato($cod)
{
  $strSQL = "SELECT COD_RELATO, TITULO, TEXTO, codInstanciaGlobal, emConstrucao,  DATE_FORMAT(DATA, '%d/%m/%Y') AS DATA_MODIFICADA  FROM relato WHERE COD_RELATO = ". $cod; 
  return mysql_query($strSQL);	
}
//=================================================================================================================

function relatoAutores($cod)
{
  $strSQL = "SELECT P.NOME_PESSOA, P.COD_PESSOA".
    " FROM relato R, relato_autor RA, pessoa P".
    " WHERE P.COD_PESSOA = RA.COD_PESSOA AND R.COD_RELATO = RA.COD_RELATO".
    " AND R.COD_RELATO = ". $cod;
			 
  return mysql_query($strSQL);	
}
//=================================================================================================================
function relatoApaga($cod)	
{
  if ($cod != "")
    {
      $strSQL = "SELECT R.COD_RELATO FROM relato R, relato_autor RA WHERE R.COD_RELATO = RA.COD_RELATO AND R.COD_RELATO=". $cod ." AND RA.COD_PESSOA='". $_SESSION["COD_PESSOA"] . "'";
      $rsCon  = mysql_query($strSQL);
		
      if (! $rsCon)
	return false;
      else
	{
	  $strSQL = "DELETE FROM relato WHERE COD_relato=". $cod; 
	  return mysql_query($strSQL);
	}
    }
  else
    return false;
}
//=================================================================================================================
function relatoEnviaCom($cod, $text)
{
  $strSQL = "INSERT INTO relato_comentario (COD_RELATO, COD_PESSOA, TEXTO)" .
    " VALUES (". $cod ."," . $_SESSION["COD_PESSOA"] . ", '" . $text . "')";
	
  mysql_query($strSQL);		
	
  return (! mysql_errno());
}
//=================================================================================================================
function relatoEnvia($autor, $titulo, $texto,$emConstrucao)
{

	$strSQL = "INSERT INTO relato (codInstanciaGlobal, titulo, texto, emConstrucao)" .
    " VALUES (" . $_SESSION["codInstanciaGlobal"] . ", ". quote_smart($titulo) .",". quote_smart($texto) .",".$emConstrucao.")";
 	mysql_query($strSQL);
	$last_id=mysql_insert_id();

	if ($last_id==0)
		return -2;
	else
	{
	  		
	  $erro  = false;
				
	  for ($i=0; $i < count($autor); $i++)
	  {
	      $strSQL = "INSERT INTO relato_autor (COD_RELATO, COD_PESSOA) VALUES (". $last_id.",". $autor[$i] . ")";
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
	    return $last_id;
	}
  
}
//=================================================================================================================
function relatoLista($order)
{
  /*
  // Lista os estudos de relato pelo nome das pessoas
  if ($order == "nome")
    {
    
      $strSQL = "SELECT RA.COD_PESSOA, R.COD_RELATO, R.TITULO, P.NOME_PESSOA".
	" FROM relato R".
	" LEFT JOIN".
	" pessoa P, relato_autor RA".
	" ON R.COD_RELATO = RA.COD_RELATO".
	" WHERE R.codInstanciaGlobal = '" . $_SESSION["codInstanciaGlobal"] . "' AND RA.COD_PESSOA = P.COD_PESSOA AND R.COD_RELATO IS NOT NULL ORDER BY P.NOME_PESSOA";
  
        
    }				 
  else
    {
      // Lista os estudos de relato pelo seu titulo
      //		$strSQL = "SELECT CA2.COD_PESSOA AS COD_MEU_RELATO, CA.COD_PESSOA, C.COD_RELATO, C.TITULO, P.NOME_PESSOA".
      //				  " FROM relato C".
      //				  " LEFT JOIN".
      //						" pessoa P, relato_autor CA".
      //				  " ON C.COD_RELATO = CA.COD_RELATO".
      //				  " LEFT JOIN relato_autor CA2".
      //				  " ON CA2.COD_RELATO = CA.COD_RELATO AND CA2.COD_PESSOA =". $_SESSION["COD_PESSOA"].
      //				  " WHERE C.codInstanciaGlobal = '" . $_SESSION["codInstanciaGlobal"] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_RELATO IS NOT NULL ORDER BY C.COD_RELATO DESC";
      $strSQL = "SELECT RA.COD_PESSOA, R.COD_RELATO, R.TITULO, P.NOME_PESSOA".
	" FROM relato R".
	" LEFT JOIN".
	" pessoa P, relato_autor RA".
	" ON R.COD_RELATO = RA.COD_RELATO".
	" WHERE R.codInstanciaGlobal = '" . $_SESSION["codInstanciaGlobal"] . "' AND RA.COD_PESSOA = P.COD_PESSOA AND R.COD_RELATO IS NOT NULL ORDER BY R.COD_RELATO DESC";

    }
    */

    $strSQL = "SELECT R.emConstrucao, RA.COD_PESSOA, R.COD_RELATO, R.TITULO, P.NOME_PESSOA".
    	" FROM pessoa P".
	    " INNER JOIN relato_autor RA ON (P.COD_PESSOA=RA.COD_PESSOA)".
	    " INNER JOIN relato R ON (RA.COD_RELATO=R.COD_RELATO)".
     	" WHERE R.codInstanciaGlobal = " . quote_smart($_SESSION["codInstanciaGlobal"]);

    if ($order == "nome") {
      $strSQL.=" ORDER BY P.NOME_PESSOA";
    }
    else {
      $strSQL.=" ORDER BY R.TITULO";
    }
   

  return mysql_query($strSQL);		
}



//==============================================================================================================

function relatoComentario($cod)
{
//echo $cod;

  $strSQL = "SELECT RM.COD_RELATO , RM.COD_COM, RM.TEXTO, DATE_FORMAT(DATA, '%d/%m/%Y') AS DATA_MODIFICADA, P.NOME_PESSOA, P.COD_PESSOA".
    " FROM relato_comentario RM, pessoa P".
    " WHERE RM.COD_PESSOA = P.COD_PESSOA AND RM.COD_RELATO = ". $cod; 

  return mysql_query($strSQL);
}
//================================================================================================================
?>
