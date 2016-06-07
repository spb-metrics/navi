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
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();


// INCLUIR
// REMOVER
// ALTERAR
// LISTAGEM

if ( !isset($_REQUEST["OPCAO"]) )
	$_REQUEST["OPCAO"] = "";

if ( !isset($_REQUEST["LOCAL"]) )
	$_REQUEST["LOCAL"] = "";

if ( !isset($_REQUEST["COD_ARQUIVO"]) )
	$_REQUEST["COD_ARQUIVO"] = "";
	
if ( !isset($_REQUEST["SENT"]) )
	$_REQUEST["SENT"] = "";
	
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Acervo</title>		
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
	
<body bgcolor="#FFFFFF" text="#000000" class="bodybg" style="background-image: none">

<?php
$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];

if ( ( $acesso != 1 ) AND ( $acesso != 2 ) AND ( $acesso != 3 ) )
{ 
	echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
	exit();
 } 
$nivelAtual = getNivelAtual();
$instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());

switch ($_REQUEST["OPCAO"])
{ 


//#################################################################################################
//									INCLUIR
//#################################################################################################


	case "Incluir":
		if ( $_REQUEST["LOCAL"] == "" )
		{ 

			if ( $_REQUEST["SENT"] == "" )
			{
				echo "<br> <p align='center'>";

				$nivel = getNivelAtual();
	 
				echo "</p>".
					 "<form name=\"form\" method=\"post\" action=\"\"><table align=\"center\">\n".
					 "<tr><td><b>Descrição para ".$nivel->nome." : </b>&nbsp;&nbsp;</td></tr><tr><td> <input type='text' name='DESC_ARQUIVO_INSTANCIA' size='50' value='".getDescricaoArquivo($_REQUEST["COD_ARQUIVO"])."' ></td></tr>\n".
				     "<tr><td><b>Item Acervo : </b>&nbsp;&nbsp;</td></tr><tr><td><select name=\"COD_TIPO_ITEM_BIB\"  >\n";
					
					 $rsConBIB = listaBiblioteca();
						 if ( $rsConBIB )
						 {          
									while ( $linhaBIB = mysql_fetch_array($rsConBIB) )
								   {
									         
										 echo     "<option value=" . $linhaBIB["COD_TIPO_ITEM_BIB"]. ">" . $linhaBIB["DESC_TIPO_ITEM_BIB"] . "</option>\n";
										 echo ("SELECTED");
										 
									}		     
						   }       
					 
				echo "</select></td></tr>".
				     "<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name=\"TIPO_ACESSO\">\n".
					 "<option value=3>Publico</option>\n".
					 "<option value=2 SELECTED>Restrito</option>\n".
					// "<option value=3>Publico e Restrito</option>\n".
					 "</select></td></tr>\n".
					 "<input type=\"hidden\" value=\"ok\" name=\"SENT\">".
					 //"<tr><td colspan=2 align=center><br><br><input type=\"submit\" name=\"SENT\" value=\"Enviar\">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"history.back()\"></td></tr>\n".
					 "<tr><td colspan=2 align='left'><br><br><input type=\"button\" value=\"Enviar\"  onclick=\"javascript:if (document.form.DESC_ARQUIVO_INSTANCIA.value=='') { alert('Preencha a descrição do texto!'); document.form.DESC_ARQUIVO_INSTANCIA.focus(); } else { document.form.submit(); }  \">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"window.location.href = 'acervo_local.php?acao=Alterar&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\"></td></tr>\n".
					 "</table></form>\n";
					
			 }
			else
			 {
				$sucesso = AcervoLocalInsere($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"], $_REQUEST["DESC_ARQUIVO_INSTANCIA"],$_REQUEST["COD_TIPO_ITEM_BIB"]);
				
				if ( $sucesso )
				{
					echo "<br><br><p align=center>Acervo inserido<br>\n";
				 }
				else
				{   
									  
					echo "<br><br><p align=center>ERRO ao inserir Acervo<br>";
				 }

				 echo "<a href=\"javascript:window.location.href = 'acervo_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";

			 }
		 }
		 
		 break;

//#################################################################################################
//								REMOVER
//#################################################################################################


	case "Remover":
		//if ( AcervoVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		//{
			if ( $_REQUEST["SENT"] == "" )
			{
				$rsConL = listaAcervo($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);


				if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
				{
					echo "Erro ao acessar o acervo.";
					exit();
				}
					
				echo "<br>";

			  $nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Remover&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					<table align="center">

						<tr><td><b>Descrição para <?= $nivel->nome ?> :</b>&nbsp;&nbsp;</td><td> <?=$linhaL["DESC_ARQUIVO_INSTANCIA"]?> </td></tr>
						
						<tr><td><b>Tipo de Acervo:</b>&nbsp;&nbsp;</td><td><?=$linhaL["DESC_TIPO_ITEM_BIB"]?></td></tr>
						
						
						<tr><td><b>Tipo de Acesso :&nbsp;&nbsp;&nbsp;</b></td><td> 
						<?	if ($linhaL["COD_TIPO_ACESSO"] == 1 ) 
							{
								echo "Publico"; 
							}
							else 
							{	if ($linhaL["COD_TIPO_ACESSO"] == 2)
								{
									echo "Restrito";
								}
								else
								{	if ($linhaL["COD_TIPO_ACESSO"] == 3 )
										echo "Publico";
								}
							}
						?>
						</td></tr>
						<tr><td colspan=2 align=center><br><input type="submit" name="SENT" value="REMOVER">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href = 'acervo_local.php?acao=Alterar&COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>'"></td></tr>
					</table>
				</form>	
			<?php
			 }
			else
			{    $rsConL = listaAcervo($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
			    if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
				{
					echo "Erro ao acessar o acervo.";
					exit();
				}
				$sucesso = AcervoLocalRemove($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"],$linhaL["COD_TIPO_ITEM_BIB"]);
	
				if ( $sucesso )
				{ 
					echo "<br><br><p align=center>Local Removido<br>\n";
				 }
				else
				{
					echo "<br><br><p align=center>ERRO na Remoção<br>";
				 }



				if($_REQUEST['PAGINA']=='instancia'){
					echo "<a href=\"javascript:window.close()\">fechar</a>\n";
					echo "<script> window.opener.location.href='".$url."/biblioteca/interno.php'; </script>";
	
				}else{
				echo "<a href=\"javascript:window.location.href = 'acervo_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";
				}

			 }
		/* }
		else 
		{
			echo "<br><br><p align=center>Você não possui o privilégio necessário.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";			
		 }*/
		 
		 break;
		 
			
//#################################################################################################
//								ALTERAR
//#################################################################################################


	case ( "Alterar" ):
	{ 
		//if ( AcervoVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		//{ 
			if ( $_REQUEST["SENT"] == "" )
			{ 
				$rsConL = listaAcervo($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
				
				echo "<br> <p align='center'>";
				
				if ( $rsConL )				
				{  if(!$linhaL = mysql_fetch_array($rsConL))
				       exit();
					
				 }
				else{
				    echo "Acervo nao lista, erro no rscon";
					exit();
					}

				$nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Alterar&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				 
				echo "</p>".
					 "<form name=\"form\" method=\"post\" action=\"\"><table align=\"center\">\n".
					 "<tr><td><b>Descrição do texto para ".$nivel->nome."</b>&nbsp;&nbsp;</td><td> <input type=\"text\" name=\"DESC_ARQUIVO_INSTANCIA\" value=\"" . $linhaL["DESC_ARQUIVO_INSTANCIA"] . "\" size=\"80\" ></td></tr>\n".
				     "<tr><td><b>Item Acervo : </b>&nbsp;&nbsp;</td><td> <select name=\"COD_TIPO_ITEM_BIB_NOVO\"  >\n";
					
					 $rsConBIB = listaBiblioteca();

						 if ( $rsConBIB )
						 {          
									while ( $linhaBIB = mysql_fetch_array($rsConBIB) )
								   {
									         note($linhaBIB);
										 echo     "<option value=" . $linhaBIB["COD_TIPO_ITEM_BIB"];
										 if ($linhaBIB["COD_TIPO_ITEM_BIB"] == $linhaL["COD_TIPO_ITEM_BIB"])
											 echo " SELECTED";
										 
										 echo ">" . $linhaBIB["DESC_TIPO_ITEM_BIB"] . "</option>\n";
										 
									}		     
						   }       
					 
				if ($linhaL["COD_TIPO_ACESSO"] == 1) { $op1 = "SELECTED"; $op2 = ""; $op3 = ""; }
				elseif ($linhaL["COD_TIPO_ACESSO"] == 2) { $op1 = ""; $op2 = "SELECTED"; $op3 = ""; }
				elseif ($linhaL["COD_TIPO_ACESSO"] == 3) { $op1 = ""; $op2 = ""; $op3 = "SELECTED"; }

				echo "</select></td></tr>".
				     "<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp;</td><td> <select name=\"TIPO_ACESSO_NOVO\">\n".
					// "<option value=1 ".$op1.">Publico</option>\n".
					 "<option value=2 ".$op2.">Restrito</option>\n".
					 "<option value=3 ".$op3.">Publico</option>\n".
					 "</select></td></tr>\n".
					 "<tr><td colspan=2 align='left'><br><br><input type=\"submit\" name=\"SENT\" value=\"Enviar\">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"window.location.href = 'acervo_local.php?acao=Alterar&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\"></td></tr>\n".
					 "</table></form>\n";
						
			 }
			else
			{   
			    $rsConL = listaAcervo($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
				
				echo "<br> <p align='center'>";
				
				if ( $rsConL )				
				{  
				  $linhaL = mysql_fetch_array($rsConL);
					
				 }
				else{
				    echo "Acervo nao lista, erro no rscon";
					exit();
					}
				
				$sucesso = AcervoLocalAltera($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["DESC_ARQUIVO_INSTANCIA"], $_REQUEST["TIPO_ACESSO"],$_REQUEST["TIPO_ACESSO_NOVO"],$linhaL["COD_TIPO_ITEM_BIB"],$_REQUEST["COD_TIPO_ITEM_BIB_NOVO"]);
	           
				if ($sucesso)
					echo "<br><br><p align=center>Local Alterado<br>";
				else
				{
					echo "<br><br><p align=center>ERRO na Alteração<br>";
				 }
				
 				 echo "<a href=\"javascript:window.location.href = 'acervo_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";
				 

			 }
		 } 
		/*else 
		{ 
			echo "<br><br><p align=center>Você não possui o privilégio necessário.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";
		 }
	} */
	break;
	

//#################################################################################################
//								LISTAGEM
//#################################################################################################


	default:
		?>
		<table width="100%" border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>&nbsp;</td>
				<td align="left" width="120px"><U>Local</u></td>
				<td align="center"><u>Tipo</u></td>
				
			</tr>
			<tr> <td colspan="3">&nbsp;  </td> </tr>
			<?php
	
			if ( $_REQUEST["COD_ARQUIVO"] != "" )		{	
				if ($_SESSION["userRole"] == PROFESSOR)
					$numNiveisImprime = NUM_NIVEIS_PROFESSOR;
				else
					$numNiveisImprime = 100;

				$numNiveisImprime = 2;

				$rsConL = listaAcervoLocal($_REQUEST["COD_ARQUIVO"]);
				
        $publicadoNestaInstancia=0; //parametro de saida!
				echo imprimeLocais($rsConL,$_SERVER["PHP_SELF"],"COD_ARQUIVO",$_REQUEST["COD_ARQUIVO"],$numNiveisImprime,$_SESSION['codInstanciaGlobal'],$publicadoNestaInstancia);
			}
			?>
			<tr>
				<td align="left" colspan=3 height="33px">
				  <?
				  if (!$publicadoNestaInstancia) {
					  //<a href="#" onclick="el = parent.parent.document.getElementById('recurso'); el.src = 'tools/acervo_local.php?OPCAO=Incluir&COD_ARQUIVO=$_REQUEST["COD_ARQUIVO"]';"> -->
					  echo "<a href='acervo_local.php?OPCAO=Incluir&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'>";
						echo "Incluir em ".$instanciaAtual->getAbreviaturaOuNomeComPai();
						echo "</a>";
					}
					?>
				</td>
        
			</tr>
		</table>

	<?php
}
?>
</body>
</html>
