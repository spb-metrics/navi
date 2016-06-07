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


include_once("../funcoes_bd.php");
session_name(SESSION_NAME); session_start(); security();
// precisa verificar se ja exite alguem com o mesmo nome de usuario, arrumar a data no funcoes_bd, criar a pessoa com aluno..

if ( !isset($_REQUEST["DATA_NASC_PESSOA"]) )
	$_REQUEST["DATA_NASC_PESSOA"] = "";


if ( !isset($_REQUEST["DOC_ID_PESSOA"]) )
	$_REQUEST["DOC_ID_PESSOA"] = "";

if ( !isset($_REQUEST["EMAIL_PESSOA"]) )
	$_REQUEST["EMAIL_PESSOA"] = "";

if ( !isset($_REQUEST["CPF_PESSOA"]) )
	$_REQUEST["CPF_PESSOA"] = "";

if ( !isset($_REQUEST["FRESE_SENHA_PESSOA"]) )
	$_REQUEST["FRESE_SENHA_PESSOA"] = "";

?>

<html>
	<head>
		<title>Alunos</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">
<?php

$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];

if ( $acesso != 1 AND $acesso != 2)
{
	echo "<p align='center'>Acesso Restrito</p>";
	exit();
 }

if ( $_REQUEST["NOME_PESSOA"] == "" )
{
	echo "<script> alert(\"Digite o nome do aluno.\"); history.back();</script>";
	exit();
 }
 
if ( $_REQUEST["COD_SEXO"] == "" )
{
	echo "<script> alert(\"Selecione o sexo do aluno.\"); history.back();</script>";
	exit();
 }

if ( $_REQUEST["USER_PESSOA"] == "" )
{
	echo "<script> alert(\"Digite um usuario para o aluno.\"); history.back();</script>";
	exit();
 }

if ( $_REQUEST["SENHA_PESSOA"] == "" )
{
	echo "<script> alert(\"Digite uma senha para o aluno.\"); history.back();</script>";
	exit();
 }
 
 $ok = cadastro($_REQUEST["USER_PESSOA"], $_REQUEST["NOME_PESSOA"], $_REQUEST["DATA_NASC_PESSOA"], $_REQUEST["COD_SEXO"], $_REQUEST["DOC_ID_PESSOA"], $_REQUEST["EMAIL_PESSOA"], $_REQUEST["CPF_PESSOA"], $_REQUEST["SENHA_PESSOA"], $_REQUEST["FRESE_SENHA_PESSOA"], "", "", "", "", "", "", "", "", "", "");

 if ( $ok )
	echo "<script> alert(\"Cadastro feito com sucesso.\"); history.back();</script>";
	
///////////// -- TRANSFORMAR UMA PESSOA EM ALUNO DE UMA TURMA -- //////////



?>
</body>
</html>

