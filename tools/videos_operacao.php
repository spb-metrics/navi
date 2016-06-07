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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Videos</title>
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
	
	if (isset($_REQUEST["COD_VIDEO"]) )
		if ( ! VideoVerificaAcesso($_REQUEST["COD_VIDEO"]) )
		{
			echo "<tr><td><br><b>Acesso negado a este v�deo. </b><br></td></tr>";
			exit();
		 }
?>

<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align="left" width="60%">		
		<?php
		if (isset($_REQUEST["VIDEOS_ENVIO"]))
		{
			if ( $_REQUEST["VIDEOS_ENVIO"] == "alterar" )
				echo " <br><b>V�deo Alterado com Sucesso</b>";		
			else
				if ( $_REQUEST["VIDEOS_ENVIO"] == "inserir" )
					echo " <br><b>V�deo Inserido com Sucesso</b>";
		 }		
		else
		{
			echo "<br><b> V�deos - " . $_REQUEST["OPCAO"] . "</b>";
			
			if ( $_REQUEST["OPCAO"] == "Alterar" )
			{
				echo "<br><br>" . "\n";
				echo "<table><tr><td>" . "\n";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este v�deo ?')) { window.open('videos_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=videos&COD_VIDEO=" . $_REQUEST["COD_VIDEO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
				echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
				echo "<td><font color='red'>";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este v�deo ?')) { window.open('videos_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=videos&COD_VIDEO=" . $_REQUEST["COD_VIDEO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "Excluir este v�deo</a></font>" . "\n";
				echo "</td></tr></table>";
			 }
		 }
		?>
		</td>
		<td align="right">
 			<? if($_REQUEST['PAGINA']=='instancia'){
			echo "<a href=\"".$url."/aulas/index.php\">Voltar para v&iacute;deo-aulas</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Ger�ncia</a>";

		}
		?>
		</td>
	</tr>

	<?php
	if ( !isset($_REQUEST["COD_VIDEO"]) )
		$_REQUEST["COD_VIDEO"] = "";
		
	if ( $_REQUEST["OPCAO"] == "Inserir" )
	{
		$desc_video = "";
		//$cam_http   = "mms://camille.ea.ufrgs.br/eape/";
	 }

	if ( ($_REQUEST["OPCAO"] != "Inserir") AND ( $_REQUEST["COD_VIDEO"] != "" ) )
	{ 
		$rsConN = listaVideos($_REQUEST["COD_VIDEO"],"","");
	
		if ( (! $rsConN) or (mysql_num_rows($rsConN) == 0) )
		{
			echo "<tr> <td> V�deo n�o encontrado </td></tr></table>";
			exit();
		}
	
		$linhaN = mysql_fetch_array($rsConN);
		$desc_video = "";
		$cam_http   = "";
		
		$desc_video = str_replace("<br>", "\n", $linhaN["DESC_VIDEO"]);
		$desc_video = str_replace("\"", "&quot;", $linhaN["DESC_VIDEO"]);
		
		$cam_http = str_replace("<br>", "\n", $linhaN["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"]);
		$cam_http_discada = str_replace("\"", "&quot;", $linhaN["CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO"]);		

		$video = $linhaN["COD_VIDEO"];
	 }

	?>

	<form name="form1" method="post" action="videos_envio.php?OPCAO=<?=$_REQUEST["OPCAO"]?>">
		<tr>
			<td align="center" width="60%">

				
				<p>Descri��o do V�deo: <br>
			    	<input type="text" name="DESC_VIDEO" value="<?=$desc_video?>" size="80" <?php if ($_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>>
        </p>
				<p>Endere�o do Video (p/ banda larga): <br>
					<input type="text" name="CAM_HTTP" value="<?=$cam_http?>" size="80" <?php if ( $_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>>
				</p><br>
				<p>Endere�o do Video (p/ linha discada): <br>
					<input type="text" name="CAM_HTTP_DISCADA" value="<?=$cam_http_discada?>" size="80" <?php if ( $_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>>
				</p><br>
				
        <input type="checkbox" name="DOWNLOAD" value="1"   <?php if ($linhaN["DOWNLOAD"])echo "checked";?> >
Desejo disponibilizar esse video para Download<br>
				<br>
			</td>

			<?php
			if ( $_REQUEST["OPCAO"] != "Inserir" )
			{
			?>
				<td width="40%" valign="top">
					Localiza��o :
					<br><br>

					<iframe name="locais" src="videos_local.php?PAGINA=<?=$_REQUEST['PAGINA'];?>&COD_VIDEO=<?=$video?>" frameborder=0 style="position:absolute; width:300px; height:500px; z-index: 3; overflow: visible; visibility: visible">
					</iframe>
				</td>
        </tr>
        <tr>
        <td>
			<?php
			  
          }

			?>
      <td>
		</tr>

		<tr>
			<td align="center">
				<input type="submit" name="Submit" value="<? if ( $_REQUEST["OPCAO"]=="Remover") echo "Excluir"; else echo "Enviar";?>">

						<?if($_REQUEST['PAGINA']=='instancia'){?>
						<input type="reset" value="Cancelar" onClick="window.location.href = '<?=$url?>/aulas/index.php';" name="reset">
						<?}else{?>
						<input type="reset" value="Cancelar" onClick="window.location.href = 'videos.php';" name="reset"><?}?>
						<input type="hidden" name="COD_VIDEO" value="<?=$video;?>">
						<input type="hidden" name="PAGINA" value="<?=$_REQUEST['PAGINA'];?>">
		</td>
			<td>&nbsp;
				
			</td>
		</tr>	
	</form>
</table>

</body>

</html>
