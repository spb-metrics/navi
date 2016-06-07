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
		<title>Cursos</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php
		$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( $acesso <> 1 )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }
?>

<div align=center> <br><font size=4> <b> Cursos </b> </font> </div>

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
	$rsConN = listaAcesso(10, "", "", "");
?>

<form action="curso_envio.php" method="post">
	<table width="80%" bordercolor="#CCCCCC" border="1">
		<tr>
			<td><b>Criar:</b></td>
			<td>	
				<table align="center">
					<tr>
						<td>Entidade</td>
						<td><select name="COD_CURSO_ORIGEM">
								<option value='' selected> Selecione uma entidade: </option>
								<?php
								if ( $rsConN )
									while ( $linhaN = mysql_fetch_array($rsConN) )
										echo "	<option value='" . $linhaN["COD_CURSO_ORIGEM"] . "'>" . $linhaN["DESC_CURSO_ORIGEM"] . "</option>";
								?>
							</select>
							ou
							<input type="button" name="ENTIDADE" class="input3" value="Criar Entidade" onClick="window.open('curso_operacao.php?OPCAO=InserirOrigem','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes')">
						</td>																																			
					<tr>
					<tr>
						<td>Nome do curso</td>
						<td><input type="text" name="DESC_CURSO" size="70"></td>
					<tr>
					<tr>
						<td>Abreviação</td>
						<td><input type="text" name="ABREV_CURSO" size="10"></td>
					<tr>
					<tr>
						<td>Inscrição aberta</td>
						<td>
							<select name="INSCRICAO_ABERTA">
								<option value="0" selected> não </option>
								<option value="1"> sim </option>
						 	</select>
						</td>
					<tr>
				</table>
			</td>
			<td>
				<input type="hidden" name="OPCAO" value="InserirCurso">
				<input type="submit" name="Submit" class="input3" value="Criar curso" >
			</td>
		</tr>
	</table>	
</form>

</center>

		<?php
			$rsConN = listaAcesso(11, "", "", "");
// ok!
		?>

		<table cellpadding="0" cellspacing="0" border="0" width="90%" align="center">	
			<tr>
				<td align="left">  <b> Excluir - Alterar </b> </td>
				<td align="right"> <b> Inscrição Aberta </b> </td>								
			</tr>
		</table> <br>

		<?php
			$origem = "";
			$mudou  = true;
		
		if ( $rsConN )
		while ( $linhaN = mysql_fetch_array($rsConN) )
		{
			
			$mudou = false;
			
			if ( $origem <> $linhaN["COD_CURSO_ORIGEM"] )
			{
				$origem = $linhaN["COD_CURSO_ORIGEM"];
				$mudou  = true;
			 }
			
			if ($mudou)
			{
				echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%\" align=\"center\">" .
					 "<tr>" .
					 "<td align=\"right\" width=\"65\">" .
					 "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir " . $linhaN["DESC_CURSO_ORIGEM"] ."? Todos cursos, disciplinas e turmas desta entidade serão apagados.')) { window.open('curso_envio.php?OPCAO=RemoverOrigem&COD_CURSO_ORIGEM=" . $linhaN["COD_CURSO_ORIGEM"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" .
					 "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">" .
					 "</a>&nbsp;&nbsp;&nbsp;" .
					 "<a href=\"#\" onClick=\"window.open('curso_operacao.php?OPCAO=AlterarOrigem&COD_CURSO_ORIGEM=" . $linhaN["COD_CURSO_ORIGEM"] . "','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') \">" .
					 "<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">" .
					 "</a>" .
					 "</td>" .
					 "<td valign=\"middle\" align=\"left\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $linhaN["DESC_CURSO_ORIGEM"] ." </td>" .
					 "</tr> </table><br>";
			 }
			
			if ( isset($linhaN["COD_CURSO"]) )
			{
				echo  "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%\" align=\"center\">" .
					  "<tr>" .
					  "<td align=\"right\" width=\"135\">" .											
				      "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir " . $linhaN["DESC_CURSO"] ."? Todas disciplinas e turmas deste curso também serão apagadas.')) { window.open('curso_envio.php?OPCAO=RemoverCurso&COD_CURSO=" . $linhaN["COD_CURSO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" .
					  "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">" .
					  "</a>&nbsp;&nbsp;&nbsp;" .
					  "<a href=\"#\" onClick=\"window.open('curso_operacao.php?OPCAO=AlterarCurso&COD_CURSO=" . $linhaN["COD_CURSO"] . "','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') \">" .
					  "<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">" .
					  "</a>" .
					  "</td>" .
					  "<td valign=\"middle\" align=\"left\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
					  $linhaN["ABREV_CURSO"] . " - " . $linhaN["DESC_CURSO"] . "&nbsp;&nbsp;</td>";
							if ( $linhaN["INSCRICAO_ABERTA"] == 0 )
								echo " <td valign=\"middle\" align=\"right\"> não &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
							else
								echo " <td valign=\"middle\" align=\"right\"> sim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
								
				echo "</tr> </table><br>";
			 }
		  }
		  ?>

<br><br><br><br>

</body>
</html>
