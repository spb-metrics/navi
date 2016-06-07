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
include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();

?>

<html>
	<head>
		<title>Cursos</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<div align=center>
	<?php
		
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( $acesso != 1 )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>P�gina Principal</a></p>";
		exit();
	 }
	 
	 if ( !isset($_REQUEST["PAGINA"]) )
	 	$_REQUEST["PAGINA"] = "";

	switch ($_REQUEST["OPCAO"])
	{
	
		case ( "InserirCurso" ):

			if ( $_REQUEST["COD_CURSO_ORIGEM"] == "" )
			{
				echo "<Script> alert(\"Selecione o campo Entidade.\"); history.back(); </Script>";
				exit();
			 }
					
			if ( $_REQUEST["DESC_CURSO"] == "" )
			{
				echo "<Script> alert(\"Escreva o nome do curso.\"); history.back(); </Script>";
				exit();
			 }
			
			if ( $_REQUEST["ABREV_CURSO"] == "" )
			{
				echo "<Script> alert(\"Escreva uma abreviatura para o curso.\"); history.back(); </Script>";
				exit();
			 }
			
			if ( $_REQUEST["INSCRICAO_ABERTA"] != 0 AND $_REQUEST["INSCRICAO_ABERTA"] != 1 )
			{
				echo "<Script> alert(\"Selecione o campo Inscri��o Aberta.\"); history.back(); </Script>";
				exit();
			 }

			$sucesso = cursoInsere("curso", $_REQUEST["DESC_CURSO"], $_REQUEST["ABREV_CURSO"], $_REQUEST["INSCRICAO_ABERTA"], $_REQUEST["COD_CURSO_ORIGEM"]);
		
			if ( $sucesso )
			{
				if ( $_REQUEST["PAGINA"] == "fechar" )
				{
					echo "<br><br>Curso criado com sucesso - <a href=\"javascript:window.close()\"> Fechar Janela </a>".
						 "<script> window.opener.location.reload(true) </script>";
				 }
				else
					echo "<br><br>Curso criado com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./curso.php\"> Cursos</a>";
			 }
			else
			{
				if ( $_REQUEST["PAGINA"] == "fechar" )
					echo "<br><br>ERRO na cria��o do curso - <a href=\"javascript:window.close()\"> Fechar Janela </a>";
				else
					echo "<br><br>ERRO na cria��o do curso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./curso.php\"> Cursos</a>";
			 }
			break;

		case ( "InserirOrigem" ):
			if ( $_REQUEST["DESC_CURSO"] == "" )
			{
				echo "<Script> alert(\"Escreva o nome da Entidade.\"); history.back(); </Script>";
				exit();
			 }
			
			$sucesso = cursoInsere("origem", $_REQUEST["DESC_CURSO"], "", "", "");
		
			if ( $sucesso )
			{
				if ( $_REQUEST["PAGINA"] == "voltar" )
				{
					echo "<br><br>Entidade criada com sucesso - <a href=\"./curso_operacao.php?OPCAO=InserirCurso&PAGINA=fechar\"> Voltar </a>".
						 "<script> window.opener.location.reload(true) </script>";
				 }
				else
				{
					echo "<br><br>Entidade criada com sucesso - <a href=\"javascript:window.close()\"> Fechar Janela </a>".
						 "<script> window.opener.location.reload(true) </script>";
				 }
			 }
			else
			{
				if ( $_REQUEST["PAGINA"] == "voltar" )
					echo "<br><br>ERRO na cria��o da entidade - <a href=\"./curso_operacao.php?opcao=InserirCurso&PAGINA=fechar\"> Voltar </a>";
				else				
					echo "<br><br>ERRO na cria��o da entidade - <a href=\"javascript:window.close()\"> Fechar Janela </a>";
			 }
			 break;
	
		case ( "AlterarCurso" ):
			if ( $_REQUEST["COD_CURSO_ORIGEM"] == "" )
			{
				echo "<Script> alert(\"Selecione o campo Entidade.\"); history.back(); </Script>";
				exit();
			 }
					
			if ( $_REQUEST["DESC_CURSO"] == "" )
			{
				echo "<Script> alert(\"Escreva o nome do curso.\"); history.back(); </Script>";
				exit();
			 }
			
			if ( $_REQUEST["ABREV_CURSO"] == "" )
			{
				echo "<Script> alert(\"Escreva uma abreviatura para o curso.\"); history.back(); </Script>";
				exit();
			 }
			
			if ( $_REQUEST["INSCRICAO_ABERTA"] != 0 AND $_REQUEST["INSCRICAO_ABERTA"] != 1 )
			{
				echo "<Script> alert(\"Selecione o campo Inscri��o Aberta.\"); history.back(); </Script>";
				exit();
			 }

			$sucesso = cursoAltera("curso", $_REQUEST["COD_CURSO"], $_REQUEST["DESC_CURSO"], $_REQUEST["ABREV_CURSO"], $_REQUEST["INSCRICAO_ABERTA"], $_REQUEST["COD_CURSO_ORIGEM"]);
		
			if ( $sucesso )
			{
				echo "<br><br>Curso alterado com sucesso - <a href=\"javascript:window.close()\"> Fechar Janela </a>".
					 "<script> window.opener.location.reload(true) </script>";
			 }
			else
				echo "<br><br>ERRO ao alterar o curso - <a href=\"javascript:window.close()\"> Fechar Janela </a>";
			break;

		case ( "AlterarOrigem" ):
			if ( $_REQUEST["DESC_CURSO"] == "" )
			{
				echo "<Script> alert(\"Escreva o nome da Entidade.\"); history.back(); </Script>";
				exit();
			 }
			
			$sucesso = cursoAltera("origem", "", $_REQUEST["DESC_CURSO"], "", "", $_REQUEST["COD_CURSO_ORIGEM"]);
		
			if ( $sucesso )
			{
				echo "<br><br>Entidade alterada com sucesso - <a href=\"javascript:window.close()\"> Fechar Janela </a>".
								"<script> window.opener.location.reload(true) </script>";
			 }
			else
				echo "<br><br>ERRO ao alterar entidade - <a href=\"javascript:window.close()\"> Fechar Janela </a>";
			
			break;
		
		case ( "RemoverCurso" ):
			$sucesso = cursoRemove("curso", $_REQUEST["COD_CURSO"]);
	
			if ( $sucesso )
			{
				echo "<br><br>Curso removido com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>".
								"<script>\n".
								"window.opener.location.reload(true)\n".
								"</script>\n";
			 }
			else	
				echo "<br><br>ERRO ao remover o curso - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
				
			break;

		case ( "RemoverOrigem" ):
			$sucesso = cursoRemove("origem", $_REQUEST["COD_CURSO_ORIGEM"]);
	
			if ( $sucesso )
			{
				echo "<br><br>Entidade removida com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>".
					 "<script>\n".
					 "window.opener.location.reload(true)\n".
					 "</script>\n";
			 }
			else	
				echo "<br><br>ERRO ao remover a entidade - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
				
			break;
			
	 }
	?>
	
</div>
</body>
</html>