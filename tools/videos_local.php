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
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security();
// INCLUIR
// REMOVER
// ALTERAR
// LISTAGEM

if ( !isset($_REQUEST["OPCAO"]) )
	$_REQUEST["OPCAO"] = "";

if ( !isset($_REQUEST["LOCAL"]) )
	$_REQUEST["LOCAL"] = "";

if ( !isset($_REQUEST["COD_VIDEO"]) )
	$_REQUEST["COD_VIDEO"] = "";

if ( !isset($_REQUEST["SENT"]) )
	$_REQUEST["SENT"] = "";
	
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Videos</title>		
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
	echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>P�gina Principal</a></p>";
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
				echo "<br>";

				$nivel = getNivelAtual();

				echo "<form name=\"form\" method=\"post\" action=\"\"><table align=\"center\">\n".
					 "<tr><td><b>Descri��o do v�deo para ".$nivel->nome." : </b>&nbsp;&nbsp;</td></tr><tr><td> <input type=\"text\" name=\"DESC_VIDEO_INSTANCIA\" value='".getDescricaoVideo($_REQUEST["COD_VIDEO"])."' size=\"50\" ></td></tr>\n".
					 "<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp; <select name=\"TIPO_ACESSO\">\n".
					 "<option value=3>Publico</option>\n".
					 "<option value=2 SELECTED>Restrito</option>\n".
					// "<option value=3>Publico e Restrito</option>\n".
					 "</select></td></tr>\n".
					 "<input type=\"hidden\" value=\"ok\" name=\"SENT\">".
					 "<tr><td colspan=2 align=center><br><br><input type=\"button\" value=\"Enviar\"  onclick=\"javascript:if (document.form.DESC_VIDEO_INSTANCIA.value=='') { alert('Preencha a descri��o do texto!'); document.form.DESC_VIDEO_INSTANCIA.focus(); } else { document.form.submit(); }  \">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"window.location.href = 'videos_local.php?acao=Alterar&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."'\"></td></tr>\n".
					 "</table></form>\n";
			 }
			else
			{
				$sucesso = VideoLocalInsere($_REQUEST["COD_VIDEO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"], $_REQUEST["DESC_VIDEO_INSTANCIA"]);
				
				if ( $sucesso )
				{
					echo "<br><br><p align=center>V&iacute;deo publicado<br>\n";
				 }
				else
				{
					echo "<br><br><p align=center>ERRO ao publicar v&iacute;deo<br>";
				 }

				echo "<a href=\"javascript:window.location.href = 'videos_local.php?acao=Alterar&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."'\">Voltar</a>\n";


			 }
		 }
		 
		 break;

