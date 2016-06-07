<?php

//include_once( "./../../funcoes_bd.php");
include_once ("./../../config.php");
include_once ($caminhoBiblioteca."/forum.inc.php");
session_name('multinavi'); session_start();

if ( ($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == ""))
{
	echo "<p align='center'> <b>Forum disponível apenas para alunos cadastrados.</b> </p>";
	exit();
}

$texto    = $_REQUEST["TEXTO"];
$resposta = $_REQUEST["RESPOSTA"];
$cod_sala = $_REQUEST["COD_SALA"];
$tipoMsg  = $_REQUEST["tipo_msg"];




//faz a traducao dos emoticons ( se houverem )
if ($_SESSION['configForum']['usaEmoticons']) {
  $texto = traduzEmoticons($texto);
}

$ok = forum_inserir(addSlashes($texto), $resposta, (int) $_REQUEST["codMainThread"],$cod_sala, $_SESSION["codInstanciaGlobal"],$tipoMsg);


if ($ok)
{
echo "<script> location.href='./forum.php?topico=".$_REQUEST["topico"]."&acao=".$_REQUEST["acao"]."&dataFim=".$_REQUEST["dataFim"]."&dataInicio=".$_REQUEST["dataInicio"]."&COD_PESSOA=".$_REQUEST["COD_PESSOA"]."&COD_SALA=".$cod_sala."'</script>";
}
else
{
	echo "Erro ao inserir texto - <a href=\"./forum.php?topico=".$_REQUEST["topico"]."&acao=".$_REQUEST["acao"]."&dataFim=".$_REQUEST["dataFim"]."&dataInicio=".$_REQUEST["dataInicio"]."&COD_PESSOA=".$_REQUEST["COD_PESSOA"]."&COD_SALA=".$cod_sala."\"> Voltar </a>";
}
 
exit();

?>


