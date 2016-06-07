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
include_once("../config.php");
include_once($caminhoBiblioteca."/acervo.inc.php");
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>Ferramentas de Administrador - Acervo</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) )	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	}
	
  /*
	if (isset($_REQUEST["COD_ARQUIVO"]) )
		if ( ! ApoioVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		{
			echo "<tr><td><br><b>Acesso negado a este arquivo. </b><br></td></tr>";
			exit();
		 }
		 */
?>

<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align=left width="60%">		
		<?php
		if (isset($_REQUEST["ACERVO_ENVIO"]))
		{
			if ( $_REQUEST["ACERVO_ENVIO"] == "alterar" )
				echo " <br><b>Arquivo Alterado com Sucesso</b>";		
			else
				if ( $_REQUEST["ACERVO_ENVIO"] == "inserir" )
					echo " <br><b>Arquivo Inserido com Sucesso</b>";
		 }		
		else
		{
			echo "<br><b> Acervo - " . $_REQUEST["OPCAO"] . "</b>";
			
			if ( $_REQUEST["OPCAO"] == "Alterar" )
			{
				echo "<br><br>" . "\n";
				echo "<table><tr><td>" . "\n";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?')) { window.open('acervo_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=acervo&COD_ARQUIVO=" . $_REQUEST["COD_ARQUIVO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
				echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
				echo "<td><font color='red'>";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?')) { window.open('acervo_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=acervo&COD_ARQUIVO=" . $_REQUEST["COD_ARQUIVO"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "Excluir este arquivo</a></font>" . "\n";
				echo "</td></tr></table>";
			 }
		 }
		?>
		</td>
		<td align=right>
		<? if($_REQUEST['PAGINA']=='instancia'){
			echo "<a href=\"".$url."/biblioteca/interno.php\">Voltar para Acervo</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Gerência</a>";

		}	?>	</td>
	</tr>

	<?php
	if ( !isset($_REQUEST["COD_ARQUIVO"]) )
		$_REQUEST["COD_ARQUIVO"] = "";
	
	if ( $_REQUEST["OPCAO"] == "Inserir" )
	{
		$DESC_ARQUIVO_TURMA     = "";
		$DESC_ARQUIVO           = "";
		$CAMINHO_LOCAL_ARQUIVO  = "";
		$COD_TIPO_ITEM_BIB 		= "";
		$TIPO_ARQUIVO      		= "";
		
		$COD_ARQUIVO       		= "";		
	 }

