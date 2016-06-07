<?php
session_start();
//include_once("./../../funcoes_bd.php");
include_once ("./../../config.php");
include_once ($caminhoBiblioteca."/videos.inc.php");
?>
<html>
	<head> </head>
	<body>
<?php
if ( !isset($_REQUEST["ATIVAR"]) )
	$_REQUEST["ATIVAR"] = "";
if ( !isset($_REQUEST["DESATIVAR"]) )
	$_REQUEST["DESATIVAR"] = "";

	if ( ( $_SESSION["COD_ADM"] != "" ) OR ( $_SESSION["COD_PROF"] != "" ) )
	{
		if ( $_REQUEST["ATIVAR"] != "" )
		{
			$ok = videoChat("atualizar", 1);
			echo "<Script language='JavaScript'> location.href='./index.php'; </Script>";
		 }
		else
		{
			if ( $_REQUEST["DESATIVAR"] != "" )
			{
				$ok = videoChat("atualizar", 0);
				echo "<Script language='JavaScript'> location.href='./index.php'; </Script>";
			 }
		 }
	 }

?>

	</body>
</html>