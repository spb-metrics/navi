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



//include_once("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();
	/*$ok = atualiza_cadastro($_REQUEST["NOME_PESSOA"], $_REQUEST["NOME_CHAT"], $_REQUEST["DATA_NASC_PESSOA_MODIFICADA"], $_REQUEST["COD_SEXO"], $_REQUEST["DOC_ID_PESSOA"], $_REQUEST["EMAIL_PESSOA"], $_REQUEST["CPF_PESSOA"], $_REQUEST["CORREIO_RECEBE_MAIL_EXTERNO"], $_REQUEST["CORREIO_RECEBE_MAIL_INTERNO"],$_REQUEST["RECADO_MAIL"]);*/

	$ok = atualiza_cadastro($_REQUEST["NOME_PESSOA"], $_REQUEST["NOME_CHAT"], $_REQUEST["DATA_NASC_PESSOA_MODIFICADA"], $_REQUEST["COD_SEXO"], $_REQUEST["DOC_ID_PESSOA"], $_REQUEST["EMAIL_PESSOA"], $_REQUEST["CPF_PESSOA"], $_REQUEST["CORREIO_RECEBE_MAIL_EXTERNO"], $_REQUEST["CORREIO_RECEBE_MAIL_INTERNO"],$_REQUEST["RECADO_MAIL"]);


	if ($_REQUEST["PROX_PAG"] != "" )
		$strRedirect = $_REQUEST["PROX_PAG"] . "?";
	else
		$strRedirect = "./frm_atualiza_cadastro.php";

	if ($_REQUEST["NRO_ITEM_FONE"] != "" )
		$strRedirect = $strRedirect . "NRO_ITEM_FONE=" . $_REQUEST["NRO_ITEM_FONE"];
		
	if ($_REQUEST["NRO_ITEM_END"]  != "" )
		$strRedirect = $strRedirect . "NRO_ITEM_END="  . $_REQUEST["NRO_ITEM_END"];
		
	echo "<script> location.href=\"./" . $strRedirect . "\";</script>";
	
?>

