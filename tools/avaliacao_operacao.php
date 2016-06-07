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
include_once($caminhoBiblioteca."/avaliacao.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Avalia��o</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>P�gina Principal</a></p>";
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
			echo "<br><b> Avalia��o - " . $_REQUEST["OPCAO"] . "</b>";
			
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
			echo "<a href=\"".$url."/avaliacao/index.php\">Voltar para Avalia��o</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Ger�ncia</a>";

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
			echo "<tr> <td> Arquivo n�o encontrado </td></tr></table>";
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

				
				<p><b> Descri��o do Arquivo: </b><br>
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
				echo "<p><b>*Escolha somente uma das op��es para o endere�o:</b><br>";			
				echo "<p><b> Endere�o do Arquivo: </b><br> <input type=\"file\" name=\"ARQUIVO_NOVO\" size=\"60\"> </p>";
				echo "<p><b> Endere�o do link para arquivo: </b><br> <input type=\"text\" name=\"LINK_NOVO\" value=\"http://\" size=\"60\"> </p>";
			 }
				
			?>		    		

			</td>

			<?php
			if ( $_REQUEST["OPCAO"] != "Inserir" )
			{
			?>
				<td width="40%" valign="top">
					Localiza��o :
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
					echo "<br><br><br> <div align=left>* Caso seja escolhido um novo arquivo o antigo ser� apagado. </div>";
				?>

			</td>
			<td>&nbsp;
				
			</td>
		</tr>	
	</form>
</table>

</body>

</html>
