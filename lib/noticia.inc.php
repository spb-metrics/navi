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

/** FUNCOES PARA MANIPULACAO DE NOTICIAS
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA
    Funcoes
      - NoticiaAltera
      - NoticiaCodigo
      - NoticiaExclue
      - NoticiaLocalAltera
      - NoticiaLocalInsere
      - NoticiaLocalRemove
      - NoticiaVerificaAcesso
      - listaNoticiasLocal
      - listaNoticiasAdm
      - listaNoticias
**/
//======================================================================================================
// tools/noticais_envio.asp
// Grava informações no Banco

/*function NoticiaAltera($COD, $TITULO, $RESUMO, $TEXTO,$sitesRSS,$numeroNoticiasRSSResumo) {
  $strSQL = "UPDATE noticia SET " .
    "TITULO_NOTICIA = '" . $TITULO . "', " .
    "RESUMO_NOTICIA = '" . $RESUMO . "', " .
    "TEXTO_NOTICIA = '" . $TEXTO . "', " .
    "sitesRSS = '" . $sitesRSS . "', " .
    "numeroNoticiasRSSResumo = '" . $numeroNoticiasRSSResumo . "', " .
    "COD_PESSOA = " . $_SESSION["COD_PESSOA"] . " " .
    //"WHERE COD_NOTICIA = " . $COD; 
    "WHERE COD_NOTICIA = " . quote_smart($COD);
	
  mysql_query($strSQL);
				 
  return (! mysql_errno());
}
*/
function NoticiaAltera($COD, $TITULO, $RESUMO, $TEXTO,$sitesRSS,$numeroNoticiasRSSResumo) {
  $strSQL = "UPDATE noticia SET " .
    "TITULO_NOTICIA = " . quote_smart($TITULO) . ", " .
    "RESUMO_NOTICIA = " . quote_smart($RESUMO) . ", " .
    "TEXTO_NOTICIA = " . quote_smart($TEXTO) . ", " .
    "sitesRSS = " . quote_smart($sitesRSS) . ", " .
    "numeroNoticiasRSSResumo = " . quote_smart($numeroNoticiasRSSResumo) . ", " .
    "COD_PESSOA = " . quote_smart($_SESSION["COD_PESSOA"]) . " " .

    "WHERE COD_NOTICIA = " . quote_smart($COD); 
	
  mysql_query($strSQL);
				 
  return (! mysql_errno());
  }
//======================================================================================================
// tools/noticias_envio.asp
// Retorna o cod_noticia de acordo com o que foi inserido anteriormente

/*function NoticiaCodigo($titulo, $resumo, $texto) {
  $strSQL = "SELECT COD_NOTICIA FROM noticia WHERE".
    " COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."' AND".
    " TITULO_NOTICIA = '". $titulo ."' AND".
    " RESUMO_NOTICIA = '". $resumo ."' AND".
    " TEXTO_NOTICIA  = '". $texto  ."' ".
    " ORDER BY COD_NOTICIA DESC";

  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
		
  return $linha["COD_NOTICIA"];
}*/

function NoticiaCodigo($titulo, $resumo, $texto) {
  $strSQL = "SELECT COD_NOTICIA FROM noticia WHERE".
    " COD_PESSOA = ". quote_smart($_SESSION["COD_PESSOA"]) ." AND".
    " TITULO_NOTICIA = ". quote_smart($titulo) ." AND".
    " RESUMO_NOTICIA = ". quote_smart($resumo) ." AND".
    " TEXTO_NOTICIA  = ". quote_smart($texto)  ." ".
    " ORDER BY COD_NOTICIA DESC";

  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
		
  return $linha["COD_NOTICIA"];
}
 
//======================================================================================================
// tools/noticais_envio.asp
// Apaga imformações no Banco

