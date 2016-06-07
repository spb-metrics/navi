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

include_once('CLDb.inc.php');
/** FUNCOES PARA MANIPULACAO DA ENQUETE 
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
    
    Funcoes : 
    - EnqueteInsere
    - RecebeCodEnquete
    - RecebeTextoEnquete
    - EnqueteResInsere
    - RecebeRespostaEnqute
    - EnqueteLocalInsere
    - listaEnqueteLocal
    - EnqueteVerificaAcesso
    - listaEnquete
    - EnqueteLocalRemove
    - EnqueteLocalAltera
    - EnqueteAltera
    - EnqueteRespostaAltera
    - listaEnqueteAdm
    - EnqueteExclue
    - enquete
**/

//===================================================================================================
function EnqueteInsere ($texto_enquete)
{
		
  $strSQL = "INSERT INTO enquete (TEXTO_ENQUETE,COD_PESSOA) " .
    " VALUES (". quote_smart($texto_enquete) .",".$_SESSION["COD_PESSOA"].")";
  echo $strSQL; 
  mysql_query($strSQL);
	
  return (! mysql_errno());
  
}
//===============================================================================================================
function RecebeCodEnqute ($texto_enquete)
{	

  $strSQL = "SELECT COD_ENQUETE FROM enquete WHERE TEXTO_ENQUETE = " . quote_smart($texto_enquete );
  return mysql_query($strSQL);
}
//===============================================================================================================
function RecebeTextoEnquete ($cod_enquete,$texto_enquete)
{
  $strSQL = "SELECT TEXTO_ENQUETE FROM enquete WHERE COD_ENQUETE = " . $cod_enquete." ORDER BY COD_ENQUETE";
  return mysql_query($strSQL);
}
//===============================================================================================================
function EnqueteResInsere($cod_enquete,$texto_resposta)
{
  $strSQL = "INSERT INTO enquete_resposta (COD_ENQUETE, TEXTO_RESPOSTA) " .
    " VALUES (". $cod_enquete .",". quote_smart($texto_resposta) . ")";
  mysql_query($strSQL);
	
  return (! mysql_errno());	
}
//===============================================================================================================
function RecebeRespostaEnqute ($cod_enquete)
{

  $strSQL = "SELECT  COD_RESPOSTA, TEXTO_RESPOSTA FROM enquete_resposta WHERE COD_ENQUETE = " . $cod_enquete." ORDER BY COD_ENQUETE";
  return mysql_query($strSQL);
}
//===============================================================================================================
function EnqueteLocalInsere($cod_enquete, $inst, $acesso)
{
	
  mysql_query("INSERT INTO enquete_instancia (COD_ENQUETE, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO) VALUES (' $cod_enquete ',' $inst ',' $acesso ')");
  return (! mysql_errno());
}
//===============================================================================================================
/*function listaEnqueteLocal($cod_enquete)
{

  $strSQL = "SELECT DISCIPLINA.COD_CURSO, ENQUETE_TURMA.COD_TURMA, TURMA.COD_DIS, COD_TIPO_ACESSO AS ACESSO FROM ENQUETE_TURMA , TURMA, DISCIPLINA " .
    " WHERE DISCIPLINA.COD_DIS = TURMA.COD_DIS AND TURMA.COD_TURMA = ENQUETE_TURMA.COD_TURMA AND COD_ENQUETE = ". $cod_enquete .
    " ORDER BY ENQUETE_TURMA.COD_TURMA, COD_TIPO_ACESSO";
	
  return mysql_query($strSQL);
}*/

//lista os locais onde determinada enquete esta publicada
function listaEnqueteLocal($codEnquete) {
	return listaLocal("enquete_instancia","COD_ENQUETE",$codEnquete);
}


