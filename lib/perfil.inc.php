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
 
include_once($caminhoBiblioteca."/CLDb.inc.php");
//========================================================================
function dadosPerfil($codPessoa) {
	$strSQL= "SELECT P.DESC_PERFIL, P.FOTO,P.FOTO_REDUZIDA, P.NOME_PESSOA, P.LINK_PERFIL FROM pessoa P WHERE P.COD_PESSOA=".quote_smart($codPessoa);

	return  mysql_query($strSQL);
}
//========================================================================
function AlterarPerfil($caminhoFoto,$caminhoFotoReduzida, $descPerfil,$linkPerfil, $cod_pessoa)
{
	$strSQL= "UPDATE pessoa SET ";
  if (!empty($caminhoFoto)) { 
    $arrayCampos['FOTO']=quote_smart($caminhoFoto);
  }
  if (!empty($caminhoFotoReduzida)) { 
    $arrayCampos['FOTO_REDUZIDA']=quote_smart($caminhoFotoReduzida);
  }
  if (!empty($descPerfil)) { 
    $arrayCampos['DESC_PERFIL']=quote_smart($descPerfil);
  }
  if (!empty($linkPerfil)) { 
    $arrayCampos['LINK_PERFIL']=quote_smart($linkPerfil);
    
  }
  if (!empty($arrayCampos)) {
    foreach($arrayCampos as $campo=>$valor) {
      $strSQL.=$campo.'='.$valor.' ,';
    }
    $strSQL = rtrim($strSQL,' ,');
    
    $strSQL.=" WHERE COD_PESSOA=".quote_smart($cod_pessoa);
  
    
    mysql_query($strSQL);
    //echo $strSQL; echo mysql_error(); die;
    return (! mysql_errno());
  }
  else {
    return 1;
  }
}
//========================================================================
function listaRecados($cod_pessoa_recebe)
{
  
	$strSQL= "SELECT R.TEXTO, R.COD_RECADO, DATE_FORMAT(R.DATA, '%d/%m/%Y') as DATA, R.COD_PESSOA, R.COD_PESSOA_RECEBE, R.msgLida, P.USER_PESSOA, P.COD_PESSOA, P.NOME_PESSOA FROM recados R, pessoa P WHERE P.COD_PESSOA=R.COD_PESSOA AND R.COD_PESSOA_RECEBE='".$cod_pessoa_recebe."' "."ORDER BY R.DATA DESC";

	return  mysql_query($strSQL);
}
//========================================================================
function recadoInserir($cod_pessoa_recebe, $texto)
{

 	$strSQL = "INSERT INTO recados (COD_PESSOA_RECEBE, COD_PESSOA, TEXTO)" .
    " VALUES (".quote_smart($cod_pessoa_recebe)." , " .quote_smart($_SESSION["COD_PESSOA"]) . " , " . quote_smart($texto) . " )";

  ///echo $strSQL;
  mysql_query($strSQL);
 
  return (! mysql_errno());
}
//===========================================================================
function vericaRecadoMail($codPessoa)
{
	$strSQL= "SELECT NOME_PESSOA, RECADO_MAIL, EMAIL_PESSOA  FROM pessoa WHERE COD_PESSOA='".$codPessoa."'";
	return  mysql_query($strSQL);
}
//===========================================================================
function recadomsgLida($cod_recado) {
  $strSQL= "SELECT msgLida, DATA FROM recados WHERE COD_RECADO =".$cod_recado;
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array( $rsCon);
  $lida = $linha["msgLida"];
  $lida = !$lida;
  $data = $linha["DATA"];

  $strSQL = "UPDATE recados SET msgLida = '".$lida."', DATA = '".$data."'  WHERE COD_RECADO ='".$cod_recado."'";

  ///echo $strSQL;
  mysql_query($strSQL);

  return (! mysql_errno());
}
//===========================================================================
function recadoApagar($cod_recado)
{
  
	$strSQL= "DELETE FROM recados WHERE COD_RECADO=".$cod_recado;
 
  mysql_query($strSQL);
 
  return (! mysql_errno());
	
}

function printNome($codPessoa) {
	$strSQL= "SELECT P.NOME_PESSOA FROM pessoa P WHERE P.COD_PESSOA=".quote_smart($codPessoa);
	$result = mysql_query($strSQL);
  $linha = mysql_fetch_assoc($result);
	
	return $linha['NOME_PESSOA'];
}

function getNovosRecados($cod_pessoa_recebe) {
 	$strSQL = " SELECT count(*) as numMsgLida FROM recados  ";
  $strSQL.= " WHERE COD_PESSOA_RECEBE=".quote_smart($cod_pessoa_recebe). " AND MSGLIDA=0";
	
	$result = mysql_query($strSQL);
  $linha = mysql_fetch_assoc($result);
	
	return $linha['numMsgLida'];  

}

function getNovosEmails($codPessoa) {
	$strSQL = " SELECT count(*) as numMsgsNaoLidas FROM correio_msg  ";
  $strSQL.= " INNER JOIN correio_msg_dest ON (correio_msg.codMsg = correio_msg_dest.codMsg)";
  $strSQL.= " WHERE user_to=".quote_smart($codPessoa). " AND (lida IS NULL OR lida=0) AND caixaSaidaExcluida!=1";
	
	$result = mysql_query($strSQL);
  $linha = mysql_fetch_assoc($result);
	
	return $linha['numMsgsNaoLidas'];  
}

//=============================================================================
/*function mostraFotoReduzida($codPessoa)
{	
	$strSQL="SELECT FOTO_REDUZIDA FROM pessoa WHERE COD_PESSOA=".$codPessoa;
	$rsCon=mysql_query($strSQL);
 
  if(! mysql_errno())
  {
	  while($linha = mysql_fetch_array($rsCon))
		$foto= str_replace("\\","/",$linha["FOTO_REDUZIDA"]);
	 
	  if(!empty($foto))
		  return "<img src=".$foto." height='30' width='40' border='2'>";
	  else
		  return "Sem foto."; 

  }
  
}FUNÇÂO INATIVA, não tem porque ser mais usada, por favor utilize foto.php, não se esqueça que deves passar como parâmetro COD_PESSOA e CASE, sendo CASE  que tipo de foto quer ver , se reduzida ou anormal, qualquer duvida vide código */
//=================================================================================
/**
 *
 */
 function checarFotosPessoas(){
 
  $sql= "SELECT  COD_PESSOA,FOTO,FOTO_REDUZIDA FROM pessoa";
  $result = new RDCLQuery($sql);
  return $result;
 } 
 
 function atualizaBDFotos($campoFoto,$codPessoa,$caminhoFoto=""){
   $sql= "UPDATE pessoa SET $campoFoto=".quote_smart($caminhoFoto)." WHERE COD_PESSOA='".$codPessoa."'";
   mysql_query($sql);
   return (! mysql_errno());
 
 }
?>
