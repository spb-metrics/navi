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
include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Disciplinas</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<div align=center>
	<?php
	
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( $acesso != 1 AND $acesso != 2)
	{
		echo "<p align='center'>Acesso Restrito</p>";
		exit();
	 }

	switch ( $_REQUEST["OPCAO"])
	{

		case ( "InserirDisciplina" ):
		
		if ( $_REQUEST["COD_CURSO"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Curso.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["DESC_DIS"] == "" )
		{
			echo "<script> alert(\"Escreva o nome da disciplina.\"); history.back();</script>";
			exit();
		 }
		
//'		if ( $_REQUEST["NRO_CRED_DIS"] == "" )
//'		{
//'			echo "<script> alert(\"Escreva o número de créditos da disciplina.\"); history.back();</script>";
//'			exit();
//'		 }

		$sucesso = disciplinaInsere($_REQUEST["COD_CURSO"], $_REQUEST["DESC_DIS"], $_REQUEST["NRO_CRED_DIS"]);
		
		if ( $sucesso )
			echo "<br><br>Disciplina criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";	
		else
			echo "<br><br>ERRO na criação da disciplina - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		
		break;
		
	case ( "InserirTurma" ):

		if ( $_REQUEST["COD_CURSO"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Curso.\"); history.back();</script>";
			//exit();
		 }
		
		if ( $_REQUEST["COD_DIS"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Disciplina.\"); history.back();</script>";
			//exit();
		 }
		
		if ( $_REQUEST["NOME_TURMA"] == "" )
		{
			echo "<script> alert(\"Escreva o nome da turma.\"); history.back();</script>";
			//exit();
		 }
		
		if ( $_REQUEST["ANO_TURMA"] == "" )
		{
			echo "<script> alert(\"Escreva o ano da turma.\"); history.back();</script>";
			//exit();
		 }

		$sucesso = turmaInsere ($_REQUEST["COD_CURSO"], $_REQUEST["COD_DIS"], $_REQUEST["NOME_TURMA"], $_REQUEST["ANO_TURMA"], $_REQUEST["PERIODO_TURMA"], $_REQUEST["NRO_VAGAS_TURMA"]);
		
		if ( $sucesso )
			echo "<br><br>Turma criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		else
			echo "<br><br>ERRO na criação da Turma - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
			
		break;
		
	case ( "RemoverDisciplina" ):
		
		$sucesso = disciplinaRemove($_REQUEST["COD_DIS"]);
	
		if ( $sucesso )
		{		
			echo "<br><br>Disciplina removida com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>".
			 	 "<script>\n".
			 	 "window.opener.location.reload(true)\n".
				 "</script>\n";
		 }
		else	
				echo "<br><br>ERRO ao remover a disciplina - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
		
		break;
		
	case ( "RemoverTurma" ):
		$sucesso = turmaRemove($_REQUEST["COD_TURMA"]);
		if ( $sucesso )
		{
			echo "<br><br>Turma removida com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>".
				 "<script>\n".
				 "window.opener.location.reload(true)\n".
				 "</script>\n";
		 }
		else	
			echo "<br><br>ERRO ao remover a turma - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
		
		break;

	case ( "AlterarDisciplina" ):
		if ( $_REQUEST["COD_CURSO"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Curso.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["COD_DIS"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Disciplina.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["NOME_TURMA"] == "")
		{
			echo "<script> alert(\"Escreva o nome da turma.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["ANO_TURMA"] == "" )
		{
			echo "<script> alert(\"Escreva o ano da turma.\"); history.back();</script>";
			exit();
		 }

		$sucesso = turmaAltera($_REQUEST["COD_DIS"],$_REQUEST["COD_CURSO"],$_REQUEST["COD_DIS"],$_REQUEST["NOME_TURMA"],$_REQUEST["ANO_TURMA"],$_REQUEST["PERIODO_TURMA"],$_REQUEST["NRO_VAGAS_TURMA"]);
		
		if ( $sucesso )
			echo "<br><br>Turma criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		else
			echo "<br><br>ERRO na criação da Turma - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
			
		break;

	case ( "AlterarTurma" ):
	
		if ( $_REQUEST["COD_CURSO"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Curso.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["COD_DIS"] == "" )
		{
			echo "<script> alert(\"Selecione o campo Disciplina.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["NOME_TURMA"] == "" )
		{
			echo "<script> alert(\"Escreva o nome da turma.\"); history.back();</script>";
			exit();
		 }
		
		if ( $_REQUEST["ANO_TURMA"] == "" )
		{
			echo "<script> alert(\"Escreva o ano da turma.\"); history.back();</script>";
			exit();
		 }

		$sucesso = turmaAltera ($_REQUEST["COD_TURMA"], $_REQUEST["COD_CURSO"], $_REQUEST["COD_DIS"], $_REQUEST["NOME_TURMA"], $_REQUEST["ANO_TURMA"], $_REQUEST["PERIODO_TURMA"], $_REQUEST["NRO_VAGAS_TURMA"]);
		
		if ( $sucesso )
			echo "<br><br>Turma criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		else
			echo "<br><br>ERRO na criação da Turma - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Gerência</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
			
		break;

	 }
	?>
</div>
</body>
</html>