//#################################################################################################
//								REMOVER
//#################################################################################################


	case "Remover":
		if ( VideoVerificaAcesso($_REQUEST["COD_VIDEO"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{
				$rsConL = listaVideos($_REQUEST["COD_VIDEO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);

				if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
				{
					echo "Erro ao acessar video.";
					exit();
				}
					
				echo "<br>";

				$nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Remover&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"]."&SENT=1";

				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					<table align="center">

						<tr><td><b>Descri��o do v�deo para <?=$nivel->nome?> :</b>&nbsp;&nbsp;</td></tr><tr><td> <?=$linhaL["DESC_VIDEO_INSTANCIA"]?> </td></tr>
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
										echo "Publico";
								}
							}
						?>
						</td></tr>
						<tr><td colspan=2 align=center><br><input type="submit" name="SENT" value="REMOVER">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href = 'videos_local.php?acao=Alterar&COD_VIDEO=<?=$_REQUEST["COD_VIDEO"]?>';"></td></tr>
					</table>
				</form>	
			<?php
			 }
			else
			{
				$sucesso = VideoLocalRemove($_REQUEST["COD_VIDEO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
	
				if ( $sucesso )
				{ 
					echo "<br><br><p align=center>Local Removido<br>\n";
				 }
				else
				{
					echo "<br><br><p align=center>ERRO na Remo��o<br>";

				 }
				if($_REQUEST['PAGINA']=='instancia'){
					echo "<a href=\"javascript:window.close()\">fechar</a>\n";
					echo "<script> window.opener.location.href='".$url."/aulas/index.php'; </script>";
	
				}else{
						echo "<a href=\"javascript:window.location.href = 'videos_local.php?acao=Alterar&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."'\">Voltar</a>\n";
				}

			 }
		 }
		else 
		{
			echo "<br><br><p align=center>Voc� n�o possui o privil�gio necess�rio.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";			
		 }
		 
		 break;
		 
			
//#################################################################################################
//								ALTERAR
//#################################################################################################


	case "Alterar":
	{ 
		if ( VideoVerificaAcesso($_REQUEST["COD_VIDEO"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{ 
				$rsConL = listaVideos($_REQUEST["COD_VIDEO"], $_REQUEST["codInstanciaGlobal"], "", $_REQUEST["TIPO_ACESSO"]);
				echo "<br>";
				
				if ( $rsConL )				
				{
					if (! $linhaL = mysql_fetch_array($rsConL))
						exit();
				 }
				else
					exit();
				
				$nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Alterar&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					<table align="center">
						<tr><td><b>Descri��o do v�deo para <?=$nivel->nome?> :</b>&nbsp;&nbsp;</td></tr><tr><td> <input type="text" name="DESC_VIDEO_INSTANCIA" size="50" value="<?=$linhaL["DESC_VIDEO_INSTANCIA"]?>" ></td></tr>
						<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp;
						
						    <select name="TIPO_ACESSO_NOVO">
								<!--<option value=1<? if ($linhaL["COD_TIPO_ACESSO"] == 1) echo " selected";?>>Publico</option>-->
								<option value=2<? if ($linhaL["COD_TIPO_ACESSO"] == 2 ) echo " selected";?>>Restrito</option> 
								<option value=3<? if ($linhaL["COD_TIPO_ACESSO"] == 3 ) echo " selected";?>>Publico</option>
						    </select>
						    </td>
						</tr>
					
						<tr><td colspan=2 align="center"><input type="submit" name="SENT" value="Enviar">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href = 'videos_local.php?acao=Alterar&COD_VIDEO=<?=$_REQUEST["COD_VIDEO"]?>';"></td></tr>
					</table>
				</form>
	
			<?php
			   
			 }
			else
			{
				$sucesso = VideoLocalAltera($_REQUEST["COD_VIDEO"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["DESC_VIDEO_INSTANCIA"], $_REQUEST["TIPO_ACESSO"],$_REQUEST["TIPO_ACESSO_NOVO"]);
	
//				echo "Altera";
//				exit();
				
				if ($sucesso)
					echo "<br><br><p align=center>Local Alterado<br>";
				else
				{
					echo "<br><br><p align=center>ERRO na Altera��o<br>";
				 }

				echo "<a href=\"javascript:window.location.href = 'videos_local.php?acao=Alterar&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."'\">Voltar</a>\n";

			 }
		 } 
		else 
		{ 
			echo "<br><br><p align=center>Voc� n�o possui o privil�gio necess�rio.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";
		 }
	} 
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
	
			if ( $_REQUEST["COD_VIDEO"] != "" )
			{	
			
				if ($_SESSION["userRole"] == PROFESSOR)
					$numNiveisImprime = NUM_NIVEIS_PROFESSOR;
				else
					$numNiveisImprime = 100;

				$numNiveisImprime = 2;

				$rsConL = listaVideosLocal($_REQUEST["COD_VIDEO"]); // $rsConL � setado novamente
				$publicadoNestaInstancia = 0;
				echo imprimeLocais($rsConL,$_SERVER["PHP_SELF"],"COD_VIDEO",$_REQUEST["COD_VIDEO"],$numNiveisImprime,$_SESSION['codInstanciaGlobal'],$publicadoNestaInstancia);
			}
	
			?>
			<tr>
				<td align="left" colspan=3 height="33px">
					<!--<a href="#" onclick="el = parent.parent.document.getElementById('recurso'); el.src = 'tools/videos_local.php?OPCAO=Incluir&COD_VIDEO=<?=$_REQUEST["COD_VIDEO"]?>';">-->

					<? if (!$publicadoNestaInstancia) {
					      echo "<a href='videos_local.php?OPCAO=Incluir&COD_VIDEO=".$_REQUEST["COD_VIDEO"]."'>".						   
                     "Incluir em ".$instanciaAtual->getAbreviaturaOuNomeComPai()."</a>";
              }?>
						
					</a>
				</td>
        </tr>
        
		</table>

	<?php
}
?>

</body>

</html>
