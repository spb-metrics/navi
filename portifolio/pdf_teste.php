<?php
session_name('multinavi'); session_start();
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/arquivo.inc.php");

	$rsCon = apoioCaminhoAluno($_REQUEST["COD_ARQUIVO"],$_REQUEST["COM"]);
	if ( (! $rsCon)  )
		exit();

	$linha = mysql_fetch_array($rsCon);
	
	if ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) ){
		exit();
		}

if ($linha["CAMINHO_LOCAL_ARQUIVO"]!="" )
	{
		$arq= $caminhoUpload .$linha["CAMINHO_LOCAL_ARQUIVO"];
		if ( file_exists($arq) ) {
			$temp = explode("/", $arq);
			$nome = $temp[count($temp) - 1];
		}else{
	        $arq= $caminhoUpload1 .$linha["CAMINHO_LOCAL_ARQUIVO"];
			$temp = explode("/", $arq);
			$nome = $temp[count($temp) - 1];

		}
		
		
	      if (empty($nome)) { $nome="portfolio aluno"; }

     
			header("Pragma: public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Description: File Transfer");	
			header("Content-type: "  .$linha["TIPO_ARQUIVO"]);
			header("Content-Disposition: inline; filename=". $nome); 
			header("Content-Transfer-Encoding: binary");
	
	
	/*// Nós estaremos enviando um PDF	
	header ("Content-type: " . $linha["TIPO_ARQUIVO"] );

	// Será chamado downloaded.pdf - attachment - inline
	
	
	header("Content-Disposition: inline; filename=". $nome);
	//header("Content-Disposition: attachment; filename=". $nome );
	// A fonte do PDF é original.pdf	*/
  flush();
	readfile($arq);
	  
  }
   else
	 {
	 	echo "Caminho vazio";
		exit();
	  }		
?>