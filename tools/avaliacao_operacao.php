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
include_once($caminhoBiblioteca."/avaliacao.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Avaliação</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }
		
	if (isset($_REQUEST["COD_ARQUIVO"]) )
		if ( ! AvaliacaoVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		{
			echo "<tr><td><br><b>Acesso negado a este arquivo. </b><br></td></tr>";
			exit();
		 }
?>

<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align=left width="60%">		
		<?php
		if (isset($_REQUEST["AVALIACAO_ENVIO"]))
		{
			if ( $_REQUEST["AVALIACAO_ENVIO"] == "alterar" )
				echo " <br><b>Arquivo Alterado com Sucesso</b>";		
			else
				if ( $_REQUEST["AVALIACAO_ENVIO"] == "inserir" )
					echo " <br><b>Arquivo Inserido com Sucesso</b>";
		 }		
		else
		{
			echo "<br><b> Avaliação - " . $_REQUEST["OPCAO"] . "</b>";
			
			if ( $_REQUEST["OPCAO"] == "Alterar" )
			{
				echo "<br><br>" . "\n";
				echo "<table><tr><td>" . "\n";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?')) { window.open('avaliacao_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=avaliacao&COD_ARQUIVO=" . $_REQUEST["COD_ARQUIVO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
				echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
				echo "<td><font color='red'>";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?')) { window.open('avaliacao_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=avaliacao&COD_ARQUIVO=" . $_REQUEST["COD_ARQUIVO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "Excluir este arquivo</a></font>" . "\n";
				echo "</td></tr></table>";
			 }
		 }
		?>
		</td>
		<td align=right>
		<? if($_REQUEST['PAGINA']=='instancia'){
			echo "<a href=\"".$url."/avaliacao/index.php\">Voltar para Avaliação</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Gerência</a>";

		}
		?>		</td>
	</tr>

	<?php
	if ( !isset($_REQUEST["COD_ARQUIVO"]) )
		$_REQUEST["COD_ARQUIVO"] = "";
	
	if ( $_REQUEST["OPCAO"] == "Inserir" )
	{
		$desc_arquivo = "";
		$arquivo	  = "";		
	 }
	
	if ( ($_REQUEST["OPCAO"] != "Inserir") AND ( $_REQUEST["COD_ARQUIVO"] != "" ) )
	{
		$rsConN = listaAvaliacao($_REQUEST["COD_ARQUIVO"],"","");
	
		if ( (! $rsConN) or (mysql_num_rows($rsConN) == 0) )
		{
			echo "<tr> <td> Arquivo não encontrado </td></tr></table>";
			exit();
		}
			
		$linhaN = mysql_fetch_array($rsConN);
		
		$cam_local = $linhaN["CAMINHO_LOCAL_ARQUIVO"];
		
		$desc_arquivo = str_replace("<br>", "\n", $linhaN["DESC_ARQUIVO"]);
		$desc_arquivo = str_replace("\"", "&quot;", $linhaN["DESC_ARQUIVO"]);
		
		$temp = explode ("/", $linhaN["CAMINHO_LOCAL_ARQUIVO"]); 
		$nome = $temp[count($temp)-1];		

		$tamanho = str_replace("<br>", "\n", $linhaN["TAMANHO_ARQUIVO"]);
		$tamanho = str_replace("\"", "&quot;", $linhaN["TAMANHO_ARQUIVO"]);

		$tipo = str_replace("<br>", "\n", $linhaN["TIPO_ARQUIVO"]);
		$tipo = str_replace("\"", "&quot;", $linhaN["TIPO_ARQUIVO"]);

		$arquivo = $linhaN["COD_ARQUIVO"];
	 }

	?>

	<form name="form1" method="post" enctype="multipart/form-data" action="avaliacao_envio.php?OPCAO=<?=$_REQUEST["OPCAO"]?>">
		<tr>
			<td align=left width="60%">

				
				<p><b> Descrição do Arquivo: </b><br>
			    	<input type="text" name="DESC_ARQUIVO" value="<?=$desc_arquivo?>" size="80" <?php if ($_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>>
        		</p>
				
			<?
			if ( ($_REQUEST["OPCAO"] != "Inserir") )
			{
			?>				
				<p> <b> Nome do Arquivo: </b> <br>
					<input type="text" size="80" name="NOVO_NOME" value="<?=$nome?>" disabled>
				<input type="hidden" name="CAM_LOCAL" value="<?=$cam_local?>">
				</p>			
				
				<p> <b> Tamanho do Arquivo: </b> <?=$tamanho?>
				<input type="hidden" name="TAMANHO" value="<?=$tamanho?>">
				</p>
				<p> <b> Tipo do Arquivo: </b> <?=$tipo?>
				<input type="hidden" name="TIPO" value="<?=$tipo?>">
				</p>
			<?
			}
			if ( ($_REQUEST["OPCAO"] != "Inserir") and ($_REQUEST["OPCAO"] != "Remover") )
				echo "<p><b>* Novo Arquivo: </b><br> <input type=\"file\" name=\"ARQUIVO_NOVO\" size=\"60\"> </p>";
				
			if ( ($_REQUEST["OPCAO"] == "Inserir") and ($_REQUEST["OPCAO"] != "Remover") )
			{
				echo "<p><b>*Escolha somente uma das opções para o endereço:</b><br>";			
				echo "<p><b> Endereço do Arquivo: </b><br> <input type=\"file\" name=\"ARQUIVO_NOVO\" size=\"60\"> </p>";
				echo "<p><b> Endereço do link para arquivo: </b><br> <input type=\"text\" name=\"LINK_NOVO\" value=\"http://\" size=\"60\"> </p>";
			 }
				
			?>		    		

			</td>

			<?php
			if ( $_REQUEST["OPCAO"] != "Inserir" )
			{
			?>
				<td width="40%" valign="top">
					Localização :
					<br><br>

					<iframe name="locais" src="avaliacao_local.php?PAGINA=<?=$_REQUEST['PAGINA'];?>&COD_ARQUIVO=<?=$arquivo?>" frameborder=0 style="position:absolute; width:300px; height:500px; z-index: 3; overflow: visible; visibility: visible">
					</iframe>
				</td>
			<?php
			 }
			?>
		</tr>

		<tr>
			<td align="center">
				<input type="submit" name="Submit" value="<? if ( $_REQUEST["OPCAO"]=="Remover") echo "Excluir"; else echo "Enviar";?>">
				<?if($_REQUEST['PAGINA']=='instancia'){?>
						<input type="reset" value="Cancelar" onClick="window.location.href = '<?=$url?>/avaliacao/index.php';" name="reset">
						<?}else{?>
						<input type="reset" value="Cancelar" onClick="window.location.href = 'avaliacao.php';" name="reset"><?}?>
		        <input type="hidden" name="COD_ARQUIVO" value="<?=$arquivo;?>">
				<input type="hidden" name="PAGINA" value="<?=$_REQUEST['PAGINA'];?>">
				
				<?
				if ( ($_REQUEST["OPCAO"] != "Inserir") and ($_REQUEST["OPCAO"] != "Remover") )
					echo "<br><br><br> <div align=left>* Caso seja escolhido um novo arquivo o antigo será apagado. </div>";
				?>

			</td>
			<td>&nbsp;
				
			</td>
		</tr>	
	</form>
</table>

</body>

</html>
