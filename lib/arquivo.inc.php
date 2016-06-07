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


/*
 * Classe Arquivo
 * PadronizaþÒo da utilizaþÒo de arquivos pelos diferentes recursos
 */  
class Arquivo {
  var $codArquivo;


  function Arquivo($codArquivo) {
    $this->codArquivo=$codArquivo;  
  }

  function recursoUtiliza() {
   $sql = "update arquivo set contadorRelacionamento=contadorRelacionamento+1 where COD_ARQUIVO=".$this->codArquivo;
    mysql_query($sql);
  }

 /* function recursoNaoUtiliza() {
   $sql = "update arquivo set contadorRelacionamento=contadorRelacionamento-1 where 

COD_ARQUIVO=".$this->codArquivo;
    mysql_query($sql);
  }*/

  function recursoNaoUtiliza() {
   $sql = "delete from arquivo  where COD_ARQUIVO=".$this->codArquivo." AND contadorRelacionamento=0";
    mysql_query($sql);
  }

}


/** FUNCOES PARA ARQUIVO , APOIO e BIBLIOTECA
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
    
    Funcoes : 
    - ArquivoApaga
    - ArquivoInsere - não é utilizado
    - arquivos
    - arquivosADM
    - biblioteca
    - bibliotecaADM - não é utilizada 
    - bibliotecaitem
    - listaBiblioteca
    - listaApoioAdm
    - ApoioVerificaAcesso
    - listaApoio
    - ApoioAltera
    - ApoioInsere
    - ApoioExclue
    - ApoioCodigo
    - ApoioLocalInsere
    - ApoioLocalRemove
    - ApoioLocalAltera
    - listaApoioLocal
    - apoioAulas
    - apoioCaminho
    - apoioCaminhoAluno


    Funcoes que nao estao implementadas direito q tem de ser deletadas ou corrigidas :
    - ArquivoApaga
**/


/** MULTINAVI
    - Falta fazer para funcao arquivos


 **/


//======================================================================================================
// apoio/download.asp; apoio/index.asp; apoio/pdf.asp; apoio/topo.asp;
// tools/apoio_operacao; 
//TODO
function arquivos($cod_arquivo)
{

  if ($cod_arquivo == "")
    $strSQL = "SELECT COD_ARQUIVO, DESC_ARQUIVO_INSTANCIA FROM arquivo_instancia WHERE COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"] . " ORDER BY DESC_ARQUIVO_INSTANCIA";
  else
    $strSQL = "SELECT AT.COD_ARQUIVO, AT.DESC_ARQUIVO_INSTANCIA, A.DESC_ARQUIVO, AT.COD_INSTANCIA_GLOBAL,".
      " A.CAMINHO_LOCAL_ARQUIVO, A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO , T.COD_DIS, D.COD_CURSO".
      " FROM arquivo A, arquivo_instancia AT, turma T, disciplina D WHERE AT.COD_TURMA = T.COD_TURMA".
      " AND A.COD_ARQUIVO = AT.COD_ARQUIVO AND T.COD_DIS = D.COD_DIS".
      " AND A.COD_ARQUIVO =". $cod_arquivo;

  return mysql_query($strSQL);
}

//======================================================================================================
// tools/apoio.asp

function arquivosADM($cod_instancia)
{	
  if ($cod_instancia != "") 
    $strSQL = "SELECT A.COD_ARQUIVO, A.DESC_ARQUIVO_INSTANCIA, A.COD_INSTANCIA_GLOBAL FROM arquivo_instancia A WHERE A.COD_INSTANCIA_GLOBAL=" . $cod_instancia . " ORDER BY DESC_ARQUIVO_INSTANCIA";
  else
    $strSQL = "SELECT A.COD_ARQUIVO, A.DESC_ARQUIVO_INSTANCIA, A.COD_INSTANCIA_GLOBAL, FROM arquivo_instancia A ORDER BY A.COD_INSTANCIA_GLOBAL,DESC_ARQUIVO_INSTANCIA";

  return mysql_query($strSQL);

}

//======================================================================================================
// biblioteca/interno.asp; biblioteca/menu.asp;
// tools/acervo_operacao.asp
// biblioteca
//function biblioteca($cod_tipo_item_bib, $tipoAcesso)

