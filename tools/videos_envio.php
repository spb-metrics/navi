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

//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Videos</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<div align="center">

<?php
	
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ( $acesso != 1 ) AND ( $acesso != 2 ) AND ( $acesso != 3 ) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }
	
	if ( !isset($_REQUEST["DESC_VIDEO"]) )
		$_REQUEST["DESC_VIDEO"] = "";
		
	if ( !isset($_REQUEST["CAM_HTTP"]) )
		$_REQUEST["CAM_HTTP"] = "";
    
  if ( !isset($_REQUEST["DOWNLOAD"]) )
		$_REQUEST["DOWNLOAD"] = "";
  
	$download=$_REQUEST["DOWNLOAD"];
	$desc_video = str_replace("\n", "<br>", $_REQUEST["DESC_VIDEO"]);
	$cam_http   = str_replace("\n", "<br>", $_REQUEST["CAM_HTTP"]);
	$cam_http_discada   = str_replace("\n", "<br>", $_REQUEST["CAM_HTTP_DISCADA"]);

  if ($download=="1"){$download="1";}
  else {$download="0";}
	
	switch ( $_REQUEST["OPCAO"])
	{
		case ("Alterar"):
      
			if ( VideoVerificaAcesso($_REQUEST["COD_VIDEO"]) )
			{
				$sucesso = VideoAltera($_REQUEST["COD_VIDEO"], $desc_video, $cam_http, $cam_http_discada, $download );
	
				if ( $sucesso )
					echo "<script> location.href=\"./videos_operacao.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Alterar&COD_VIDEO=". $_REQUEST["COD_VIDEO"] ."&VIDEOS_ENVIO=alterar\"</script>";
				else
				{
					echo "ERRO na Alteração<br>".
						 "<a href=\"javascript:history.back()\">Voltar</a>";
				 }
			 }
		break;
					
		case ("Inserir"):
          
			$sucesso = VideoInsere($desc_video, $cam_http, $cam_http_discada, $download);
		 echo mysql_error();
			if ( $sucesso )
			{
        $num_video = VideoCodigo( $desc_video, $cam_http, $cam_http_discada );
   

				echo "<script> location.href=\"./videos_operacao.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Alterar&COD_VIDEO=". $num_video ."&VIDEOS_ENVIO=inserir\";</script>";
			 }						
			else
			{
				echo "ERRO na Inserção<br>".
					 "<a href=\"javascript:history.back()\">Voltar</a>";
			 }
		break;
		
		case ("Remover"):
			if ( VideoVerificaAcesso($_REQUEST["COD_VIDEO"]) )
			{
				$sucesso = VideoExclue($_REQUEST["COD_VIDEO"]);
	
				if ( $sucesso )
				{
					echo "<br><br>Vídeo Removido com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
					
					if ( !isset($_REQUEST["PAGINA"]) )
						$_REQUEST["PAGINA"] = "";
						
					if ( $_REQUEST["PAGINA"] == "videos" )
						echo "<script> window.opener.location.href='./videos.php?PAGINA=".$_REQUEST['PAGINA2']."</script>";
					else
						echo "<script> window.opener.location.reload(true) </script>";
				 }
				else	
					echo "<br><br>ERRO na Remoção - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
			 }
		break;

	 }
	?>	
</div>
</body>
</html>