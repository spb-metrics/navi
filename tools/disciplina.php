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
include_once($caminhoBiblioteca."/autenticacao.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
	
<html>
	<head>
		<title>Disciplinas</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php

	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
//	'Response.write acesso

	if ( $acesso != 1 AND $acesso != 2 )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }
?>

<div align=center> <br><font size=4> <b> Disciplinas / Turmas </b> </font> </div>

<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align="right">
			<a href="./../ferramentas.php" target="_parent">Ferramentas de Gerência</a>
			- <a href="javascript:history.back()">Voltar</a>
		</td>	
	</tr>
</table>

<center>

<?php
if ( $acesso == 1 )
	$rsConN = listaAcesso(15, "", "", "");
else
	$rsConN = listaAcesso(5, "", "", "");
	
if ( !isset($_REQUEST["COD_CURSO"]) )
	$_REQUEST["COD_CURSO"] = "";
	
if ( !isset($_REQUEST["MOSTRAR"]) )
	$_REQUEST["MOSTRAR"] = "";
?>

<form action="disciplina_envio.php" method="post">
	<table width="80%" bordercolor="#CCCCCC" border="1">
		<tr>
			<td width="40"><b>Criar:</b></td>
			<td>	
				<table align="left" width="100%">
					<tr>
						
              <td width="30%">Curso*</td>
						<td><select name="COD_CURSO">
								<option value='' selected> Selecione um curso: </option>
								<?php
								while ( $linhaN = mysql_fetch_array($rsConN) )
									echo " <option value='" . $linhaN["COD_CURSO"] . "'>" . $linhaN["DESC_CURSO_ORIGEM"] . " - " . $linhaN["ABREV_CURSO"] . "</option>";

								?>
							</select>
							ou
							<input type="button" name="ENTIDADE" class="input3" value="Criar Curso" onClick="window.open('curso_operacao.php?OPCAO=InserirCurso&PAGINA=fechar','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes')">
						</td>																																			
					<tr>
					<tr>
						
              <td>Nome da Disciplina*</td>
						<td><input type="text" name="DESC_DIS" size="50"></td>
					<tr>
					<tr>
						<td>Créditos da Disciplina</td>
						<td><input type="text" name="NRO_CRED_DIS" size="10"></td>
					<tr>
				</table>
			</td>
			<td>
				<input type="hidden" name="OPCAO" value="InserirDisciplina">
				<input type="submit" name="Submit" class="input3" value="Criar disciplina" >
			</td>
		</tr>
	</table>	
</form>

<br>

<?php
if ( $acesso == 1 )
	$rsConN = listaAcesso(15, "", "", "");
else
	$rsConN = listaAcesso(5, "", "", "");
?>

<form name="DISCIPLINA_ENVIO" action="disciplina_envio.php" method="post">
	<table width="80%" bordercolor="#CCCCCC" border="1">
		<tr>
			<td width="40"><b>Criar:</b></td>
			<td>	
				<table align="left" width="100%">
					<tr>
						
              <td width="30%">Curso*</td>
						<td>
								<select name="COD_CURSO" onChange="form.action='disciplina.php'; submit();">
									<option value='' selected> Selecione um curso: </option>
									<?php
									while ( $linhaN = mysql_fetch_array($rsConN) )
									{
										echo "<option value='" . $linhaN["COD_CURSO"] . "'";
										
										if ( $_REQUEST["COD_CURSO"] == strval($linhaN["COD_CURSO"]) )
											echo (" selected");
										
										echo ">" . $linhaN["DESC_CURSO_ORIGEM"] . " - " . $linhaN["ABREV_CURSO"] . "</option>";
									 }
									?>
								</select>								
						</td>
					</tr>	
					<tr>
						
              <td width="30%">Disciplina*</td>
						<td>
							<select name="COD_DIS">
								<option value='' selected> Selecione uma disciplina: </option>
								<?php
								if ( isset ($_REQUEST["COD_CURSO"]) )
								{
									if ( $acesso == 1 )
										$rsConN = listaAcesso(13, $_REQUEST["COD_CURSO"], "", "");// ' 2
									else
										$rsConN = listaAcesso(14, $_REQUEST["COD_CURSO"], "", "");// ' 5
	
									while ( $linhaN = mysql_fetch_array($rsConN) )
										echo "<option value='" . $linhaN["COD_DIS"] . "'>" . $linhaN["DESC_DIS"] . "</option>";
								 }
								?>
							</select>
						</td>																																			
					<tr>
					<tr>
						
              <td>Nome da Turma*</td>
						<td>
                <input type="text" name="NOME_TURMA" size="4" maxlength="1">
              </td>
					<tr>
					<tr>
						
              <td>Ano*</td>
						<td><input type="text" name="ANO_TURMA" size="10"></td>
					<tr>

					<tr>
						
              <td>Periodo</td>
						<td><input type="text" name="PERIODO_TURMA" size="10"></td>
					<tr>
					<tr>
						
              <td>N&uacute;mero de Vagas</td>
						<td><input type="text" name="NRO_VAGAS_TURMA" size="10"></td>
					<tr>
				</table>
			</td>
			<td>
				<input type="hidden" name="OPCAO" value="InserirTurma">
				<input type="submit" name="Submit" class="input3" value="Criar turma" >
			</td>
		</tr>
	</table>	
</form>