function biblioteca($cod_tipo_item_bib)
{	

  if ($cod_tipo_item_bib == "")
    $strSQL = "SELECT TIB.COD_TIPO_ITEM_BIB, TIB.DESC_TIPO_ITEM_BIB FROM tipo_item_biblioteca TIB,biblioteca B WHERE B.COD_TIPO_ITEM_BIB=TIB.COD_TIPO_ITEM_BIB AND B.COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"] . " GROUP BY TIB.COD_TIPO_ITEM_BIB ORDER BY TIB.DESC_TIPO_ITEM_BIB";
  else
    $strSQL = "SELECT A.COD_PESSOA, B.COD_ARQUIVO, B.DESC_ARQUIVO_INSTANCIA, B.COD_TIPO_ACESSO  FROM biblioteca B, arquivo A WHERE B.COD_ARQUIVO=A.COD_ARQUIVO AND B.COD_TIPO_ITEM_BIB=" . $cod_tipo_item_bib . " AND B.COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"] . " ORDER BY B.DESC_ARQUIVO_INSTANCIA";

  return mysql_query($strSQL);
		
}

//======================================================================================================
// biblioteca/pdf.asp; biblioteca/topo.asp
// tools/acervo_operacao.asp

function bibliotecaitem($cod_arquivo)
{
 
  if ($cod_arquivo == "")
    $strSQL = "SELECT COD_ARQUIVO, DESC_ARQUIVO_INSTANCIA FROM biblioteca WHERE COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"] . " ORDER BY DESC_ARQUIVO_INSTANCIA";
  else
    $strSQL = "SELECT B.COD_ARQUIVO, B.DESC_ARQUIVO_INSTANCIA, A.DESC_ARQUIVO, B.COD_INSTANCIA_GLOBAL, B.COD_TIPO_ITEM_BIB,".
      " A.CAMINHO_LOCAL_ARQUIVO, A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO ".
      " FROM arquivo A, biblioteca B WHERE ".
      " A.COD_ARQUIVO = B.COD_ARQUIVO ".
      " AND A.COD_ARQUIVO =". $cod_arquivo;
 
  return mysql_query($strSQL);
}

//======================================================================================================
function listaBiblioteca()
{
  $strSQL = "SELECT COD_TIPO_ITEM_BIB, DESC_TIPO_ITEM_BIB FROM tipo_item_biblioteca ORDER BY DESC_TIPO_ITEM_BIB";
  return mysql_query($strSQL);
}
 
//======================================================================================================
// casos/index.asp; casos/lista.asp;
// relatos/index.asp
// Estudo de casos
 
 
//======================================================================================================

function listaApoioAdm($cod_instancia, $local, $quem)
{
  $strSQL = "SELECT DISTINCT A.DESC_ARQUIVO, A.COD_ARQUIVO, A.COD_PESSOA";

  if ($local == "instanciaAtual")
    {
      $strSQL .= " FROM arquivo A, arquivo_instancia AT WHERE A.COD_ARQUIVO = AT.COD_ARQUIVO";
		
      if ($cod_instancia != "")
	$strSQL .= " AND AT.COD_INSTANCIA_GLOBAL = '" . $cod_instancia . "'";
    }

  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT AT.COD_ARQUIVO FROM arquivo_instancia AT");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";
    }

  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT AT.COD_ARQUIVO FROM arquivo_instancia AT");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";	
    }

  if ($local == "")
    $strSQL .= " FROM arquivo A";

  if ($quem != "")
    $strSQL = "Select * FROM arquivo A WHERE A.COD_PESSOA = '". $quem ."'";
		
  $strSQL .= " ORDER BY A.COD_ARQUIVO";

  return mysql_query($strSQL);					
}

//======================================================================================================

/** TO DO -> maneira mais geral de ver o direito do usuario **/

