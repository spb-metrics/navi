<?php	include_once("../config.php");
		include_once ($caminhoBiblioteca."/perfil.inc.php");?>
<html>
<head>
<title>Perfil</title>
<link rel="stylesheet" href=".././cursos.css" type="text/css">
<link rel="stylesheet" href="./../sca.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">

<?
$rsCon=dadosPerfil($_REQUEST["COD_PESSOA"]);
	if($rsCon) {
		if ($linha = mysql_fetch_array($rsCon)){
			
		?>
<table width="606" border="1" height="347" align="center">
  <tr>
    <td height="348"> 
      <table width="597" border="0" height="342">
        <tr> 
          <td height="326" width="374"  valign="top"><br>
    <p align="left"><?echo "<h3><i>".$linha['NOME_PESSOA']."</i></h2>";?></p><br>
    <p align="left"><b>Apresentação Pessoal:</b></p>
    <p align="left"><?echo nl2br($linha["DESC_PERFIL"]); ?></p><br>
	<p align="left"><b>Links de Apresentação Pessoal:</b></p>
	<p align="left">
	<?	$links= explode(";",$linha["LINK_PERFIL"]);
		$count= count($links);
	//lista links
	for($i=0; $i<$count; $i ++){?>
		<a href="<?=$links[$i]?>" target="_blank"><?=$links[$i]?></a><br>
	<?}?>
	</p>
   </td>
          <td width="213" valign="top" height="326"> 
            <p align="right"><img src="foto.php?COD_PESSOA=<?=$_REQUEST["COD_PESSOA"]?>&CASE=" height="<? echo ALTURA_FOTO;?>"  width="<? echo LARGURA_FOTO;?>" border="2"></p></td>
  </tr>
</table>
</td></tr>
</table>
<?}}?>
<table width="100" border="0" align="center">
  <tr>
    <td><div align="center"><a href="javascript:history.back()">voltar</a></div></td>
    <td><a href="recados.php?COD_PESSOA=<?=$_REQUEST["COD_PESSOA"]?>">recados</a></td>
  </tr>
</table>
<p align="center">&nbsp;</p>
</body>
</html>
<?//include("foto.php?COD_PESSOA=".$_REQUEST["COD_PESSOA"]."")?>