function NoticiaExclue($COD)
{
  $strSQL = "DELETE FROM noticia WHERE COD_NOTICIA = " . quote_smart($COD);
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

//======================================================================================================
// tools/noticias_envio.asp
// Grava imformações no Banco

/*function NoticiaInsere($TITULO, $RESUMO, $TEXTO,$sitesRSS,$numeroNoticiasRSSResumo)
{
 $strSQL = "INSERT INTO `noticia` (COD_PESSOA, TITULO_NOTICIA, RESUMO_NOTICIA, TEXTO_NOTICIA, sitesRSS,numeroNoticiasRSSResumo) " .
           " VALUES (".$_SESSION["COD_PESSOA"] .",'".$TITULO . "','". $RESUMO . "','". $TEXTO . "','".$sitesRSS."','".$numeroNoticiasRSSResumo."')";
    
 
 
  mysql_query($strSQL);

   return mysql_insert_id();
  return (! mysql_errno());
}*/
function NoticiaInsere($TITULO, $RESUMO, $TEXTO,$sitesRSS,$numeroNoticiasRSSResumo)
{
 $strSQL = "INSERT INTO noticia (COD_PESSOA, TITULO_NOTICIA, RESUMO_NOTICIA, TEXTO_NOTICIA, sitesRSS,numeroNoticiasRSSResumo) " .
           " VALUES (". quote_smart($_SESSION["COD_PESSOA"]) .",". quote_smart($TITULO) . ",". quote_smart($RESUMO) . ",". quote_smart($TEXTO) . ",".quote_smart($sitesRSS).",".quote_smart($numeroNoticiasRSSResumo).")";



     mysql_query($strSQL);
    
   return mysql_insert_id();

}

//======================================================================================================
// tools/noticias_local.asp
// Grava informações no Banco

/*function NoticiaLocalAltera($noticia, $codInstanciaGlobal, $lin, $col, $acesso,$tipo_acesso_novo)
{
  $strSQL = "UPDATE noticia_instancia SET NRO_COLUNA_NOTICIA=". $col .",  NRO_LINHA_NOTICIA=". $lin .",COD_TIPO_ACESSO=".$tipo_acesso_novo .
	" WHERE COD_NOTICIA = ". $noticia ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $codInstanciaGlobal;
  //echo $strSQL;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}*/
function NoticiaLocalAltera($noticia, $codInstanciaGlobal, $lin, $col, $acesso,$tipo_acesso_novo)
{
  $strSQL = "UPDATE noticia_instancia SET NRO_COLUNA_NOTICIA=". quote_smart($col) .",  NRO_LINHA_NOTICIA=". quote_smart($lin) .",COD_TIPO_ACESSO=".quote_smart($tipo_acesso_novo) .
	" WHERE COD_NOTICIA = ". quote_smart($noticia)." AND COD_TIPO_ACESSO = ". quote_smart($acesso) ." AND COD_INSTANCIA_GLOBAL=". quote_smart($codInstanciaGlobal);
  //echo $strSQL;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}

//======================================================================================================
// tools/noticias_local.asp
// Grava informações no Banco

/*function NoticiaLocalInsere($noticia, $codInstanciaGlobal, $lin, $col, $acesso)
{
   $strSQL = "INSERT INTO noticia_instancia (COD_NOTICIA, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO , NRO_COLUNA_NOTICIA,  NRO_LINHA_NOTICIA) " .
	" VALUES (". $noticia .",". $codInstanciaGlobal.",". $acesso .",". $col . ",". $lin . ")";
  mysql_query($strSQL);
  return (! mysql_errno());
}*/
function NoticiaLocalInsere($noticia, $codInstanciaGlobal, $lin, $col, $acesso)
{
   $strSQL = "INSERT INTO noticia_instancia (COD_NOTICIA, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO , NRO_COLUNA_NOTICIA,  NRO_LINHA_NOTICIA) " .
	" VALUES (". quote_smart($noticia) .",". quote_smart($codInstanciaGlobal).",". quote_smart($acesso) .",". quote_smart($col) . ",". quote_smart($lin) . ")";
  mysql_query($strSQL);
  return (! mysql_errno());
}

//======================================================================================================
// tools/noticias_local.asp
// Grava imformações no Banco

/*
  $strSQL = "DELETE FROM noticia_instancia WHERE COD_NOTICIA = ". $noticia ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $codInstanciaGlobal;

  //echo $strSQL;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}*/
function NoticiaLocalRemove($noticia,$codInstanciaGlobal, $acesso) {
  $strSQL = "DELETE FROM noticia_instancia WHERE COD_NOTICIA = ". quote_smart($noticia) ." AND COD_TIPO_ACESSO = ". quote_smart($acesso) ." AND COD_INSTANCIA_GLOBAL=". quote_smart($codInstanciaGlobal);

  //echo $strSQL;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

//======================================================================================================
// Dada uma noticia retorna true casa a pessoa possui o privilégio para alterar a noticia
// se nivel_acesso_futuro = 1 entao libera
// se nivel_acesso_futuro = 2 entao procura por cod_pessoa ou curso ou turmas do curso ou turmas de prof.
// se nivel_acesso_futuro = 3 entao procura por cod_pessoa ou turmas de prof.

/*function NoticiaVerificaAcesso($cod_noticia) {
  $permite = false;
	//ADM NIVEL E ADM GERAL SEMPRE PODEM MEXER	
  if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1 || $_SESSION['userRole']==ADMINISTRADOR_GERAL || $_SESSION['userRole']==ADM_NIVEL)
    $permite = true;

  // Verifica se a noticia é dele	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) ) {

      $strSQL = "SELECT COD_NOTICIA FROM noticia".	" WHERE COD_NOTICIA = '". $cod_noticia ."'".
    	" AND COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."'";				  
      $rsCon = mysql_query($strSQL);
		
      if (mysql_num_rows($rsCon))	{        
        $permite = true;
	    }
  }
	// Verifica se ele é professor e este nível tem relacionamento com professores
  if ( !$permite && $_SESSION['userRole']==PROFESSOR ) {
    $nivelAtual = getNivelAtual();
    if (!empty($nivelAtual->nomeFisicoTabelaRelacionamentoProfessores)) {  
      $permite = true;   
    }
  }

  return $permite;
}*/
function NoticiaVerificaAcesso($cod_noticia) {
  $permite = false;
	//ADM NIVEL E ADM GERAL SEMPRE PODEM MEXER	
  if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1 || $_SESSION['userRole']==ADMINISTRADOR_GERAL || $_SESSION['userRole']==ADM_NIVEL)
    $permite = true;

  // Verifica se a noticia é dele	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) ) {

      $strSQL = "SELECT COD_NOTICIA FROM noticia".	" WHERE COD_NOTICIA = ". quote_smart($cod_noticia) .
    	" AND COD_PESSOA = ". quote_smart($_SESSION["COD_PESSOA"]) ;				  
      $rsCon = mysql_query($strSQL);
		
      if (mysql_num_rows($rsCon))	{        
        $permite = true;
	    }
  }
	// Verifica se ele é professor e este nível tem relacionamento com professores
  if ( !$permite && $_SESSION['userRole']==PROFESSOR ) {
    $nivelAtual = getNivelAtual();
    if (!empty($nivelAtual->nomeFisicoTabelaRelacionamentoProfessores)) {  
      $permite = true;   
    }
  }

  return $permite;
}


//======================================================================================================
// tools/noticias.asp
// Retorna um ResultSet com as noticias

function listaNoticiasAdm($cod_instancia_global, $local, $quem)
{
  $strSQL = "SELECT DISTINCT N.TITULO_NOTICIA, N.COD_NOTICIA";

  /* if ($local == "curso")
    {
      $strSQL .= " FROM noticia N, NOTICIA_CURSO NC WHERE N.COD_NOTICIA = NC.COD_NOTICIA";

      if ($cod_curso != "")
	$strSQL .= " AND NC.COD_CURSO = '" . $cod_curso . "'";	
    }*/
	
  if ($local == "instancia")    {
      $strSQL .= " FROM noticia N, noticia_instancia NT WHERE N.COD_NOTICIA = NT.COD_NOTICIA";
		
      if ($cod_instancia_global != "") {
	      $strSQL .= " AND NT.COD_INSTANCIA_GLOBAL = '" . $cod_instancia_global . "'";
      }
  }

  //if ($local == "principal")
  //  $strSQL .= " FROM noticia N, NOTICIA_PRINCIPAL NP WHERE N.COD_NOTICIA = NP.COD_NOTICIA";

  if ($local == "nenhum") {
      $rsCon = mysql_query("SELECT COD_NOTICIA FROM noticia_instancia NT");
		
      $strSQL .= " FROM noticia N WHERE N.COD_NOTICIA NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon)) {
	      $strSQL .= $linha["COD_NOTICIA"] . ",";
		  }
      $strSQL .= "0)";
    }

  if ($local == "algo")   {
    $rsCon = mysql_query("SELECT COD_NOTICIA FROM noticia_instancia NT ");

    $strSQL .= " FROM noticia N WHERE N.COD_NOTICIA IN (";

    while ($linha = mysql_fetch_array($rsCon)) {
      $strSQL .= $linha["COD_NOTICIA"] . ",";
    }
    $strSQL .= "0)";
  }

  if ($local == "")
    $strSQL .= " FROM noticia N";

  if ($quem != "")
    $strSQL = "Select * FROM noticia N WHERE N.COD_PESSOA = '". $quem ."'";
		
  $strSQL .= " ORDER BY N.COD_NOTICIA";

  return mysql_query($strSQL);					
}


