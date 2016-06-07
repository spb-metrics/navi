<?php
include("../config.php");
include($caminhoBiblioteca."/perfil.inc.php");
//include("../configarquivos.php");

$rsCon=dadosperfil($_REQUEST["COD_PESSOA"]);

$linha = mysql_fetch_array($rsCon);

switch($_REQUEST["CASE"]) {
case "FOTO_REDUZIDA":
  
	if(!empty($linha["FOTO_REDUZIDA"])){
	  $caminhoLocal= $caminhoUpload.$linha["FOTO_REDUZIDA"];
	  echo "<br>Dentro do primeiro IF".$caminhoLocal;
	}else{
			$caminhoLocal= $caminhoUpload."/fotos/0/nophoto.jpg";

	}

	if(file_exists($caminhoLocal)){
		$temp = explode("//", $caminhoLocal);
		$nome = $temp[count($temp) - 1];
		
	}
	
	if(!file_exists($caminhoLocal))
	{
		if(!empty($linha["FOTO_REDUZIDA"])){
			$caminhoLocal = $caminhoUpload1.$linha["FOTO_REDUZIDA"];
		}else{
			$caminhoLocal = $caminhoUpload1."/fotos/0/nophoto.jpg";

		}
		$temp = explode("//", $caminhoLocal);
		$nome = $temp[count($temp) - 1];
	}
	
	if((substr($linha["FOTO_REDUZIDA"],0,4))== "http")
	{
		$caminhoLocal = $linha["FOTO_REDUZIDA"];
		header("Location: " . $caminhoLocal);
	}
	 
	
	
break;

default:
	if(!empty($linha["FOTO"])){
	$caminhoLocal= $caminhoUpload.$linha["FOTO"];
	}else{
		$caminhoLocal= $caminhoUpload."/fotos/0/nophoto.jpg";
	}

 // echo $caminhoLocal; die();

  //echo "primeiro caminho: ". $caminhoLocal;

	if(file_exists($caminhoLocal))
	{
		$temp = explode("//", $caminhoLocal);
		$nome = $temp[count($temp) - 1];
		
	}
	if(!file_exists($caminhoLocal))
	{
		
		if(!empty($linha["FOTO"])){
			$caminhoLocal = $caminhoUpload1.$linha["FOTO"];
		}else{
			$caminhoLocal= $caminhoUpload1."/fotos/0/nophoto.jpg";

		}
		$temp = explode("//", $caminhoLocal);
		$nome = $temp[count($temp) - 1];
	}
	if((substr($linha["FOTO"],0,4))== "http")
	{
		$caminhoLocal = $linha["FOTO"];
		header("Location: " . $caminhoLocal);
		exit;
	}
	
	break;
}

//flush();
header ("Content-type: " . $linha["TIPO_ARQUIVO"] ); 
echo "<br> La No FIM:  ".$caminhoLocal;
readfile($caminhoLocal);
?>
