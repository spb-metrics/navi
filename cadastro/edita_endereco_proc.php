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



//include_once("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();
if ($_SESSION["NOME_PESSOA"] == "")
{
	echo "Você não está logado no sistema. Esta página só pode ser acessada por um usuário logado.";
	exit();
}

$cod_endereco	=  $_POST['cod_endereco'];
$tipo_endereco	=  $_POST['tipo_endereco'];
$descricao		=  $_POST['descricao'];
$bairro			=  $_POST['bairro'];
$cidade			=  $_POST['cidade'];
$uf				=  $_POST['uf'];
$pais			=  $_POST['pais'];
$cep			=  $_POST['cep'];
$empresa		=  $_POST['empresa'];
$setor			=  $_POST['setor'];
$cargo			=  $_POST['cargo'];

$ret = atualiza_endereco("atualizar",$cod_endereco,$tipo_endereco,$descricao,$bairro,$cidade,$uf,$pais,$cep,$empresa,$setor,$cargo);

if($ret != 1 )
	$msg = "erro ao editar endereço!";
else
	$msg = "endereço editado com sucesso!";

?>
<html>
<body>
<script language="javascript">
	alert("<? print $msg; ?>");
	document.location="frm_atualiza_cadastro.php";
</script>
</body>
</html>