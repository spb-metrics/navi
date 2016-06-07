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
if ($_SESSION["NOME_PESSOA"] == "")
{
	echo "Voc� n�o est� logado no sistema. Esta p�gina s� pode ser acessada por um usu�rio logado.";
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
	$msg = "erro ao editar endere�o!";
else
	$msg = "endere�o editado com sucesso!";

?>
<html>
<body>
<script language="javascript">
	alert("<? print $msg; ?>");
	document.location="frm_atualiza_cadastro.php";
</script>
</body>
</html>