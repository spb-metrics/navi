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


?>

<html>
	<head> </head>
	<body>

<?php
//include_once("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();

if ($_REQUEST["ACTION"] == "excluir")
	$ok = atualiza_endereco("excluir", $_REQUEST["NRO_ITEM_END"], "", "", "", "", "", "", "", "", "", "");
else
{
	if ($_REQUEST["NRO_ITEM_END"] <> "")
		$ok = atualiza_endereco("atualizar", $_REQUEST["NRO_ITEM_END"], $_REQUEST["COD_TIPO_END"], $_REQUEST["DESC_END"], $_REQUEST["BAIRRO_END"], $_REQUEST["CIDADE_END"], $_REQUEST["UF_END"], $_REQUEST["PAIS_END"], $_REQUEST["CEP_END"], $_REQUEST["EMPRESA_END"], $_REQUEST["SETOR_END"], $_REQUEST["CARGO_END"]);
	else
		$ok = atualiza_endereco("inserir"  ,                        "", $_REQUEST["COD_TIPO_END"], $_REQUEST["DESC_END"], $_REQUEST["BAIRRO_END"], $_REQUEST["CIDADE_END"], $_REQUEST["UF_END"], $_REQUEST["PAIS_END"], $_REQUEST["CEP_END"], $_REQUEST["EMPRESA_END"], $_REQUEST["SETOR_END"], $_REQUEST["CARGO_END"]);
 }

if ($ok == true)
	echo "<Script language='JavaScript'> alert(\"Endere�o editado com sucesso\"); location.href='./frm_atualiza_cadastro.php'; </Script>";
else
	echo "<Script language='JavaScript'> alert(\"Problemas na edi��o de seu endere�o, por favor tente novamente. Caso o problema continue favor contatar a equipe do CURSOSNAVI.\"); location.href='./frm_atualiza_cadastro.php'; </Script>";

?>
