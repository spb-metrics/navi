<?php
session_start();
//le as variaveis de sessao q ira usar e ja encerra a sessao, para nao bloquear os outros frames 
session_write_close();
//include_once("./../../funcoes_bd.php");
/*
$sRoomID = $_SESSION["ROOM_ID"];
$sUserID = $_SESSION["USER_ID"];
*/
include_once ("./../../config.php");
include_once ($caminhoBiblioteca."/videos.inc.php");

?>
<html>
	<head>
		<title>CHAT</title>
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 
			<table width="100%" height='100%' border="0" cellpadding="0" cellspacing="0" >
				<tr> 
					<td>
<?php
if ( !( ( $_SESSION["COD_ADM"] != "" ) OR ( $_SESSION["COD_PROF"] != "" ) OR ( $_SESSION["COD_AL"] != "" ) ) )
{
	msg("Aula interativa dispon&iacute;vel apenas para alunos cadastrados.");
	exit();
 }


if ( ( $_SESSION["NIVEL_ACESSO"] == 2 ) OR ( $_SESSION["NIVEL_ACESSO"] == 3 ) )
{
	$rsChat = videoChat("consultar", "");

	if ( $rsChat )
	{
		if ( $linhaChat = mysql_fetch_array($rsChat) )
		{
			echo "<div align='center'>";
			echo "	<form name='Video' action='./video.php' >";
			echo "O video está habilitado! <br><br>";
			
			if ( $linhaChat["EM_USO"] == 1 ) {
				echo "O video está ativo. <br><br>";
        echo "<input type='submit' name='DESATIVAR' value='Desativar Video'> ";				
			  echo "<br><br>* No final de cada aula com o uso do video é necessário 'Desativar Video'";
			}
			else {
			  echo " O video está inativo. Clique em 'Ativar Video' para utilizar durante a aula.<br><br>";
			  echo "		<input type='submit' name='ATIVAR' value='Ativar Video'> ";
      }							

			echo "	</form>";
			echo "</div>";				
		 }
		 echo "<hr/>";
	 }
 }
if ( ( $_SESSION["COD_PESSOA"] != "" ) AND ( $_SESSION["COD_TURMA"] != "" ) )
{
	$rsChat2 = videoChat("consultar", "");

	if ( $rsChat2 )
	{
		if ( $linhaChat2 = mysql_fetch_array($rsChat2) )
		{
			echo "<div align='center'>";

			if ( $linhaChat2["EM_USO"] == 1 )
			{
				echo "O video está em uso nesta instância. <br> <br>";

				echo "	<form name='Video'   action='./index_2.php'  >";

				echo "		<input type='submit' name='COM_VIDEO' value='Chat Café com Video'> ";
				echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
				echo "		<input type='submit' name='SEM_VIDEO' value='Chat Café sem Video'  > ";
        //onClick=\"javascript:window.open('./index_2.php','', 'fullscreen=yes, scrollbars=none');\"
				echo "	</form>";
				echo "</div>";
			 }				
			else 
			{
				echo "	<form name='Video'   action='./index_2.php'  >";
				echo "		<input type='submit' name='SEM_VIDEO' value='Entrar no Chat Café' > ";
         //onClick=\"javascript:window.open('./index_2.php','', 'fullscreen=yes, scrollbars=none');\"
        echo "	</form>";
				echo "</div>";
			 }
		}
		else
		{
				echo "<div align='center'>";
				
				echo "	<form name='Video' action='./index_2.php' >";
				echo "		<input type='submit' name='SEM_VIDEO' value='Entrar no Chat Café'> ";
				echo "	</form>";
				echo "</div>";
		 }
	 }
 }
?>
					</td>
				</tr>
			</table>
		</td> 
	</tr>
</table>
</body>
</html>