//======================================================================================================
function EnqueteVerificaAcesso($cod_enquete)
{
  $permite = false;
		
  if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1)
    $permite = true;
	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
    {
      // Verifica se a enquete é dele
		
      $strSQL = "SELECT COD_ENQUETE FROM enquete".
	" WHERE COD_ENQUETE = '". $cod_enquete ."'";
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
      // Verifica se a enquete é de alguma turma na qual ele é professor
			$strSQL = "SELECT EI.COD_ENQUETE FROM enquete_instancia EI WHERE EI.COD_ENQUETE=".quote_smart($cod_enquete).
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
      // Verifica se a enquete é de alguma turma cujo curso ele é adm
      $strSQL = "SELECT ET.COD_ENQUETE FROM ENQUETE_TURMA ET, TURMA T, DISCIPLINA D, ADMINISTRADOR_CURSO AC".
	" WHERE ET.COD_ENQUETE = '". $cod_enquete ."'".
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
 
//===============================================================================================================
function listaEnquete($cod_enquete, $codInstanciaGlobal, $acesso)
{    
                 
  if ($codInstanciaGlobal != "")
    {
      $strSQL = "SELECT E.COD_ENQUETE,E.TEXTO_ENQUETE, ET.COD_TIPO_ACESSO " . 		
	" FROM enquete E, enquete_instancia ET" .
	" WHERE E.COD_ENQUETE = ET.COD_ENQUETE" .
	" AND ET.COD_INSTANCIA_GLOBAL = " . $codInstanciaGlobal;

      if ($cod_enquete != "")
	$strSQL .= " AND E.COD_ENQUETE =" . $cod_enquete;
      else
	{		
	  if ($acesso != "")
	    $strSQL .= " AND (ET.COD_TIPO_ACESSO =" . $acesso . " OR ET.COD_TIPO_ACESSO=3 )";	
	}
				
      $strSQL .= " ORDER BY E.COD_ENQUETE DESC";
    }
  else
    {
      $strSQL = "SELECT E.COD_ENQUETE".
	" FROM enquete E" .
	" WHERE E.COD_ENQUETE = " . $cod_enquete;
				  	
    }

  return mysql_query($strSQL);
}
						
//======================================================================================================

function EnqueteLocalRemove($cod_enquete, $inst, $acesso)
{
  $strSQL = "DELETE FROM enquete_instancia WHERE COD_ENQUETE = ". $cod_enquete." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}
//======================================================================================================
function EnqueteLocalAltera($cod_enquete, $inst, $acesso, $tipo_acesso_novo)
{
  $strSQL = "UPDATE enquete_instancia SET  COD_TIPO_ACESSO=".$tipo_acesso_novo .
    " WHERE COD_ENQUETE = ". $cod_enquete ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}
//======================================================================================================


function EnqueteAltera($cod_enquete, $texto_enquete)
{
  $strSQL = "UPDATE enquete SET " .
    "TEXTO_ENQUETE = " . quote_smart($texto_enquete) . ", " .
    "COD_PESSOA = " . $_SESSION["COD_PESSOA"] . " " .
    "WHERE COD_ENQUETE = " .$cod_enquete; 
	
  mysql_query($strSQL);
				 
  return (! mysql_errno());
  //mysql_query("UPDATE enquete SET TEXTO_ENQUETE='$texto_enquete', COD_PESSOA ='$_SESSION["COD_PESSOA"]' WHERE COD_ENQUETE='$cod_enquete'");
  return (! mysql_errno());
}  
//======================================================================================================	
function EnqueteRespostaAltera($chave2,$chave1){

	
  mysql_query("UPDATE enquete_resposta SET TEXTO_RESPOSTA='$chave1' WHERE COD_RESPOSTA='$chave2'" );
	
  return (! mysql_errno());
	  
 
}
//======================================================================================================
function listaEnqueteAdm($codInstanciaGlobal, $local,$quem)

{
  $strSQL = "SELECT DISTINCT  E.COD_ENQUETE, E.TEXTO_ENQUETE ";

  if ($local == "instanciaAtual")
    {
      $strSQL .= " FROM enquete E, enquete_instancia ET WHERE E.COD_ENQUETE = ET.COD_ENQUETE";
		
      if ($codInstanciaGlobal != "")
	$strSQL .= " AND ET.COD_INSTANCIA_GLOBAL = '" . $codInstanciaGlobal . "'";
    }

  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT ET.COD_ENQUETE FROM enquete_instancia ET");
		
      $strSQL .= " FROM enquete E WHERE E.COD_ENQUETE NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ENQUETE"] . ",";
		
      $strSQL .= "0)";
    }

  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT ET.COD_ENQUETE FROM enquete_instancia ET");
		
      $strSQL .= " FROM enquete E WHERE E.COD_ENQUETE IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ENQUETE"] . ",";
		
      $strSQL .= "0)";	
    }

  if ($local == "")
	
    $strSQL .= " FROM enquete E, enquete_instancia ET WHERE E.COD_ENQUETE = ET.COD_ENQUETE";

  //		$strSQL .= " FROM enquete E";

  if ($quem != "")
    $strSQL = "SELECT * FROM enquete E WHERE E.COD_PESSOA = '". $quem ."'";
		
  $strSQL .= " ORDER BY E.COD_ENQUETE";


  return mysql_query($strSQL);					
}
//======================================================================================================

