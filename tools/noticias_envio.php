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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//include_once("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/noticia.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>Noticias</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	  <script language="JavaScript" src="".$url."/js/editor.js"></script>
	  <script language="javascript" type="text/javascript" src="".$url."/js/tiny_mce/tiny_mce.js"></script>
    
  </head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">
<div align=center>
<?php

	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ( $acesso != 1 ) AND ( $acesso != 2 ) AND ( $acesso != 3 ) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }

	if ( !isset($_REQUEST["TITULO_NOTICIA"]) )
		$_REQUEST["TITULO_NOTICIA"] = "";
		
	if ( !isset($_REQUEST["RESUMO_NOTICIA"]) )
		$_REQUEST["RESUMO_NOTICIA"] = "";
		
	if ( !isset($_REQUEST["TEXTO_NOTICIA"]) )
		$_REQUEST["TEXTO_NOTICIA"] = "";
	
	$titulo = str_replace("\n", "<br>", $_REQUEST["TITULO_NOTICIA"]);
	$resumo = str_replace("\n", "<br>", $_REQUEST["RESUMO_NOTICIA"]);
	$texto  = str_replace("\n", "<br>", $_REQUEST["TEXTO_NOTICIA"]);
  $siteRSS=implode(";",$_REQUEST["sitesRSS"]);
  $numeroNoticiasRSSResumo = $_REQUEST["numeroNoticiasRSSResumo"];
  switch ( $_REQUEST["OPCAO"])
	{
		case ("Alterar"):
			if ( NoticiaVerificaAcesso($_REQUEST["COD_NOTICIA"]) )
			{
				$sucesso = NoticiaAltera($_REQUEST["COD_NOTICIA"], $titulo, $resumo, $texto,$siteRSS,$numeroNoticiasRSSResumo );
	
				if ( $sucesso )
					echo "<script> location.href=\"./noticias_operacao.php?OPCAO=Alterar&COD_NOTICIA=". $_REQUEST["COD_NOTICIA"] ."&NOTICIAS_ENVIO=alterar\"</script>";
				else
				{
					echo "ERRO na Alteração<br>".
						 "<a href=\"javascript:history.back()\">Voltar</a>";
				 }
			 }
		break;
					
		case ("Inserir"):
			$sucesso = NoticiaInsere($titulo,$resumo,$texto,$siteRSS,$numeroNoticiasRSSResumo );
		
			if ( $sucesso )
			{
				$num_noticia = NoticiaCodigo( $titulo, $resumo, $texto);
				
				echo "<script> location.href=\"./noticias_operacao.php?OPCAO=Alterar&COD_NOTICIA=". $num_noticia ."&NOTICIAS_ENVIO=inserir\";</script>";
//				Response.redirect "./noticias_operacao.php?opcao=Alterar&COD_NOTICIA="& num_noticia &"&NOTICIAS_ENVIO=inserir"
//'				echo "<br><br>Noticia Inserida com sucesso - <a href='./noticias.php'>Voltar para Noticias</a><br><br><br>".
//'					 "<a href='./noticias_operacao.php?opcao=Alterar&COD_NOTICIA="& num_noticia &"'>Adicionar a noticia a algum local (Ir no link ""Incluir Nova"" na página seguinte)</a>";
			 }						
			else
			{
			  //echo mysql_error();
				echo "ERRO.<br>".
					 "<a href=\"javascript:history.back()\">Voltar</a>";
			 }
		break;
		
		case ("Remover"):
			if ( NoticiaVerificaAcesso($_REQUEST["COD_NOTICIA"]) )
			{
				$sucesso = NoticiaExclue($_REQUEST["COD_NOTICIA"]);
	
				if ( $sucesso )
				{
					//session("atualizar") = true
					
					echo "<br><br>Noticia Removida com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
					
					if ( !isset($_REQUEST["PAGINA"]) )
						$_REQUEST["PAGINA"] = "";
						
					if ( $_REQUEST["PAGINA"] == "noticias" )
						echo "<script> window.opener.location.href='./noticias.php'; </script>";
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