//======================================================================================================
// noticias.asp; noticias_descricao.asp
// alunos/index.asp
// casos/envia_caso.asp; casos/envia_com.asp; casos/index.asp; casos/lista.asp; casos/mostra.asp
// relatos/envia_com.asp; relatos/envia_relato.asp; relatos/index.asp; relatos/lista.asp; relatos/mostra.asp;
// tools/noticias_local.asp; tools/noticias_operacao.asp

// Retorna um ResultSet com as noticias


function listaNoticias($cod_noticia, $cod_instancia_global, $coluna, $acesso) {

  $strSQL = "SELECT N.sitesRSS,N.numeroNoticiasRSSResumo,N.TITULO_NOTICIA, N.RESUMO_NOTICIA, N.COD_NOTICIA, N.TEXTO_NOTICIA, NRO_COLUNA_NOTICIA, NRO_LINHA_NOTICIA, COD_TIPO_ACESSO";

  $strSQL .= " FROM noticia N, noticia_instancia NT" .
    " WHERE N.COD_NOTICIA = NT.COD_NOTICIA" .
    " AND NT.COD_INSTANCIA_GLOBAL = " . quote_smart($cod_instancia_global);

  if ($coluna != "")
    $strSQL .= " AND NRO_COLUNA_NOTICIA=" . quote_smart($coluna);

  if ($acesso != "")
    $strSQL .= " AND (COD_TIPO_ACESSO =" . quote_smart($acesso) . " OR COD_TIPO_ACESSO=3 )";	

  if ($cod_noticia != "")    {
    if ($acesso != "") {
      $strSQL .= " AND N.COD_NOTICIA =" . quote_smart($cod_noticia);
    }
    else {
    $strSQL = "SELECT  N.sitesRSS,N.numeroNoticiasRSSResumo,N.TITULO_NOTICIA, N.RESUMO_NOTICIA, N.COD_NOTICIA, N.TEXTO_NOTICIA".
      " FROM noticia N" .
      " WHERE N.COD_NOTICIA = " . quote_smart($cod_noticia);
    }
  }
  else {
    $strSQL .= " ORDER BY NRO_COLUNA_NOTICIA ASC,NRO_LINHA_NOTICIA ASC, N.COD_NOTICIA DESC";
  }

  return mysql_query($strSQL);					
}
 function sitesRSS($codNoticia){
    $sql="SELECT sitesRSS FROM noticia WHERE COD_NOTICIA=".quote_smart($codNoticia)."";
    $ok= mysql_query($sql);
    $linha = mysql_fetch_array($ok);
    return $linha["sitesRSS"];
  } 
 
?>
