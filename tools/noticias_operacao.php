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
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/noticia.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once($caminhoBiblioteca."/rss/rss.inc.php");
include_once($caminhoBiblioteca."/utils.inc.php");
session_name(SESSION_NAME); session_start(); security();
/*colocar array para trocar layout entre lembretes e noticias*/
if(empty($_SESSION['recurso'])|| $_SESSION['recurso']!=$_REQUEST['recurso'] && !empty($_REQUEST['recurso']) ){
$_SESSION['recurso']=$_REQUEST['recurso'];
}

if($_SESSION['recurso']=="noticias"){
  $configRecurso['nome']="Not&iacute;cia";
  $configRecurso['conjugacao_a']="a";
  $configRecurso['conjugacao_b']="esta";
 
}else{
  $configRecurso['nome']="Lembrete";
  $configRecurso['conjugacao_a']="o";
  $configRecurso['conjugacao_b']="este";
 
}

?>
<html>
	<head>
		<title>Ferramentas de Administrador - Noticias</title>
		
		<link rel="stylesheet" href="./../css/padraogeral.css" type="text/css"">
		<script language="JavaScript" src="<?=$url?>/js/editor.js"></script>
	  <script language="javascript" type="text/javascript" src="<?=$url?>/js/tiny_mce/tiny_mce.js"></script>
    	<script language="JavaScript">
			
      function validaForm()
			{
				var msg = "";
				var obj = "";
				var preen = true;
       

					// Valida titulo noticia - text
					if ((document.form1.TITULO_NOTICIA.value == null) || (document.form1.TITULO_NOTICIA.value == ""))
					{
						msg += "=> Titulo não preenchida;\n";
          
						preen = false;
           
					 }
          /* Valida resumo noticia - text
					if ((document.form1.RESUMO_NOTICIA.value == null) || (document.form1.RESUMO_NOTICIA.value == ""))
					{
						msg += "=> Resumo não preenchida;\n";
          
						preen = false;
           

					 }
           // Valida texto noticia - text
					if ((document.form1.TEXTO_NOTICIA.value == null) || (document.form1.TEXTO_NOTICIA.value == ""))
					{
						msg += "=> Texto não preenchida;\n";
            
						preen = false;
            

					 }*/
        if (msg != "")
				{
					msg = "Ocorreram os seguintes erros:\n\n" + msg;
					alert (msg);
					return false;
				}
				else
				{
					return true;
				}
			}
      
			
				
			function enviaForm()
			{
        
				if(validaForm())
				{
					
          document.form1.action="./noticias_envio.php?OPCAO=<?=$_REQUEST["OPCAO"]?>";
          document.form1.submit();
				}
                 
			}
			
	function novoSite() {
    el = document.getElementById("sitesRSS");
    el.innerHTML+= '<input type="text" name="sitesRSS[]" size="80" title="coloque a origem das notícias RSS"><br>';
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
	
	if (! isset($_REQUEST["FILTRO"]) )
		$_REQUEST["FILTRO"] = "";


	if ($_REQUEST["COD_NOTICIA"]!="" )
  {
		if ( !NoticiaVerificaAcesso($_REQUEST["COD_NOTICIA"]) )
		{
			echo "<tr><td><br><b>Acesso negado a ".$configRecurso['conjugacao_b']." ".$configRecurso['nome']."</b><br></td></tr>";
			exit();
		}
  }
?>
<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
	<tr>
		<td align=left>		
		<?php
		if (isset($_REQUEST["NOTICIAS_ENVIO"]))
		{
			if ( $_REQUEST["NOTICIAS_ENVIO"] == "alterar" )
				echo " <br><b>Notícia Alterada com Sucesso</b>";		
			else
				if ( $_REQUEST["NOTICIAS_ENVIO"] == "inserir" )
					echo " <br><b>Notícia Inserida com Sucesso</b>";
		 }		
		else
		{
			echo "<br><b> ". $configRecurso['nome']." - " . $_REQUEST["OPCAO"] . "</b>";
			
			if ( $_REQUEST["OPCAO"] == "Alterar" )
			{
				echo "<br><br>" . "\n";
				echo "<table><tr><td>" . "\n";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir ".$configRecurso['conjugacao_b']." ".$configRecurso['nome']." ?')) { window.open('noticias_envio.php?OPCAO=Remover&PAGINA=noticias&COD_NOTICIA=" . $_REQUEST["COD_NOTICIA"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
				echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
				echo "<td><font color='red'>";
				echo "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir ".$configRecurso['conjugacao_b']." ".$configRecurso['nome']." ?')) { window.open('noticias_envio.php?OPCAO=Remover&PAGINA=noticias&COD_NOTICIA=" . $_REQUEST["COD_NOTICIA"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">" . "\n";
				echo "Excluir ".$configRecurso['conjugacao_b']." ".$configRecurso['nome']."</a></font>" . "\n";
				echo "</td></tr></table>";
			 }
		 }
		?>
		</td>
		<td align=right colspan=2>
 			<a href="../tools/index.php" >Ferramentas de Gerência</a> - 
			<a href="./noticias.php?FILTRO=<?=$_REQUEST["FILTRO"]?>&CURSO=<?=$_REQUEST["CURSO"]?>&TURMA<?=$_REQUEST["CURSO"]?>=<?=$_REQUEST["TURMA" . $_REQUEST["CURSO"]]?>">Ir para <?=$configRecurso['nome']?> </a>
		</td>
	</tr>

	<?php
	if ( !isset($_REQUEST["COD_NOTICIA"]) )
		$_REQUEST["COD_NOTICIA"] = "";
	
	$rsConN = listaNoticias($_REQUEST["COD_NOTICIA"],"","","","");
	
	if ( $_REQUEST["OPCAO"] == "Inserir" )
	{
		$titulo = "";
		$resumo = "";
		$texto  = "";
	 }
	
	if ( ($_REQUEST["OPCAO"] != "Inserir") AND ( $_REQUEST["COD_NOTICIA"] != "" ) )
	{
	
		$rsConN = listaNoticias($_REQUEST["COD_NOTICIA"],"","","","");

		if ( (! $rsConN) or (mysql_num_rows($rsConN) == 0) )
		{
			echo "<tr> <td> Noticia não encontrada </td></tr></table>";
			exit();
		}
	
		$linhaN = mysql_fetch_array($rsConN);
		$titulo = "";
		$resumo = "";
		$texto  = "";
	
		$titulo = str_replace("<br>", "\n", $linhaN["TITULO_NOTICIA"]);
		$titulo = str_replace("\"" ,"&quot;", $linhaN["TITULO_NOTICIA"]);
		
		$resumo = str_replace("<br>", "\n", $linhaN["RESUMO_NOTICIA"]);
//    	$resumo = str_replace("\"", "&quot", $linhaN["RESUMO_NOTICIA"]);// lembrar de pequisar o porque nao funciona?!!!
				
		$texto  = str_replace("<br>","\n" , $linhaN["TEXTO_NOTICIA"]);
//		$texto  = str_replace("\"", "&quot;", $linhaN["TEXTO_NOTICIA"]);
    $sitesRSS = $linhaN["sitesRSS"];
    $numeroNoticiasRSSResumo= $linhaN["numeroNoticiasRSSResumo"];	
		$noticia = $linhaN["COD_NOTICIA"];
		
	 }
  echo "<tr><td>".ativaDesativaEditorHtml()."</td></tr>";
	?>

	
  <form name="form1" method="post" action="noticias_envio.php?OPCAO=<?=$_REQUEST["OPCAO"]?>">
		<tr>
			<td align=center width="60%">		

				
				<p>T&iacute;tulo d<?echo $configRecurso['conjugacao_a']." ".$configRecurso['nome'];?>:<br>
			    	<input type="text" name="TITULO_NOTICIA" value="<?=$titulo?>" size="80" <?php if ($_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>>
        </p>
        
				<p>Resumo d<?echo $configRecurso['conjugacao_a']." ".$configRecurso['nome'];?> <br>
						     <textarea name="RESUMO_NOTICIA" cols="80" rows="3"  <?php if ($_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>><?=$resumo?></textarea>
       	        
         </p>
				<p>Texto d<?echo $configRecurso['conjugacao_a']." ".$configRecurso['nome'];?> <br>
					<textarea name="TEXTO_NOTICIA" cols="80" rows="20" <?php if ( $_REQUEST["OPCAO"]=="Remover") echo "ReadOnly";?>><?=$texto;?></textarea>
				 
        </p>
				<br>
					
			 <p>Sites com Not&iacute;cia RSS:<br>
				
        <div id="sitesRSS" onFocus="novoSite();">
            <?
            $sitesRSS=explode(';',$sitesRSS);
            for($i=0;$i<count($sitesRSS);$i++){
               echo"<input type=\"text\" name=\"sitesRSS[]\" size=\"80\"  value=\"".$sitesRSS[$i]."\" title=\"coloque a origem das notícias RSS\"> ";
            }
            echo "<span style=\"cursor:pointer;\" onClick=\"novoSite()\" title=\"Adicionar mais uma noticias no padrão RSS\" >+</span>";
           ?> 
         </div><br>
	   </p>
        <p style="color:red;align=left" >Número de Not&iacute;cias exibidas junto ao resumo:
           <input type="text" name="numeroNoticiasRSSResumo" value="<?=$numeroNoticiasRSSResumo;?>" size="3" maxlength="3" title="Número de link´s impressos na capa">      
        </p>
      </td>

			<?php
			if ( $_REQUEST["OPCAO"] != "Inserir" )
			{
			?>
				<td width="40%" valign="top">
					Localização:
					<br><br>

					<iframe name="locais" src="noticias_local.php?COD_NOTICIA=<?=$noticia?>" frameborder=0 style="position:absolute; width:250px; height:500px; z-index: 3; overflow: visible; visibility: visible">
					</iframe>
				</td>
			<?php
			 }
			?>
		</tr>

		<tr>
			<td align="center">
      			<input type="button" name="Submit" value="<? if ( $_REQUEST["OPCAO"]=="Remover") echo "Excluir"; else echo "Enviar";?>" onClick="javascript:enviaForm()">
		        <input type="hidden" name="COD_NOTICIA" value="<?=$noticia;?>">
			</td>
			<td>&nbsp;
				
			</td>
		</tr>	
	</form>
</table>

</body>

</html>
