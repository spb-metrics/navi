<?php
session_start();
//include_once("../../funcoes_bd.php");
include_once ("./../../config.php");
include_once ($caminhoBiblioteca."/videos.inc.php");

if ( $_SESSION["VIZUALIZA"] == "1" )
{	
	$rsCon = videoChat("consultar", "");
	$linha = mysql_fetch_array($rsCon);

	$_SESSION["VIZUALIZA"] = "2";

	echo $linha["CAMINHO_CAMERA"];
	exit();
 }
else
{
	exit();
 }
?>