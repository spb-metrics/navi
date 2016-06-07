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
	echo "<Script language='JavaScript'> alert(\"Endereço editado com sucesso\"); location.href='./frm_atualiza_cadastro.php'; </Script>";
else
	echo "<Script language='JavaScript'> alert(\"Problemas na edição de seu endereço, por favor tente novamente. Caso o problema continue favor contatar a equipe do CURSOSNAVI.\"); location.href='./frm_atualiza_cadastro.php'; </Script>";

?>