// Apaga imformações no Banco

function EnqueteExclue($cod_enquete,$texto_enquete)
{
  $strSQL = "DELETE FROM enquete WHERE COD_ENQUETE = " . $cod_enquete;
	
  mysql_query($strSQL);
	
  return (! mysql_errno());
}
//======================================================================================================
function enquete($cod_enquete, $cod_resposta, $opcao)
{

  //		Enquetes de uma determinada turma
  if ($cod_enquete == "")
    {
      $strSQL = "SELECT E.COD_PESSOA,E.COD_ENQUETE, E.TEXTO_ENQUETE, ET.COD_TIPO_ACESSO FROM enquete E, enquete_instancia ET".
	" WHERE ET.COD_INSTANCIA_GLOBAL = '". $_SESSION["codInstanciaGlobal"] ."' AND E.COD_ENQUETE = ET.COD_ENQUETE ORDER BY COD_ENQUETE";
    }
  else
    {
      //			if ($opcao != "")
      //			{
      if ($opcao == 0)
	{
	  //					Respostas de uma determinada enquete					
	  $strSQL = "SELECT E.COD_PESSOA,E.TEXTO_ENQUETE, ER.COD_RESPOSTA, ER.TEXTO_RESPOSTA FROM enquete E, enquete_resposta ER, enquete_instancia ET".
	    " WHERE E.COD_ENQUETE = ER.COD_ENQUETE AND ER.COD_ENQUETE= " . $cod_enquete . " GROUP BY ER.COD_RESPOSTA";
      
  }
      else
	{
	  if ($opcao == 1)
	    {
	      //					Respostas dos alunos para uma determinada enquete						
	      $strSQL = "SELECT ER.TEXTO_RESPOSTA, ER.COD_RESPOSTA, EAV.COD_AL FROM enquete E, enquete_resposta ER, enquete_instancia ET, enquete_aluno_votou EAV".
		" WHERE E.COD_ENQUETE = ER.COD_ENQUETE AND ER.COD_ENQUETE = ET.COD_ENQUETE AND ET.COD_ENQUETE= " . $cod_enquete . " AND ET.COD_INSTANCIA_GLOBAL = '" . $_SESSION["codInstanciaGlobal"] . "' AND ET.COD_ENQUETE_INSTANCIA = EAV.COD_ENQUETE_INSTANCIA AND ER.COD_RESPOSTA = EAV.COD_RESPOSTA";
	      //  GROUP BY ER.COD_RESPOSTA";
	   
		}
	  else
	    {
	      if ($opcao == 2)
		{
		  //						Gravar a resposta de algum aluno 
							
		  $strSQL = "SELECT COD_ENQUETE_INSTANCIA FROM enquete_instancia WHERE COD_INSTANCIA_GLOBAL = '" . $_SESSION["codInstanciaGlobal"] . "' AND COD_ENQUETE = '" . $cod_enquete . "' ";

		  $rsCon = mysql_query($strSQL);
		  $linha = mysql_fetch_array($rsCon);
							
		  $cod_enquete_inst = $linha["COD_ENQUETE_INSTANCIA"];
							
		  $strSQL = "INSERT INTO enquete_aluno_votou(COD_AL, COD_RESPOSTA,COD_ENQUETE_INSTANCIA) VALUES ('" . $_SESSION["COD_AL"] . "', '" . $cod_resposta . "', '" . $cod_enquete_inst . "')";
		  
    }
	    }
	  //				}
	} 
    }				

  //print_r(mysql_query($strSQL));

  return mysql_query($strSQL);
}

?>