if ( ($_REQUEST["OPCAO"] != "Inserir")  AND ($_REQUEST["COD_ARQUIVO"] != "") )
{
		
		$rsCon = listaAcervo($_REQUEST["COD_ARQUIVO"], "", "" );
		
		if (  (!$rsCon)or (mysql_num_rows($rsCon)== 0) )	{  
			echo "<tr> <td> Arquivo não encontrado </td></tr></table>";
			exit();
		}

  	if($rsCon)	{
  	  $linha = mysql_fetch_array($rsCon) ;
			
			$DESC_ARQUIVO= str_replace("<br>", "\n", $linha["DESC_ARQUIVO"]);
	    $DESC_ARQUIVO = str_replace("\"", "&quot;", $DESC_ARQUIVO);
			
			//se o caminho for guardado com \ troca para /
			$CAMINHO_LOCAL_ARQUIVO = str_replace("\\", "/", $linha["CAMINHO_LOCAL_ARQUIVO"]);			
			
			$TIPO_ARQUIVO	= $linha["TIPO_ARQUIVO"];
			$COD_ARQUIVO	= $linha["COD_ARQUIVO"];
		
	    $temp = explode ("/", $CAMINHO_LOCAL_ARQUIVO); 
	    $nome = $temp[count($temp)-1];	

			if ($linha["TAMANHO_ARQUIVO"] != "" ) {
				$TAMANHO = $linha["TAMANHO_ARQUIVO"];
			}
			else {
				$TAMANHO = 0;
			}
	
	}
}	 
?>

			<div id="cur"></div><br>
			
			
		
	
			<form name="form1" method="post" enctype="multipart/form-data" action="acervo_envio.php?OPCAO=<?=$_REQUEST["OPCAO"];?>">
			  <tr>
			  	<td align=left width="60%">
				
					<br>
	  				<p>Descrição do Arquivo <br>
    				<input type="text" name="DESC_ARQUIVO" value="<?=$DESC_ARQUIVO ;?>" style="width:95%;" <?php if ( $_REQUEST["OPCAO"] == "Remover") echo "ReadOnly"; ?>>
					</p>
					<?php
					  if (( $_REQUEST["OPCAO"] == "Inserir" ) and ($_REQUEST["OPCAO"] != "Remover"))
					  {
					 ?>
					<p><b>*Escolha uma das opções para o endereço:</b><br> 
	  				<p>Caminho Local do Arquivo <br>
          			<input type="file" name="ARQUIVO_NOVO" value="<?=$CAMINHO_LOCAL_ARQUIVO; ?>"  style="width:95%;">
					</p>
				

					<p> Endereço do link para arquivo: <br> <input type="text" name="LINK_NOVO" value="http://" style="width:95%;"> </p>
        			
					<?php
					  }
					 if($_REQUEST["OPCAO"] != "Inserir")
					  { 
					  
					?>
					<p>Nome do Arquivo <br>
          			<input type="text" name="NOVO_NOME" value="<?=$nome;?>" style="width:95%;" <?php if ( $_REQUEST["OPCAO"] == "Remover") echo "ReadOnly"; ?> disabled>
 					<input type="hidden" name="CAM_LOCAL" value="<?=$CAMINHO_LOCAL_ARQUIVO;?>" ></p>

				
					
					<?php
	                    				
						}
					?>		
					<p>Tipo de Arquivo <br>
						<select name="TIPO_ARQUIVO" value="<?=$TIPO_ARQUIVO;?>" style="width:95%;" <?php if ( $_REQUEST["OPCAO"] == "Remover") echo "ReadOnly"; ?>>
							<?php
								if ( $TIPO_ARQUIVO == "text/html" )
									echo "<option value=\"text/html\" selected>HTML</option>";
								else
									echo "<option value=\"text/html\">HTML</option>";
								
								if ( $TIPO_ARQUIVO == "application/pdf" )
									echo "<option value=\"application/pdf\" selected>PDF</option>";
								else
									echo "<option value=\"application/pdf\">PDF</option>";
								
								if ( $TIPO_ARQUIVO == "text/plain" )
									echo "<option value=\"text/plain\" selected>Texto</option>";
								else
									echo "<option value=\"text/plain\">Texto</option>";
								
								if ( $TIPO_ARQUIVO == "application/msword" )
									echo "<option value=\"application/msword\" selected>Documento Word</option>";
								else
									echo "<option value=\"application/msword\">Documento Word</option>";
								
								if ( $TIPO_ARQUIVO == "application/vnd.ms-powerpoint" )
									echo "<option value=\"application/vnd.ms-powerpoint\" selected>Apresentação em Power Point</option>";
								else
									echo "<option value=\"application/vnd.ms-powerpoint\">Apresentação em Power Point</option>";
               
																
								if ( ($TIPO_ARQUIVO != "application/msword") AND ($TIPO_ARQUIVO != "text/html") AND ($TIPO_ARQUIVO != "application/pdf") AND ($TIPO_ARQUIVO != "text/plain") AND ($TIPO_ARQUIVO != "application/vnd.ms-powerpoint")  AND ( $_REQUEST["OPCAO"] != "Inserir") )
									echo "<option value=\" . $TIPO_ARQUIVO . \" selected>" . $TIPO_ARQUIVO . "</option>";
							?>				
						</select>
					</p>
	  		
			
					<p>&nbsp; </p>
						<?php
							if (( $_REQUEST["OPCAO"] != "Inserir" ) and ($_REQUEST["OPCAO"] != "Remover"))
							{ 
						?>
							<p>Novo Arquivo <br>
								<input type="file" name="ARQUIVO_NOVO" style="width:95%;">
		 					</p>
						<?php
		 					/* perguntar sobre local de ambos servidores*/
								if (!file_exists($caminhoUpload.$CAMINHO_LOCAL_ARQUIVO))
									echo "<p><font color=red>AVISO: Este arquivo não existe mais.</font></p>";
					
							 }
							 
						?>
						
	               <p>&nbsp; </p>
			        </td>
			        
        			</td>
						<?php
							if ( $_REQUEST["OPCAO"] != "Inserir" )
							{
							?>
								<td width="40%" valign="top">
									Localização:
									<br><br>
				
									<iframe name='locais' src='acervo_local.php?PAGINA=<?=$_REQUEST['PAGINA'];?>&COD_ARQUIVO=<?=$COD_ARQUIVO;?>' frameborder=0 style='position:absolute; width:300px; height:500px; z-index: 3; overflow: visible; visibility: visible'>
									</iframe>
								</td>
							<?php
							 }
							 if($_REQUEST["OPCAO"]!="Inserir") 
							 echo "<tr><td>	<p> Endereço do link para arquivo: <br> <input type=\"text\" name=\"LINK_NOVO\" value=\"http://\" style=\"width:95%;\"> </p></td></tr>";
									?>
				</tr>	
        			
				<tr>
					<td colspan=2 align="center">
					    <input type="hidden" name="COD_ARQUIVO" value="<?=$COD_ARQUIVO;?>">
						<input type="submit" name="Submit" value="<?php if ( $_REQUEST["OPCAO"] == "Remover" ) echo "Excluir"; else echo "Enviar";?>">
						<?if($_REQUEST['PAGINA']=='instancia'){?>
						<input type="reset" value="Cancelar" onClick="window.location.href = '<?=$url?>/biblioteca/interno.php';" name="reset">
						<?}else{?>
						<input type="reset" value="Cancelar" onClick="window.location.href = 'acervo.php';" name="reset"><?}?>
						<input type="hidden" name="PAGINA" value="<?=$_REQUEST['PAGINA'];?>">

                        
                        <?php
							if ( ($_REQUEST["OPCAO"] != "Inserir") and ($_REQUEST["OPCAO"] != "Remover") )
								echo "<br><br><br> <div align=left>* Caso seja escolhido um novo arquivo o antigo será apagado. </div>";
						?>
                     </td>
					<td>&nbsp;</td>
				</tr>	
		</form>
</table>
</body>
</html>
