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
//'			echo "<script> alert(\"Escreva o n�mero de cr�ditos da disciplina.\"); history.back();</script>";
//'			exit();
//'		 }

		$sucesso = disciplinaInsere($_REQUEST["COD_CURSO"], $_REQUEST["DESC_DIS"], $_REQUEST["NRO_CRED_DIS"]);
		
		if ( $sucesso )
			echo "<br><br>Disciplina criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";	
		else
			echo "<br><br>ERRO na cria��o da disciplina - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		
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
			echo "<br><br>Turma criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		else
			echo "<br><br>ERRO na cria��o da Turma - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
			
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
			echo "<br><br>Turma criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		else
			echo "<br><br>ERRO na cria��o da Turma - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
			
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
			echo "<br><br>Turma criada com sucesso - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
		else
			echo "<br><br>ERRO na cria��o da Turma - <a href=\"./../ferramentas.php\" target=\"_parent\">Ferramentas de Ger�ncia</a> - <a href=\"./disciplina.php\"> Disciplinas/Turmas</a>";
			
		break;

	 }
	?>
</div>
</body>
</html>
