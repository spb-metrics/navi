<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include("./configchat.php");
session_name(SESSION_NAME); session_start();
security();
?>
<html>
	<head>
		<title>Sala de Transmiss&atilde;o</title>
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>
<body>


<?php

if ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) )
{

	exit();
}
?>
	<table align='center' width='100%' cellspacing=0 cellpadding=0> <tr>     
<?
if ( !isset($_REQUEST["COM_VIDEO"]) )
	$_REQUEST["COM_VIDEO"] = "";
else
	if ( $_REQUEST["COM_VIDEO"] != "" )
	{
		$_SESSION["VIZUALIZA"] = "1";
		echo "<td align='center' valign='top' width='120' style='padding-top:5;'>";
		echo "<object width='120' height='110'  id=\"Video Aula\" classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\"";
		echo "		codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715\"";
		echo "		standby=\"Carregando os componentes do Microsoft Windows Media Player ...\"";
		echo "		type=\"application/x-oleobject\">";
		echo "	<param name=\"src\" value=\"";
		echo "./redireciona.php";
	//	echo $_REQUEST["CAMINHO"];
		echo "\">";
		echo "	<param name=\"ShowControls\" value=\"0\">";
		echo "	<param name=\"autostart\" value=\"true\">";
		echo "  <param name=\"ShowStatusBar\" value=\"1\">";
		echo "</object>";
		echo "</TD>";
	//	Session("VIZUALIZA") = "2"	
  	//echo " &nbsp;&nbsp;&nbsp;&nbsp; </td>";
	 }
   //le as variaveis de sessao q ira usar e ja encerra a sessao, para nao bloquear os outros frames 
   session_write_close();
?>
	 <td align='center' style='filter:shadow(color:gray,direction=135); padding:4px;' >
   <iframe id="framechat" frameborder=0 src="./chat.php" style="width:100%; height:435px; z-index:1; border:1px solid  #000000;"></iframe> 
	 </td>

	</tr> 
	</table>		

</body>
</html>
