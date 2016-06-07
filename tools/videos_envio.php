<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
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
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>P�gina Principal</a></p>";
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
					echo "ERRO na Altera��o<br>".
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
				echo "ERRO na Inser��o<br>".
					 "<a href=\"javascript:history.back()\">Voltar</a>";
			 }
		break;
		
		case ("Remover"):
			if ( VideoVerificaAcesso($_REQUEST["COD_VIDEO"]) )
			{
				$sucesso = VideoExclue($_REQUEST["COD_VIDEO"]);
	
				if ( $sucesso )
				{
					echo "<br><br>V�deo Removido com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
					
					if ( !isset($_REQUEST["PAGINA"]) )
						$_REQUEST["PAGINA"] = "";
						
					if ( $_REQUEST["PAGINA"] == "videos" )
						echo "<script> window.opener.location.href='./videos.php?PAGINA=".$_REQUEST['PAGINA2']."</script>";
					else
						echo "<script> window.opener.location.reload(true) </script>";
				 }
				else	
					echo "<br><br>ERRO na Remo��o - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
			 }
		break;

	 }
	?>	
</div>
</body>
</html>