</center>

		<?php
		    if ( $_REQUEST["MOSTRAR"] == "nao" )
				exit();

			if ( $acesso == 1 )
				$rsConN = listaAcesso(13, "", "", "");
			else
				$rsConN = listaAcesso(14, "", "", "");
		?>

		<table cellpadding="0" cellspacing="0" border="0" width="80%" align="center">	
			<tr>
				<td align="left"> <b> Excluir - Alterar </b> </td>
			</tr>
		</table> <br>
		<?php

			$origem  = "";
			$origem2 = "";
			$origem3 = "";
			$origem4 = "";			
			$mudou   = true;
			$mudou2  = true;
			$mudou3  = true;			
			$mudou4  = true;						
		
		while ( $linhaN = mysql_fetch_array($rsConN) )
		{
			
			$mudou = false;
			
			if ( $origem != $linhaN["COD_CURSO_ORIGEM"] )
			{
				$origem = $linhaN["COD_CURSO_ORIGEM"];
				$mudou  = true;
			 }
			
			if ( $mudou )
			{
				echo  "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"80%\" align=\"center\">".
					  "<tr>".
					  "	<td align=\"right\" width=\"65\">".
					  "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir " . $linhaN["DESC_CURSO_ORIGEM"] ."? Todos cursos, disciplinas e turmas desta entidade serão apagados.')) { window.open('curso_envio.php?OPCAO=RemoverOrigem&COD_CURSO_ORIGEM=" . $linhaN["COD_CURSO_ORIGEM"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
					  "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
					  "		</a>&nbsp;&nbsp;&nbsp;".
					  "		<a href=\"#\" onClick=\"window.open('curso_operacao.php?OPCAO=AlterarOrigem&COD_CURSO_ORIGEM=" . $linhaN["COD_CURSO_ORIGEM"] . "','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') \">".
					  "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
					  "		</a>".
					  "	</td>".
					  "	<td valign=\"middle\" align=\"left\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
					  $linhaN["DESC_CURSO_ORIGEM"].
					  "	</td>".
					  "</tr> </table><br>";
			 }
			
			$mudou2 = false;
			
			if ( $linhaN["COD_CURSO"] != NULL ) // not isNull(rsConN("COD_CURSO")) then
			{				
				if ( $origem2 != $linhaN["COD_CURSO"] )
				{
					$origem2 = $linhaN["COD_CURSO"];
					$mudou2  = true;
				 }
			 }
						
			if ( $mudou2 )
			{
				echo  "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"80%\" align=\"center\">".
					  "<tr>".
					  "	<td align=\"right\" width=\"135\">".
					  "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir " . $linhaN["DESC_CURSO"] ."? Todas disciplinas e turmas deste curso também serão apagadas.')) { window.open('curso_envio.php?OPCAO=RemoverCurso&COD_CURSO=" . $linhaN["COD_CURSO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
					  "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
					  "		</a>&nbsp;&nbsp;&nbsp;".
					  "		<a href=\"#\" onClick=\"window.open('curso_operacao.php?OPCAO=AlterarCurso&COD_CURSO=" . $linhaN["COD_CURSO"] . "','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') \">".
					  "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
					  "		</a>".
					  "	</td>".
					  "	<td valign=\"middle\" align=\"left\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
					  $linhaN["ABREV_CURSO"] . " - " . $linhaN["DESC_CURSO"] . "&nbsp;&nbsp;</td>".
					  "</tr> </table><br>";
			 }

			$mudou3 = false;
			
			if ( $linhaN != NULL )//not isNull(rsConN("COD_DIS")) then
				if ( $origem3 != $linhaN["COD_DIS"] )
				{
					$origem3 = $linhaN["COD_DIS"];
					$mudou3  = true;
				 }
						
			if ( $mudou3 )
			{
				echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"80%\" align=\"center\">".
					 "<tr>".
					 "	<td align=\"right\" width=\"205\">".
					 "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir " . $linhaN["DESC_DIS"] ."? Todas turmas desta disciplina também serão apagadas.')) { window.open('disciplina_envio.php?OPCAO=RemoverDisciplina&COD_DIS=" . $linhaN["COD_DIS"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
					 "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
					 "		</a>&nbsp;&nbsp;&nbsp;".
					 "		<a href=\"#\" onClick=\"window.open('disciplina_operacao.php?OPCAO=alterarDisciplina&COD_DIS=" . $linhaN["COD_DIS"] . "','Wlocal','top=106,left=120,width=740px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') \">".
					 "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
					 "		</a>".
					 "	</td>".
					 "	<td valign=\"middle\" align=\"left\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
					 $linhaN["DESC_DIS"].
					 " </td> </tr> </table><br>";
			 }

			$mudou4 = false;
			
			if ( $linhaN["COD_TURMA"] != NULL ) //not isNull(rsConN("COD_TURMA")) then
				if ( $origem4 != $linhaN["COD_TURMA"] )
				{
					$origem4 = $linhaN["COD_TURMA"];
					$mudou4  = true;
				 }
						
			if ( $mudou4 )
			{
				echo  "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"80%\" align=\"center\">".
					  "<tr>".
					  "	<td align=\"right\" width=\"275\">".
					  "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir a Turma " . $linhaN["NOME_TURMA"] ." da disciplina " . $linhaN["DESC_DIS"] ."?')) { window.open('disciplina_envio.php?OPCAO=RemoverTurma&COD_TURMA=" . $linhaN["COD_TURMA"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
					  "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
					  "		</a>&nbsp;&nbsp;&nbsp;".
					  "		<a href=\"#\" onClick=\"window.open('disciplina_operacao.php?OPCAO=alterarTurma&COD_TURMA=" . $linhaN["COD_TURMA"] . "','Wlocal','top=106,left=120,width=740px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') \">".
					  "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
					  "		</a>".
					  "	</td>".
					  "	<td valign=\"middle\" align=\"left\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
					  "Turma " . $linhaN["NOME_TURMA"].
					  "</td> </tr> </table><br>";
			 }
		 }
		
		?>

<br><br><br><br>

</body>
</html>
