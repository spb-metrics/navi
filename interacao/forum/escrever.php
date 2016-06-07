<?php
//include_once("./../../funcoes_bd.php");
include("./../../config.php");
include($caminhoBiblioteca."/forum.inc.php");
include($caminhoBiblioteca."/linkimagem.inc.php");

session_name('multinavi'); session_start();
//Usar ou nao editor html

//A partir de agora, apenas le da sessao
session_write_close();

$configMathml = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);
?>
<html>
	<head>
		<title>F&Oacute;RUM</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<script LANGUAGE="JavaScript">
             <!--
				function openWindow(page) {                                  
				  location.href=page;
			  }	
             //-->
		</script>
	<script language="JavaScript" src="<?=$url?>/js/editorForum.js"></script>
	<script language="javascript" type="text/javascript" src="<?=$url?>/js/tiny_mce/tiny_mce.js"></script>
	   	  <link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	       <link rel="stylesheet" href="<?=$_SESSION["configForum"]["arquivoCSS"]?>" type="text/css">			  
     
	</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 
		
			<table width="100%" height='100%' border="0" cellpadding="0" cellspacing="0" >

			<tr>
				<td align="left">
					<?php
          $obj = new Voltar("forum.php?COD_SALA=".$_REQUEST["COD_SALA"],"Voltar");

          echo $obj->imprime();
					?>
				</td>
			</tr>
				<?php				
				if (($_SESSION["COD_PESSOA"] == "" )OR ($_SESSION["codInstanciaGlobal"] == "" ))
				{
					echo "<td> <p align='center'> <font size='2'> <b> Fórum dispon&iacute;vel apenas para alunos cadastrados.</b> </font> </p> </td>";
					exit();
				}
				if ( isset ($_REQUEST["RESPOSTA"] )) {
					$rsCon = forum($_REQUEST["RESPOSTA"]);	
					
					if ($rsCon) {
					  if ($linha = mysql_fetch_array($rsCon)) {
							echo  "<tr>".
												"<td class='exibe_normal' width='100%' colspan='2' valign='top'>" . 
														"<font color='blue'>".
															"<b>" . $linha["NOME_PESSOA"] . "</b>".
														"</font>".			
														" respondeu em <b>" . $linha["DATA_MENSAGEM"] . "</b>".	
														"<br> <font size='2'>" . str_replace("\n","<br>", $linha["TEXTO_MENSAGEM"]) . "</font><br><br>".
												"</td>".
											"</tr>";
						
					  }
				  }
				}
				?>
	
				<tr>
					<table width="100%" align="center">
						<tr>
							<td align="center">
						
    						<?php
    
    						echo "<form name=\"form1\" method=\"post\" action=\"envia_texto.php\">";
    						if (isset($_REQUEST["RESPOSTA"] )) {
    							echo "Escolha o tipo de Mensagem: ";
    						}
    						else {
    							echo "Escolha o tipo de Mensagem: ";
    						}
    						
    													
    						$tipoMsg=getTipoMsg();
    
    						echo "<select name=\"tipo_msg\" >";
    
    						while($linha=mysql_fetch_array($tipoMsg)) {
    							echo "<option value='".$linha["codTipoMsg"]."'>".$linha["classificacao"]."</option>";
    						}										
    						echo " </select>";
                
                echo "&nbsp;&nbsp;&nbsp;";
                echo ativaDesativaEditorHtml();
                              
    					  ?>
    				
							</td>
						</tr>
						
						<tr>
							<td align="center">
								
					    		<table><tr align="center">
										<td><textarea name="TEXTO" rows="10" cols="70"></textarea></td>
										</tr></table>
										<p align="center">
                        <!--
			    							<input type="submit" name="botao1"  value="Enviar texto">
										    <input type="reset"  name="botao3"  value="Apagar o texto">
                        -->
                        <table><tr align="center">
			    							<td width="80"><a href="#" onClick="javascript:document.form1.submit();"><img src="./imagens/enviarTexto.gif" border="no"><br>Enviar</a></td>
			    							<td width="80"><a href="#" onClick="javascript:document.form1.reset(); form1.TEXTO.focus(); "><img src="./imagens/apagarTexto.gif"  border="no"><br>Apagar</a></td>
                        </tr></table>										
											<input type="hidden" name="RESPOSTA" value="<?=$_REQUEST["RESPOSTA"]?>">	
										    <input type="hidden" name="codMainThread" value="<?=$_REQUEST["codMainThread"]?>">
											<input type="hidden" name="COD_SALA" value="<?=$_REQUEST["COD_SALA"]?>">
											<input type="hidden" name="COD_PESSOA" value="<?=$_REQUEST["COD_PESSOA"]?>">
											<input type="hidden" name="dataInicio" value="<?=$_REQUEST["dataInicio"]?>">
											<input type="hidden" name="dataFim" value="<?=$_REQUEST["dataFim"]?>">
											<input type="hidden" name="acao" value="<?=$_REQUEST["acao"]?>">
  										    <input type="hidden" name="topico" value="<?=$_REQUEST["topico"]?>">
										  </p>
								</form>
								<? if($configMathml->getUsoMathml()==1){
									echo "<p><a href=\"instrucaoUsoPlugginMathml.php\" target=\"_blank\"><b>Ver sintaxe mathml</b></a></p>";

									}
								?>

							</td>
						</tr>
						
						<?
						if ($_SESSION["configForum"]["usaEmoticons"]) {
					      echo "<tr><td align=\"center\">Clique em algum &iacute;cone para adicion&aacute;-lo a sua mensagem<br>";
						   imprimeIcones();
						   echo "</td></tr>";
					   }
						?>
					</table>	
				</tr>
			</table>
		</td>
	</tr>
</table>

<script>
	form1.TEXTO.focus();
</script>
	
</body>
</html>
