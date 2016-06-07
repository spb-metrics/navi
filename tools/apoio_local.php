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
include_once($caminhoBiblioteca."/arquivo.inc.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
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
		<title>Ferramentas de Administrador - Conte&uacute;os</title>		
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		<script language="JavaScript">
			function open1(src) 
			{
			    props = "top=106,left=120,width=756px,height=450px,toolbar=no,status=yes,menubar=no,scrollbars=auto,scrolling=auto,resizable=yes";
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
	echo "<p align='left'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
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
	
		if ( $_REQUEST["SENT"] == "" )
			{
				echo "<br> <p align='left'>";

				$nivel = getNivelAtual();

				echo "</p>".
					 "<form name=\"form\" method=\"post\" action=\"\"><table align=\"left\">\n".
					 "<tr><td><b>Descrição do Conte&uacute;do para ".$nivel->nome." : </b>&nbsp;&nbsp;</td></tr><tr><td> <input type=\"text\" name=\"DESC_ARQUIVO_INSTANCIA\" size=\"50\" value=''".getDescricaoArquivo($_REQUEST["COD_ARQUIVO"])."' ></td></tr>\n".
					 "<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp; <select name=\"TIPO_ACESSO\">\n".
					 "<option value=3>Publico</option>\n".
					 "<option value=2 SELECTED>Restrito</option>\n". //restrito é o padrão
					 //"<option value=3>Publico e Restrito</option>\n".
					 "</select></td></tr>".
					 "<input type=\"hidden\" value=\"ok\" name=\"SENT\">".
					 "<tr><td colspan=2 align=left><br><br><input type=\"button\" value=\"Enviar\"  onclick=\"javascript:if (document.form.DESC_ARQUIVO_INSTANCIA.value=='') { alert('Preencha a descrição do Conteudo!'); document.form.DESC_ARQUIVO_INSTANCIA.focus(); } else { document.form.submit(); }  \">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"window.location.href = 'apoio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\"></td></tr>\n".
					 "</table></form>\n";
			 }
			else
			{
				$sucesso = ApoioLocalInsere($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"], $_REQUEST["DESC_ARQUIVO_INSTANCIA"]);

				if ( $sucesso )
				{
					echo "<br><br><p align=left>Conte&uacute;do inserido<br><br>\n";
				}
				else
				{
					echo "<br><br><p align=left>ERRO ao inserir Conte&uacute;do<br>".
					"<p align=left>Verifique se o Conte&uacute;do já não está inserido neste local.<br><br>";
				}

				echo "<a href=\"javascript:window.location.href = 'apoio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";
			}
	 
		 break;

//#################################################################################################
//								REMOVER
//#################################################################################################


	case "Remover":
		if ( ApoioVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{
				$rsConL = listaApoio($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);

				if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
				{
					echo "Erro ao acessar Conte&uacute;do.";
					exit();
				}

			  $nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Remover&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				?>

				<form name="form" method="post" action="<?=$acao?>">				
					<table align="left">

						<tr><td><b>Descrição do Conte&uacute;do para <?= $nivel->nome ?> :</b>&nbsp;&nbsp;</td></tr>
            <tr><td> <?=$linhaL["DESC_ARQUIVO_INSTANCIA"]?> </td></tr>
						<tr><td><b>Tipo de Acesso :&nbsp;&nbsp;&nbsp;</b> 
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
										//echo "Publico e Restrito";
										echo "Publico";
								}
							}
						?>
						</td></tr>
						<tr><td colspan=2 align='left'><br><input type="submit" name="SENT" value="REMOVER">&nbsp;<input type="reset" 
            value="Cancelar" onclick="window.location.href = 'apoio_local.php?COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>';"></td></tr>
					</table>
				</form>	
			<?php
			 }
			else
			{
				$sucesso = ApoioLocalRemove($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
	
				if ( $sucesso )
				{ 
					echo "<br><br><p align=left>Local Removido<br><br>\n";
				 }
				else
				{
					echo "<br><br><p align=left>ERRO na Remoção<br><br>";
				 }
				if($_REQUEST['PAGINA']=='instancia'){
					echo "<a href=\"javascript:window.close()\">fechar</a>\n";
					echo "<script> window.opener.location.href='".$url."/apoio/index.php'; </script>";
	
				}else{
				echo "<a href=\"javascript:window.location.href = 'apoio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";
				}

			 }
		 }
		else 
		{
			echo "<br><br><p align=left>Você não possui o privilégio necessário.<br>";
				 			
		 }
		 
		 break;
		 
			
//#################################################################################################
//								ALTERAR
//#################################################################################################


	case "Alterar":
	{ 
		if ( ApoioVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{ 
				$rsConL = listaApoio($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
				echo "<br> <p align='left'>";
				
				if ( $rsConL )				
				{
					if (! $linhaL = mysql_fetch_array($rsConL))
						exit();
				 }
				else
					exit();
				
				$nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Alterar&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					<table align="left">
						<tr><td><b>Descrição do Conte&uacute;do para <?= $nivel->nome ?> :</b>&nbsp;&nbsp;</td></tr>
            <tr><td>
               <input type="text" name="DESC_ARQUIVO_INSTANCIA" size="50" value="<?=$linhaL["DESC_ARQUIVO_INSTANCIA"]?>" ></td></tr>
						<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp;
						       <select name="TIPO_ACESSO_NOVO">
								   <!--<option value=1<? if ($linhaL["COD_TIPO_ACESSO"] == 1) echo " selected";?>>Publico</option>-->
								   <option value=2<? if ($linhaL["COD_TIPO_ACESSO"] == 2 ) echo " selected";?>>Restrito</option>
								   <option value=3<? if ($linhaL["COD_TIPO_ACESSO"] == 3 ) echo " selected";?>>Publico</option>
						    </select>
						    </td>
						</tr>
					   
						<tr><td colspan=2 align='left'><input type="submit" name="SENT" value="Enviar">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href = 'apoio_local.php?COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>';"></td></tr>
					</table>
				</form>
	
			<?php
			   
			 }
			else
			{

				$sucesso = ApoioLocalAltera($_REQUEST["COD_ARQUIVO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["DESC_ARQUIVO_INSTANCIA"], $_REQUEST["TIPO_ACESSO"],$_REQUEST["TIPO_ACESSO_NOVO"]);		

//				echo "Altera";
//				exit();
				
				if ($sucesso)
					echo "<br><br><p align=left>Local Alterado<br>";
				else
				{
					echo "<br><br><p align=left>ERRO na Alteração<br>";				       
				 }

				echo "<a href=\"javascript:window.location.href = 'apoio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";

			 }
		 } 
		else 
		{ 
			echo "<br><br><p align=left>Você não possui o privilégio necessário.<br>";
				 
		 }
	} 
	break;
	

//#################################################################################################
//								LISTAGEM
//#################################################################################################


	default:
		?>
		<script type="text/javascript" src="<?=$url?>/nucleo.js"></script>
		<table width="100%" border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>&nbsp;</td>
				<td align="left" width="120px"><U>Local</u></td>
				<td align="left"><u>Tipo</u></td>
			</tr>
			<tr> <td colspan="3">&nbsp;  </td> </tr>
			<?php

		if ( $_REQUEST["COD_ARQUIVO"] != "" )
			{	
				
				if ($_SESSION["userRole"] == PROFESSOR)
					$numNiveisImprime = NUM_NIVEIS_PROFESSOR;
				else
					$numNiveisImprime = 100;

				$numNiveisImprime = 2;

				$rsConL = listaApoioLocal($_REQUEST["COD_ARQUIVO"]);

          $publicadoNestaInstancia = 0;  //parametro de saida, pode ser usado para determinar se já está aqui!
				echo imprimeLocais($rsConL,$_SERVER["PHP_SELF"],"COD_ARQUIVO",$_REQUEST["COD_ARQUIVO"],$numNiveisImprime,$_SESSION['codInstanciaGlobal'],$publicadoNestaInstancia);
			}

			?>
			<tr>
				<td align="left" colspan=3 height="33px">
					<!--a href="javascript:open1('apoio_local.php?OPCAO=Incluir&COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>')"-->
					<!-- <a href="#" onclick="el = parent.parent.document.getElementById('recurso'); el.src = 'tools/apoio_local.php?OPCAO=Incluir&COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>';"> -->
            <? //O objetivo aqui é deixar na mesma janela
              if (!$publicadoNestaInstancia) {
					      echo "<a href='apoio_local.php?OPCAO=Incluir&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'>".						   
                     "Incluir em ".$instanciaAtual->getAbreviaturaOuNomeComPai()."</a>";
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
