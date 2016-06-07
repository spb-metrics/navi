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
include_once($caminhoBiblioteca."/enquete.inc.php");
session_name(SESSION_NAME); session_start(); security();

if (! isset($linhaN["TEXTO_ENQUETE"]) )
		$linhaN["TEXTO_ENQUETE"] = "";
if (! isset($_REQUEST["TEXTO_ENQUETE"]) )
		$_REQUEST["TEXTO_ENQUETE"] = "";

?>

<html>
	<head>
		<title>Ferramentas de Administrador - Enquete</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		<script language="JavaScript">
			function open1(src) 
			{
			    props = "top=106,left=120,width=756px,height=450px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes";
				page = window.open(src,"Wlocal",props);
				page.focus();
			}
		</script>
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
		exit();
	 }
	
	//CRIAR UMA FUNCAO PARA VERIFICAR ACESSO
	/*
	if (isset($_REQUEST["COD_ENQUETE"]) )
		if ( !EnqueteVerificaAcesso($_REQUEST["COD_ENQUETE"]) )
		{
			echo "<tr><td><br><b>Acesso negado a esta enquete. </b><br></td></tr>";
			exit();
		 }
		 */
?>

<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align=left>		
		<?php
		if (isset($_REQUEST["ENQUETE_ENVIO"]))
		{
			if ( $_REQUEST["ENQUETE_ENVIO"] == "alterar" )
				echo " <br><b>Enquete Alterada com Sucesso</b>";		
			else
				if ( $_REQUEST["ENQUETE_ENVIO"] == "inserir" )
					echo " <br><b>Enquete Inserida com Sucesso</b>";
		 }		
		else
		{
			echo "<br><b> ENQUETE - " . $_REQUEST["OPCAO"] . "</b>";
			
			if ( $_REQUEST["OPCAO"] == "Alterar" )
			{
				echo "<br><br>" . "\n";
				echo "<table><tr><td>" . "\n";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir esta enquete ?')) { window.open('enquete_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=enquete&COD_ENQUETE=" . $_REQUEST["COD_ENQUETE"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
				echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
				echo "<td><font color='red'>";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir esta enquete ?')) { window.open('enquete_envio.php?PAGINA2=".$_REQUEST['PAGINA']."&OPCAO=Remover&PAGINA=enquete&COD_ENQUETE=" . $_REQUEST["COD_ENQUETE"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "Excluir esta enquete</a></font>" . "\n";
				echo "</td></tr></table>";
			 }
		 }
		?>
		</td>
		<td align=right>
		<? if($_REQUEST['PAGINA']=='instancia'){
			echo "<a href=\"".$url."/interacao/enquete/index.php\">Voltar para Enquete</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Gerência</a>";

		}	?>	</td>
	</tr>

	<?php
	if ( !isset($_REQUEST["COD_ENQUETE"]) )
		$_REQUEST["COD_ENQUETE"] = "";
	
	$rsConN = listaEnquete($_REQUEST["COD_ENQUETE"],"","","");
	
	if ( $_REQUEST["OPCAO"] == "Inserir" )
	{
		$cod_enquete = "";
		$texto_enquete = "";
		//$enquete_resposta  = "";
	 }
	
	if ( ($_REQUEST["OPCAO"] != "Inserir") AND ( $_REQUEST["COD_ENQUETE"] != "" ) )
	{
	
	
		
		$rsConN = listaEnquete($_REQUEST["COD_ENQUETE"],"","");

		if ( (! $rsConN) or (mysql_num_rows($rsConN) == 0) )
		{
			echo "<tr> <td> Enquete não encontrada </td></tr></table>";
			exit();
		}
	
		//$enquete_resposta  = "";
		$linhaN = mysql_fetch_array($rsConN);
	if (! isset($linhaN["TEXTO_ENQUETE"]) )
		$linhaN["TEXTO_ENQUETE"] = "";
	if (! isset($_REQUEST["TEXTO_ENQUETE"]) )
		$_REQUEST["TEXTO_ENQUETE"] = "";
		$texto_enquete = str_replace("<br>", "\n", $linhaN["TEXTO_ENQUETE"]);
		
		$texto_enquete = strip_tags($linhaN["TEXTO_ENQUETE"]);
		
//		$resumo = str_replace("<br>", "\n", $linhaN["RESUMO_NOTICIA"]);
//    	$resumo = str_replace("\"", "&quot", $linhaN["RESUMO_NOTICIA"]);// lembrar de pequisar o porque nao funciona?!!!
				
//		$texto  = str_replace("<br>","\n" , $linhaN["TEXTO_NOTICIA"]);
//		$texto  = str_replace("\"", "&quot;", $linhaN["TEXTO_NOTICIA"]);
		
		$cod_enquete = $linhaN["COD_ENQUETE"];
		
		
	 }

	?>


	<form name="form1" method="post" action="enquete_envio.php?OPCAO=<?=$_REQUEST["OPCAO"]?>">
		<tr>
			
      <td align=center width="60%"> 
        <?php
				if ( !isset($_REQUEST["TEXTO_ENQUETE"]) )
					$_REQUEST["TEXTO_ENQUETE"] = "";
	
			$rsCon  = RecebeTextoEnquete($_REQUEST["COD_ENQUETE"],$_REQUEST["TEXTO_ENQUETE"]);
			if($rsCon)
			{
				$linha= mysql_fetch_array($rsCon);
				//$texto_enquete = str_replace("<br>", "\n", $linha["COD_ENQUETE"]);
				//$texto_enquete = str_replace("\"", "&quot;", $linha["COD_ENQUETE"]);
				$texto_enquete= strip_tags($linha["TEXTO_ENQUETE"]);
			}
			
		?>
        <p>Pergunta da enquete(m&aacute;ximo 400 caracteres): <br> 
          <script language="JavaScript">
						function CountLen(obj) 
						{
							if(obj.value.length > 400) {
								alert("Número máximo de caracteres excedido em " + (obj.value.length -400) + " caracteres.");
								obj.value = obj.value.slice(0,400);
							}
						}
					</script>
         
		  <textarea name="TEXTO_ENQUETE" cols="80" rows="3" onBlur="javascript:CountLen(this)" <?php if ($_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>><?=$texto_enquete?></textarea>
          <BR>
          
          <?php
		  	if ( !isset($texto_resposta) )
				$texto_resposta= "";
			if ( $_REQUEST["OPCAO"] != "Inserir" )
			{	
				//listar todas as respostas
				
				$rsCon  = RecebeRespostaEnqute($_REQUEST["COD_ENQUETE"]);
			
				if ($rsCon)
				{
					if ($linha= mysql_fetch_array($rsCon))
					{
						while ($linha) 
						{	
							
							if ( !isset($linha["TEXTO_RESPOSTA"]) )
								$linha["TEXTO_RESPOSTA"] = "";
							if ( !isset($linha["COD_RESPOSTA"]) )
								$linha["COD_RESPOSTA"] = "";
							echo  $linha["COD_RESPOSTA"] . "-";
							$cod_resposta = $linha["COD_RESPOSTA"];
							$texto_resposta = strip_tags($linha["TEXTO_RESPOSTA"]);					
							
    						
				
				?>
               
        
		  <textarea name="TEXTO_RESPOSTA[]"  cols="70" rows="2" onBlur="javascript:CountLen(this)" <?php if($_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>><?=$texto_resposta?></textarea><br>
		  
		  
		 <input type="hidden" name="COD_RESPOSTA[]" value="<?=$linha["COD_RESPOSTA"];?>">
		  
		  <?php					
						$linha = mysql_fetch_array($rsCon);
						
						}
					}
									
				}
					
			
		?>
		
		<input type="hidden" name="NUM_RESPOSTA" value="<?=$result;?>">
		<a href='enquete_resposta.php?OPCAO=Inserir&COD_ENQUETE=<?=$_REQUEST["COD_ENQUETE"]?>'>Inserir 
          Alternativas </a> <br>
          <?php
		  }
			if ( $_REQUEST["OPCAO"] != "Inserir" )
			{
			?>
      <td width="40%" valign="top">
					Localização:
					<br><br>

					<iframe name="locais" src="enquete_local.php?PAGINA=<?=$_REQUEST['PAGINA'];?>&COD_ENQUETE=<?=$cod_enquete?>" frameborder=0 style="position:absolute; width:250px; height:500px; z-index: 3; overflow: visible; visibility: visible">
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
						<input type="reset" value="Cancelar" onClick="window.location.href = '<?=$url?>/interacao/enquete/index.php';" name="reset">
						<?}else{?>
						<input type="reset" value="Cancelar" onClick="window.location.href = 'avaliacao.php';" name="reset"><?}?>
				<input type="hidden" name="COD_ENQUETE" value="<?=$cod_enquete;?>">
				<input type="hidden" name="PAGINA" value="<?=$_REQUEST['PAGINA'];?>">
		</td>
			<td>&nbsp;
				
			</td>
		</tr>	
		<tr>
		<td>
		<p ><? echo "<b><font color='red' align='center'><ul> Como proceder para Colocar uma enquete</ul></font></b>".
				   "Escreva sua pergunta e após clique em <b>inserir alternativas</b><br>".
				   "Vai aparecer uma caixa de diálogo, nela você poderá escrever uma alternativa<br>".
				   "Clique em inserir,<br>".
				   "para colocar mais alternativas clique novamente em <b>inserir alternativas</b><br> ".
				   "<ul>Ex: Qual a sua cor preferida?</ul>".
				   "<ul><li>Verde</li></ul>".
				   "<ul><li>Amarelo</li></ul>".
				   "<ul><li>Azul</li></ul>".
				   "Após, Clique em <b>incluir em um novo local,</b><br>".
				   "escolha o tipo de acesso e clique em enviar";
			?></p>
		</td>
		</tr>
	</form>
</table>

</body>

</html>
