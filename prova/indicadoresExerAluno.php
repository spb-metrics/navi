<?
include_once("../config.php");
include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/prova2.inc.php");
session_start();
?>

<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<SCRIPT>
		 function MenuAparecer(a){

			aa = document.getElementById(a).style.display;

			if (aa == "none"){
			document.getElementById(a).style.display = "";
			}else{
			document.getElementById(a).style.display = "none";     
			}
     
		} 
		</SCRIPT>
		<title>Plataforma NAVi</title>
		<link rel="stylesheet" href="<?=$url?>/cursos.css" type="text/css">
		<link rel="stylesheet" href="<?=$url?>/indicadoresaluno/indicadoresaluno.css\" type="text/css">
		<link rel="stylesheet" href="<?=$url?>/css/prova.css" type="text/css">
</head>
<body class="bodybg">
<?


listaIndicadoresProvaAluno();
echo "<table align=\"center\"><tr><td>";
//echo "<p align=\"center\"><a href=\"javascript:history.back()\">Voltar</a></p></tr></td>";
echo "<a href=\"#\" onclick=\"window.location.href ='index.php'\">Voltar</a>\n";
echo "</table>";
?>
</body>
</html>