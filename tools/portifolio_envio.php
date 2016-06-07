<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de dúvidas e/ou sugestões, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, portão K. Porto Alegre - RS. CEP: 90035-003

Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU conforme publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (a seu critério) qualquer versão posterior.

    Este programa é distribuído na expectativa de que seja útil, porém, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implícita de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.
    Consulte a Licença Pública Geral do GNU para mais detalhes.
    

    Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto com este programa;
    se não, escreva para a Free Software Foundation, Inc., 
    no endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

ini_set("display_errors",1);
error_reporting(E_ALL);
//include_once("../funcoes_bd.php");
include_once("../config.php");
include($caminhoBiblioteca."/arquivo.inc.php");
include($caminhoBiblioteca."/portfolio.inc.php");
include($caminhoBiblioteca."/funcoesftp.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();

?>

<html>
	<head>
		<title>Portfólio</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		
		<script language=javascript>
		function YNconfirm(txt)
		{  return typeof(suporteVBscript)=="undefined"?confirm(txt):VBconfirm(txt)==6
		 }
		</script>
		
		<script language=vbscript>
			suporteVBscript=1
			function VBconfirm(mensagem)
					VBconfirm=msgbox(mensagem,vbyesno)
			end function
		</script>				
	</head>
	
	
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<div align=center>

<?php

	function confNum($Num)
	{
		if ($Num < 10) 
			return "0" . strval($Num);
		else		
			return strval($Num);
		
	}
	
	//$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ($_SESSION['userRole']!=ALUNO) AND ($_SESSION['userRole']!=ADMINISTRADOR_GERAL))
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../index.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }
	
	if ( !isset($_REQUEST["DESC_ARQUIVO"]) )
		$_REQUEST["DESC_ARQUIVO"] = "";
		
	if ( !isset($_REQUEST["CAM_LOCAL"]) )
		$_REQUEST["CAM_LOCAL"] = "";

	if ( !isset($_REQUEST["TAMANHO"]) )
		$_REQUEST["TAMANHO"] = "";

	if ( !isset($_REQUEST["NOVO_NOME"]) )
		$_REQUEST["NOVO_NOME"] = "";

	if ( !isset($_REQUEST["TIPO"]) )
		$_REQUEST["TIPO"] = "";
		
	if ( !isset($_REQUEST["SUBSTITUIR"]) )
		$_REQUEST["SUBSTITUIR"] = "";		
		
	$desc_arquivo = str_replace("\n", "<br>", $_REQUEST["DESC_ARQUIVO"]);
	$cam_local    = str_replace("\n", "<br>", $_REQUEST["CAM_LOCAL"]);
	$novo_nome    = $_REQUEST["NOVO_NOME"];
	$tamanho	  = $_REQUEST["TAMANHO"];
	$tipo		  = $_REQUEST["TIPO"];
	
	switch ( $_REQUEST["OPCAO"])
	{
		case ("Alterar"):
		
      if ( PortVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
			{
					$erro = false;
						
		/*	if(substr($_REQUEST["CAM_LOCAL"],0,4)=='http' )
			{
					 $sucesso = PortAltera($_REQUEST["COD_ARQUIVO"], $desc_arquivo, $_REQUEST["CAM_LOCAL"], "", "");
					 if($sucesso)
					 {
						
						echo "<script> location.href=\"./portifolio_operacao.php?OPCAO=Alterar&COD_ARQUIVO=". $_REQUEST["COD_ARQUIVO"] ."&PORTIFOLIO_ENVIO=alterar\"</script>";
					  }
					  else
					 {
						echo " ERRO na alteração - <a href=\"javascript:history.back()\">Voltar</a>";
					  }
					  
			 }			
			else
			{*/
    	if ( $_FILES["ARQUIVO_NOVO"]["size"] > TAMANHO_MAXIMO_ARQUIVO )  {	
        echo "<br><br> Arquivo com tamanho maior que o permitido de ".(TAMANHO_MAXIMO_ARQUIVO/1000000)." Mb.";
    		$erro = true;
    	}
					 
					if ( (! $erro) and ($_FILES["ARQUIVO_NOVO"]["size"] == 0) )
						{	//	echo "<br><br> Sem alteração de arquivo.";
							
							$cam_local_novo = "";
							
							if ($novo_nome != "")
							{
								// Procura caracteres invalidos
	
									$pos = strpos($novo_nome, "\\");
									
									if ($pos === false)
										$pos = strpos($novo_nome, "/");
									if ($pos === false)	
										$pos = strpos($novo_nome, ":");
									if ($pos === false)
										$pos = strpos($novo_nome, "*");
									if ($pos === false)
										$pos = strpos($novo_nome, "?");
									if ($pos === false)
										$pos = strpos($novo_nome, "<");
									if ($pos === false)
										$pos = strpos($novo_nome, ">");
									if ($pos === false)
										$pos = strpos($novo_nome, "|");
									if ($pos === false)
										$pos = strpos($novo_nome, "\"");
										
									if ($pos !== false)									
									{	echo "Os seguintes caracteres não sao permitidos em nomes de arquivos: \\ / : * ? < > | \"";
										$erro = true;
									 }
								
								if (! $erro)	 
								{
									$temp = explode ("//", $cam_local);
									$temp[count($temp)-1] = $novo_nome;
									$cam_local_novo = implode ("//", $temp); 		
			
									if ( $cam_local != $cam_local_novo )																
										rename( $cam_local, $cam_local_novo);
										
									$rsConN = listaPort($_REQUEST["COD_ARQUIVO"],"","","");
									$linhaN = mysql_fetch_array($rsConN);								

									$cam_local_novo_2 = str_replace("////", "//", $cam_local_novo);										
									$sucesso = PortAltera($_REQUEST["COD_ARQUIVO"], $desc_arquivo, $cam_local_novo_2, $linhaN["TAMANHO_ARQUIVO"], $linhaN["TIPO_ARQUIVO"]);													
								 }
							}
							else
							{
								// echo "<br><br> Sem alteração no nome";
								
								$cam_local_2 = str_replace("////", "//", $cam_local);								
								$sucesso = PortAltera($_REQUEST["COD_ARQUIVO"], $desc_arquivo, $cam_local_2, $linhaN["TAMANHO_ARQUIVO"], $linhaN["TIPO_ARQUIVO"]);													
							}														
						 }
					
			// Cria os diretorios Locais - 	d:\upload\cursosnavi\\portfolio
					
					
					if ( (! $erro) and ($_FILES["ARQUIVO_NOVO"]["size"] > 0) )
					{
						$CAMINHO = $caminhoUpload."/portfolio/". confNum($_SESSION["COD_PESSOA"]) . "/";
												
						if (! file_exists($CAMINHO))
							mkdir($CAMINHO);		
							
					}	// Faz upload local do arquivo
				
					


			$temp = explode('/',$cam_local); $nomeAntigo = $temp[count($temp)-1];
    		if ($nomeAntigo!=$_FILES["ARQUIVO_NOVO"]["name"] && file_exists($CAMINHO . $_FILES["ARQUIVO_NOVO"]["name"]))	{
    			echo "Já existe um arquivo com esse nome.";
    			$erro = true;
    		} 
    		else {
								
						if (move_uploaded_file($_FILES["ARQUIVO_NOVO"]["tmp_name"], $CAMINHO . $_FILES["ARQUIVO_NOVO"]["name"])) 	{
						  //duplica($CAMINHO .$_FILES["ARQUIVO_NOVO"]["name"], $_FILES["ARQUIVO_NOVO"]["name"], "portfolio/". confNum($_SESSION["COD_PESSOA"]) );
							echo "<br><br> O arquivo foi carregado com sucesso. ";

						
							//	Apagar arquivo antigo
						if ($nomeAntigo!=$_FILES["ARQUIVO_NOVO"]["name"]) {
						$variavelAuxiliar = $caminhoUpload.$cam_local;
  						if(file_exists($variavelAuxiliar))
  						{
  							unlink( str_replace("//", "/", $variavelAuxiliar) );
  							delete_via_ftp($variavelAuxiliar);
  						}
						}	// Altera mesmo que tenha dado erro ao apagar arquivo antigo.
							$CAMINHO = "/portfolio/". confNum($_SESSION["COD_PESSOA"]) . "/";
							$CAMINHO_2 = str_replace("////", "//", $CAMINHO);							
							$sucesso = PortAltera($_REQUEST["COD_ARQUIVO"], $desc_arquivo, $CAMINHO_2 . $_FILES["ARQUIVO_NOVO"]["name"], $_FILES["ARQUIVO_NOVO"]["size"], $_FILES["ARQUIVO_NOVO"]["type"] );
							
						}
						else 
						{
							echo "<br><br> Erro de upload. ";
							$erro = true;
						}						
					}

				if ( (! $erro) and ($sucesso) ){
					echo "<script> location.href=\"./portifolio_operacao.php?PAGINA=".$_REQUEST["PAGINA"]."&OPCAO=Alterar&COD_ARQUIVO=". $_REQUEST["COD_ARQUIVO"] ."&APORTIFOLIO_ENVIO=alterar\"</script>";
				}
        else
				{
					echo "ERRO na Alteração<br>".
						 "<a href=\"javascript:history.back()\">Voltar</a>";
				 }
			//} 
		 }
		break;
					
		case ("Inserir"):
		
	/*	if($_REQUEST["LINK_NOVO"] AND ($_REQUEST["LINK_NOVO"]!= "http://") )
		{
  			 $sucessolink = PortInsere($desc_arquivo,  $_REQUEST["LINK_NOVO"], "", "");
			 if($sucessolink)
			 {
			    $num_arquivo =ApoioCodigo($desc_arquivo,$_REQUEST["LINK_NOVO"], "", "");
				echo "<script> location.href=\"./portifolio_operacao.php?OPCAO=Alterar&COD_ARQUIVO=". $num_arquivo ."&PORTIFOLIO_ENVIO=inserir\";</script>";
			  }
			  else
			 {
				echo " ERRO na Inserção - <a href=\"javascript:history.back()\">Voltar</a>";
			  }
			  
		 }			
		else
		{*/			
					$erro = false;
				
        	if ( $_FILES["ARQUIVO_NOVO"]["size"] > TAMANHO_MAXIMO_ARQUIVO )  {	
            echo "<br><br> Arquivo com tamanho maior que o permitido de ".(TAMANHO_MAXIMO_ARQUIVO/1000000)." Mb.";
        		$erro = true;
        	}

					if ( (! $erro) and ($_FILES["ARQUIVO_NOVO"]["size"] == 0) ) 
					{	echo "<br><br> Arquivo não recebido.";
						$erro = true;
					}
					 
					if ((! $erro) and (! is_uploaded_file($_FILES["ARQUIVO_NOVO"]["tmp_name"])))
					{	echo "<br><br> Não foi feito upload do arquivo.";
						$erro = true;					
					 }

			// Cria os diretorios Locais - 	d:\upload\cursosnavi\\texto\\
					
					if (! $erro)
					{
						$CAMINHO = $caminhoUpload."/portfolio/". confNum($_SESSION["COD_PESSOA"]) . "/";
						
            if (!file_exists($CAMINHO))
							mkdir($CAMINHO);
              
            							
						// Faz upload local do arquivo
				
						if (file_exists($CAMINHO . $_FILES["ARQUIVO_NOVO"]["name"]))
						{
							echo "Já existe um arquivo com esse nome.";
							$erro = true;
						}
				
						if (! $erro)		
						{
							if (move_uploaded_file($_FILES["ARQUIVO_NOVO"]["tmp_name"], $CAMINHO . $_FILES["ARQUIVO_NOVO"]["name"])) {
							 	//duplica($CAMINHO .$_FILES["ARQUIVO_NOVO"]["name"], $_FILES["ARQUIVO_NOVO"]["name"], "portfolio/". confNum($_SESSION["COD_PESSOA"]) );
								// echo "<br><br> O arquivo foi carregado com sucesso. ";
							}
							else 
							{
								echo "<br><br> Erro de upload. ";
								$erro = true;
							}						
						}
					}
					
			if (! $erro)							
			{
				$CAMINHO = "//portfolio//". confNum($_SESSION["COD_PESSOA"]) . "//";
				$CAMINHO_2 = str_replace("////", "//", $CAMINHO);
				$sucesso = PortInsere($desc_arquivo, $CAMINHO_2 . $_FILES["ARQUIVO_NOVO"]["name"], $_FILES["ARQUIVO_NOVO"]["size"], $_FILES["ARQUIVO_NOVO"]["type"]);
			}				

			if ( (! $erro) and ($sucesso) )
			{
				$CAMINHO = "//portfolio//". confNum($_SESSION["COD_PESSOA"]) . "//";
				$CAMINHO_2 = str_replace("////", "//", $CAMINHO);
							
				$num_arquivo =ApoioCodigo($desc_arquivo, $CAMINHO_2 . $_FILES["ARQUIVO_NOVO"]["name"], $_FILES["ARQUIVO_NOVO"]["size"], $_FILES["ARQUIVO_NOVO"]["type"]);
				
        echo "<script> location.href=\"./portifolio_operacao.php?OPCAO=Alterar&COD_ARQUIVO=". $num_arquivo ."&PORTIFOLIO_ENVIO=inserir\";</script>";
			 }						
			else
			{
				echo " ERRO na Inserção - <a href=\"javascript:history.back()\">Voltar</a>";
			 }
		// }			 
		break;
		
		case ("Remover"):
			//if ( PortVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
			//{		
				
					$erro = false;

					$rsConN = listaPort($_REQUEST["COD_ARQUIVO"],"","",$_SESSION['COD_PESSOA']);
				
					if ( (! $rsConN) or (mysql_num_rows($rsConN)== 0) )
					{
						echo "<br><br> Arquivo não encontrado.";
						$erro = true;
					}
						
					if (! $erro	)
					{
						$linhaN = mysql_fetch_array($rsConN);
					//   if((substr($linhaN["CAMINHO_LOCAL_ARQUIVO"], 0, 4))!= "http") 
					  // {
						$variavelAuxiliar =$caminhoUpload.$linhaN["CAMINHO_LOCAL_ARQUIVO"];
						if (file_exists($variavelAuxiliar))		{
							//	Apagar arquivo antigo
							unlink( $variavelAuxiliar);
						}
						$variavelAuxiliar =$caminhoUpload1.$linhaN["CAMINHO_LOCAL_ARQUIVO"];
            delete_via_ftp($linhaN["CAMINHO_LOCAL_ARQUIVO"]);																						
					}
								
				if (! $erro)
					$sucesso = ApoioExclue($_REQUEST["COD_ARQUIVO"]);
	
				if ( (! $erro) and ($sucesso) )
				{
					echo "<br><br> Arquivo Removido com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
					
					if ( !isset($_REQUEST["PAGINA"]) )
						$_REQUEST["PAGINA"] = "";
						
					if ( $_REQUEST["PAGINA"] == "PORTIFOLIO" )
						echo "<script> window.opener.location.href='./portifolio.php'; </script>";
					elseif($_REQUEST["PAGINA"] == "instancia")
						echo "<script> window.opener.location.href='../portifolio/interno.php?COD_AL=".$_REQUEST["COD_AL"]."'; </script>";
					else
						echo "<script> window.opener.location.reload(true) </script>";
					
				 }
				else	
					echo " ERRO na Remoção - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
			 //}
		break;

	 }
	?>	
</div>
</body>
</html>
