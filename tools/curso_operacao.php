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

<?
//	Dim acesso, rsConC, rsConN
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
//	'Response.write acesso

	if ( $acesso != 1 )
	{
		echo "<p align='center'>Acesso Restrito. </p>";
		exit();
	 }
if ( !isset($_REQUEST["PAGINA"]) )
	$_REQUEST["PAGINA"] = "";
	
if ( !isset($_REQUEST["COD_CURSO_ORIGEM"]) )
	$_REQUEST["COD_CURSO_ORIGEM"] = "";
	
if ( !isset($_REQUEST["COD_CURSO"]) )
	$_REQUEST["COD_CURSO"] = "";

if ( $_REQUEST["PAGINA"] == "link" )
{

?>
<div align=center> <br><font size=4> <b> Criar Curso </b> </font> </div>

<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align="right">
			<a href="./../ferramentas.php" target="_parent">Ferramentas de Gerência</a>
			- <a href="javascript:history.back()">Voltar</a>
		</td>	
	</tr>
</table>
<?php
 }
else
{
?>
<br><br><br><br>
<?php
 }
?>

<center>

<?php
	switch ( $_REQUEST["OPCAO"] )
	{
		case ( "InserirCurso" ):
		
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
												{
													echo "<option value='" . $linhaN["COD_CURSO_ORIGEM"] . "'>" . $linhaN["DESC_CURSO_ORIGEM"] . "</option>";
//													rsConN.movenext
												 }
											?>
										</select>
										ou
										<?php
											if ( $_REQUEST["PAGINA"] == "fechar" )
											{
											?>
												<input type="button" name="ENTIDADE" class="input3" value="Criar Entidade" onClick="window.open('curso_operacao.php?OPCAO=InserirOrigem&PAGINA=voltar','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes')">
											<?php
											 }
											else
											{
											?>										
												<input type="button" name="ENTIDADE" class="input3" value="Criar Entidade" onClick="window.open('curso_operacao.php?OPCAO=InserirOrigem','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes')">											
											<?php
											 }
										?>
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
							<input type="hidden" name="OPCAO"  value="InserirCurso">
							<?php
							if ( $_REQUEST["PAGINA"] == "fechar" )
							{
							?>
								<input type="hidden" name="PAGINA" value="fechar">
							<?php
							 }
							?>
							<input type="submit" name="Submit" class="input3" value="Criar curso" >
						</td>
					</tr>
				</table>	
			</form>			
			<?php
//			rsConN.close
			break;
					
		case ( "InserirOrigem" ):
			?>
				<form action="curso_envio.php" method="post">
					<table width="80%" bordercolor="#CCCCCC" border="1">
						<tr>
							<td><b>Criar:</b></td>
							<td>	
								<table align="center">
									<tr>
										<td>Entidade</td>
										<td><input type="text" name="DESC_CURSO" size="70"></td>																																			
									<tr>
								</table>
							</td>
							<td>
								<input type="hidden" name="OPCAO" value="InserirOrigem">
								<?php
								if ( $_REQUEST["PAGINA"] == "voltar" )
								{
								?>
									<input type="hidden" name="PAGINA" value="voltar">
								<?php
								 }
								?>								
								<input type="submit" name="Submit" class="input3" value="Criar Entidade">
							</td>
						</tr>
					</table>	
				</form>				
			<?php
			break;
			
		case ( "AlterarOrigem" ):
				$rsConC = listaAcesso(12, "", $_REQUEST["COD_CURSO_ORIGEM"], "");
				$linhaC = mysql_fetch_array($rsConC);
			?>
				<form action="curso_envio.php" method="post">
					<table width="80%" bordercolor="#CCCCCC" border="1">
						<tr>
							<td><b>Alterar:</b></td>
							<td>	
								<table align="center">
									<tr>
										<td>Entidade: </td>
										<td><input type="text" name="DESC_CURSO" size="70" value="<?=$linhaC["DESC_CURSO_ORIGEM"];?>"></td>
									<tr>
								</table>
							</td>
							<td align="center">
								<input type="hidden" name="OPCAO" value="AlterarOrigem">
								<input type="hidden" name="COD_CURSO_ORIGEM" value="<?=$_REQUEST["COD_CURSO_ORIGEM"]?>" >
								<input type="submit" name="Submit" class="input3" value="Alterar Entidade">
							</td>
						</tr>
					</table>	
				</form>
			<?php
				break;
				
		case ( "AlterarCurso" ):
				$rsConC = listaAcesso(12, $_REQUEST["COD_CURSO"], "", "");
				$rsConN = listaAcesso(10, "", "", "");
				
				$linhaC = mysql_fetch_array($rsConC);
			?>
				<form action="curso_envio.php" method="post">
					<table width="80%" bordercolor="#CCCCCC" border="1">
						<tr>
							
        <td><b>Alterar:</b></td>
							<td>	
								<table align="center">
									<tr>
										<td>Entidade</td>
										<td><select name="COD_CURSO_ORIGEM">
												<option value='' selected> Selecione uma entidade: </option>
												<?php
												while ( $linhaN = mysql_fetch_array($rsConN) )
												{
													echo "<option value='" . $linhaN["COD_CURSO_ORIGEM"] . "'";
													
													if ( $linhaN["COD_CURSO_ORIGEM"] == $linhaC["COD_CURSO_ORIGEM"] )
														echo " selected ";
													
													echo ">" . $linhaN["DESC_CURSO_ORIGEM"] . "</option>";
												 }
												?>
											</select>
											ou
											<input type="button" name="ENTIDADE" class="input3" value="Criar Entidade" onClick="window.open('curso_operacao.php?OPCAO=InserirOrigem','Wlocal','top=106,left=120,width=756px,height=250px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes')">
										</td>																																			
									<tr>
									<tr>
										<td>Nome do curso</td>
										<td><input type="text" name="DESC_CURSO" size="70" value="<?=$linhaC["DESC_CURSO"];?>"></td>
									<tr>
									<tr>
										<td>Abreviação</td>
										<td><input type="text" name="ABREV_CURSO" size="10" value="<?=$linhaC["ABREV_CURSO"];?>"></td>
									<tr>
									<tr>
										<td>Inscrição aberta</td>
										<td>
											<select name="INSCRICAO_ABERTA">
												<?php
												if ( $linhaC["INSCRICAO_ABERTA"] == 1 )
												{
												?>
													<option value="0"> não </option>
													<option value="1" selected> sim </option>
												<?php
												 }
												else
												{
												?>
													<option value="0" selected> não </option>
													<option value="1"> sim </option>
												<?php												
												 }
												?>
											</select>
										</td>
									<tr>
								</table>
							</td>
							<td>
								<input type="hidden" name="OPCAO" value="AlterarCurso">
								<input type="hidden" name="COD_CURSO" value="<?=$_REQUEST["COD_CURSO"];?>" >								
								<input type="submit" name="Submit" class="input3" value="Alterar curso">
							</td>
						</tr>
					</table>	
				</form>
			<?php
//			rsConC.close
//			rsConN.close
			break;
			
	 }
			?>						

</center>

</body>
</html>