function ApoioVerificaAcesso($cod_arquivo)
{
  $permite = false;
		
  if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1)
    $permite = true;
	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) ) {
      // Verifica se a noticia é dele
		
      $strSQL = "SELECT COD_ARQUIVO FROM arquivo".
	" WHERE COD_ARQUIVO = '". $cod_arquivo ."'".
	" AND COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."'";
				  
      $rsCon = mysql_query($strSQL);
		
  if ($rsCon)	{
    if ($linha = mysql_fetch_array($rsCon))
      $permite = true;
    }
  }

	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )    {		  
	  $strSQL = "SELECT AI.COD_ARQUIVO FROM arquivo_instancia AI WHERE AI.COD_ARQUIVO=".quote_smart($cod_arquivo).
					" AND AI.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"];

    $rsCon = mysql_query($strSQL);

    if ($rsCon) {
	    if ($linha = mysql_fetch_array($rsCon))
	      $permite = true;
	  }
  }

  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 ) and (! $permite) )
    {
      // Verifica se a noticia é da alguma turma cujo curso ele é adm
      $strSQL = "SELECT AT.COD_ARQUIVO FROM arquivo-turma AT, turma T, disciplina D, administrador_curso AC".
	" WHERE AT.COD_ARQUIVO = '". $cod_arquivo ."'".
	" AND AC.COD_ADM = '". $_SESSION["COD_ADM"] ."' AND AC.COD_CURSO = D.COD_CURSO".
	" AND D.COD_DIS = T.COD_DIS AND T.COD_TURMA = AT.COD_TURMA";

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

function listaApoio($cod_arquivo, $cod_instancia, $acesso)
{
	
  if ($cod_instancia != "")
    {	
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.COD_PESSOA, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO, AT.COD_TIPO_ACESSO, AT.DESC_ARQUIVO_INSTANCIA, AT.COD_INSTANCIA_GLOBAL" . 		
	" FROM arquivo A, arquivo_instancia AT" .
	" WHERE AT.COD_INSTANCIA_GLOBAL =".$cod_instancia;	

      if ($cod_arquivo != ""){
	$strSQL .= "  AND A.COD_ARQUIVO=AT.COD_ARQUIVO" .
			   " AND AT.COD_ARQUIVO = " . $cod_arquivo;}
      else
	{		
	  if ($acesso != "")
	    $strSQL .= " AND (AT.COD_TIPO_ACESSO =" . $acesso . " OR AT.COD_TIPO_ACESSO=3 )";	
	}
				
      $strSQL .= " ORDER BY A.COD_ARQUIVO DESC";
    }
  else
    {   
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.COD_PESSOA, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO".
	" FROM arquivo A" .
	" WHERE A.COD_ARQUIVO = " . quote_smart($cod_arquivo);
  	
    }
  
  return mysql_query($strSQL);					
}

//======================================================================================================

function ApoioAltera($cod_arquivo, $desc_arquivo, $caminho, $tamanho, $tipo)
{

  $strSQL = "UPDATE arquivo SET " .
    "CAMINHO_LOCAL_ARQUIVO = ".quote_smart($caminho). ", " .
    "DESC_ARQUIVO = '" . $desc_arquivo . "', ";
    
  if($tamanho>0) {
    $strSQL .= "TAMANHO_ARQUIVO = '" . $tamanho . "', " ;
  }
  
  if(isset($tipo)) {
    $strSQL .= "TIPO_ARQUIVO = '" . $tipo . "', " ;
  }
    
  $strSQL .= "COD_PESSOA = " . $_SESSION["COD_PESSOA"] .
  " WHERE COD_ARQUIVO = " . $cod_arquivo; 
  /*
	echo 'tipo:'.$tipo;
	echo '<BR>'.$strSQL;
	echo mysql_error(); die;
	*/
  mysql_query($strSQL);
				 
  return (! mysql_errno());
}

//======================================================================================================

function ApoioInsere($desc_arquivo, $caminho, $tamanho, $tipo){
  $strSQL = "INSERT INTO arquivo (COD_PESSOA, CAMINHO_LOCAL_ARQUIVO, TAMANHO_ARQUIVO, TIPO_ARQUIVO, DESC_ARQUIVO) " .
    " VALUES (". $_SESSION["COD_PESSOA"] .",'". $caminho . "','". $tamanho ."','". $tipo ."','". $desc_arquivo ."')";
  mysql_query($strSQL);
	
	//echo '<BR>'.$strSQL;
	//echo mysql_error(); die;
  return (! mysql_errno());
}

//======================================================================================================

function ApoioExclue($cod_arquivo)
{
  $strSQL = "DELETE FROM arquivo WHERE COD_ARQUIVO = " . $cod_arquivo;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function ApoioCodigo($desc_arquivo, $caminho, $tamanho, $tipo)
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

function ApoioLocalInsere($cod_arquivo, $instanciaGlobal, $acesso, $desc_arquivo)
{
  $strSQL = "INSERT INTO arquivo_instancia (COD_ARQUIVO, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO , DESC_ARQUIVO_INSTANCIA) " .
    " VALUES (". $cod_arquivo .",". $instanciaGlobal .",". $acesso .", '". $desc_arquivo . "' )";
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function ApoioLocalRemove($cod_arquivo, $instanciaGlobal, $acesso)
{
  $strSQL = "DELETE FROM arquivo_instancia WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $instanciaGlobal;
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function ApoioLocalAltera($cod_arquivo, $instanciaGlobal, $desc_arquivo_instancia, $acesso, $tipo_acesso_novo)
{
  $strSQL = "UPDATE arquivo_instancia SET DESC_ARQUIVO_INSTANCIA = '". $desc_arquivo_instancia ."', COD_TIPO_ACESSO=".$tipo_acesso_novo .
    " WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $instanciaGlobal;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}


//======================================================================================================
//usado por tools/apoio_local.php
//lista os locais onde determinado arquivo foi publicado
function listaApoioLocal($codArquivo) {
	return listaLocal("arquivo_instancia","COD_ARQUIVO",$codArquivo,"DESC_ARQUIVO_INSTANCIA");
}



//======================================================================================================
// aulas/index.asp
// Retorna os videos disponiveis para uma turma

function apoioAulas($acesso,$order='',$by='')
{
  $strSQL = "SELECT A.COD_ARQUIVO, A.COD_PESSOA, A.DESC_ARQUIVO, AT.DESC_ARQUIVO_INSTANCIA, AT.COD_TIPO_ACESSO FROM arquivo A, arquivo_instancia AT WHERE AT.COD_INSTANCIA_GLOBAL = " .  $_SESSION["codInstanciaGlobal"] . " AND A.COD_ARQUIVO = AT.COD_ARQUIVO AND (AT.COD_TIPO_ACESSO =" . $acesso . " OR AT.COD_TIPO_ACESSO=3 ) GROUP BY COD_ARQUIVO ORDER BY AT.".$by." ".$order;

  return mysql_query($strSQL);	
}
 
//======================================================================================================
// tools/video_operacao.asp;
// aulas/download.asp; aulas/redireciona.asp; aulas/topo.asp; aulas/video.asp
// Retorna o caminho para um determinado cod_video

function apoioCaminho($cod_arquivo)
{	
  $strSQL = "SELECT A.COD_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.DESC_ARQUIVO, AT.COD_TIPO_ACESSO, A.TIPO_ARQUIVO,AT.COD_INSTANCIA_GLOBAL, AT.DESC_ARQUIVO_INSTANCIA ".
    " FROM arquivo A, arquivo_instancia AT ".
    " WHERE A.COD_ARQUIVO = AT.COD_ARQUIVO AND AT.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"].
    " AND A.COD_ARQUIVO = " . $cod_arquivo;
			  
  if ($cod_arquivo == "")
    $strSQL = "SELECT * FROM arquivo WHERE 0";
  
  return mysql_query($strSQL);	
}


//======================================================================================================
//usado em  portifolio 

function apoioCaminhoAluno($cod_arquivo,$comentario="")
{	
	if($comentario){
$strSQL ="SELECT * FROM arquivo WHERE COD_ARQUIVO=".$cod_arquivo;
}else{
  $strSQL = "SELECT A.COD_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.DESC_ARQUIVO, AAT.COD_TIPO_CASO, AAT.COD_INSTANCIA_GLOBAL, AAT.DESC_ARQUIVO_INSTANCIA, A.TIPO_ARQUIVO".
    " FROM arquivo A, arquivo_aluno_instancia AAT".
    " WHERE A.COD_ARQUIVO = AAT.COD_ARQUIVO ".
    " AND A.COD_ARQUIVO = " . $cod_arquivo;
			  
  if ($cod_arquivo == "")
    $strSQL = "SELECT * FROM arquivo WHERE 0";
}
  return mysql_query($strSQL);	
}

function getDescricaoArquivo($codArquivo) {
  $result= mysql_query("Select DESC_ARQUIVO from arquivo Where COD_ARQUIVO=".quote_smart($codArquivo));
  $linha = mysql_fetch_assoc($result);

  return $linha['DESC_ARQUIVO'];
} 

function formListConteudos($order='',$by=''){
$color='';	
echo "<table  align=\"center\" cellspacing=\"0\" style=\"background-color:#000000\"><tr><td>"; 
echo  "<table align='center'cellpadding=\"5\" cellspacing=\"0\">".
		  "<tr>";
if($order=='ASC' && $by=='DESC_ARQUIVO_INSTANCIA'){$color='red';}else $color='';
echo  "<td style=\"background-color:#FFFFFF\"><a href='".$_SERVER['PHP_SELF']."?order=ASC&by=DESC_ARQUIVO_INSTANCIA '><font color='".$color."'>Ordem Alfabetica Crescente |</font> </a></td>";
if($order=='DESC' && $by=='DESC_ARQUIVO_INSTANCIA'){$color='red';}else $color='';
echo  "<td style=\"background-color:#FFFFFF\"><a href='".$_SERVER['PHP_SELF']."?order=DESC&by=DESC_ARQUIVO_INSTANCIA '><font color='".$color."'>Ordem Alfabetica Decrescente |</font></a></td>";
if($order=='ASC' && $by=='COD_ARQUIVO'){$color='red';}else $color='';
echo  "<td style=\"background-color:#FFFFFF\"><a href='".$_SERVER['PHP_SELF']."?order=ASC&by=COD_ARQUIVO'><font color='".$color."'>Por Data de Postagem Crescente |</font></a></td>";
if($order=='DESC' && $by=='COD_ARQUIVO'){$color='red';}else $color='';
echo  "<td style=\"background-color:#FFFFFF\"><a href='".$_SERVER['PHP_SELF']."?order=DESC&by=COD_ARQUIVO'><font color='".$color."'> Por Data de Postagem Decrescente </font></a></td>";
echo  "</tr>".
		 "</table>";
echo "</td></tr></table>";
}


?>
