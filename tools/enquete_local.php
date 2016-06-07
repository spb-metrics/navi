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

if ( !isset($_REQUEST["SENT"]) )
	$_REQUEST["SENT"] = "";
	
	if ( !isset($_REQUEST["COD_ENQUETE"]) )
	$_REQUEST["COD_ENQUETE"] = "";
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Exercícios</title>		
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		<script language="JavaScript">
			function open1(src) {
			    props = "top=106,left=120,width=756px,height=450px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes";
				page = window.open(src,"Wlocal",props);
				page.focus();
			}
		</script>
	</head>
	
<body bgcolor="#FFFFFF" text="#000000" class="bodybg" style="background-image: none">

<?php
$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];

if ( ( $acesso != 1 ) AND ( $acesso != 2 ) AND ( $acesso != 3 ) ) { 
	echo "<p align='center'>Acesso Restrito.</p>";
	exit();
} 

$nivelAtual = getNivelAtual();
$instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());

switch ($_REQUEST["OPCAO"]) { 

//#################################################################################################
//									INCLUIR
//#################################################################################################
	case "Incluir":
		if ( $_REQUEST["LOCAL"] == "" ) {
			
			if ( $_REQUEST["SENT"] == "" )	{
				
				echo "<br> ".
					 "<form name=\"form\" method=\"post\" action=\"\"><table align=\"center\">\n".
					 "<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp;</td><td> <select name=\"TIPO_ACESSO\">\n".
					 "<option value=3>Publico</option>\n".
					 "<option value=2 SELECTED>Restrito</option>\n".
					// "<option value=3>Publico e Restrito</option>\n".
					 "</select></td></tr>\n".
					"<input type=\"hidden\" value=\"ok\" name=\"SENT\">".
					 //"<tr><td colspan=2 align=center><br><br><input type=\"submit\" name=\"SENT\" value=\"Enviar\">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"history.back()\"></td></tr>\n".
					 "<tr><td colspan=2 align=center><br><br><input type=\"button\" value=\"Enviar\"  onclick=\"javascript: document.form.submit();   \">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"window.location.href = 'enquete_local.php?COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."'\"></td></tr>\n".
					 "</table></form>\n";
			 }
			else {
				
				$sucesso = EnqueteLocalInsere($_REQUEST["COD_ENQUETE"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
				if ( $sucesso )
				{
					echo "<br><br><p align=center>Enquete publicada<br>\n";
				 }
				else
				{
					echo "<br><br><p align=center>ERRO ao publicar enquete <br>";
				 }

				echo "<a href='enquete_local.php?COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."'>Voltar</a>\n";
			 }
		 }
		 
		 break;

//#################################################################################################
//								REMOVER
//#################################################################################################


	case "Remover":
		//CRIAR UMA FUNCAO PRA VERIFICAR ACESSO DA ENQUETE
		//if ( EnqueteVerificaAcesso($_REQUEST["COD_ENQUETE"]) )
		//{
			if ( $_REQUEST["SENT"] == "" )
			{
				//CRIAR UMA FUNCAO PRA LISTAR ENQUETES
              
				$rsConL = listaEnquete($_REQUEST["COD_ENQUETE"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);

				if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
				{
					echo "Erro ao acessar enquete.";
					exit();
				}
					
				echo "<br>";

		//	  $nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Remover&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					<table align="center">

						<tr><td><b>Descrição da Enquete para <?=$nivel->nome?> :</b>&nbsp;&nbsp;</td><td> <?=$linhaL["TEXTO_ENQUETE"]?> </td></tr>
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
						<tr><td colspan=2 align=center><br><input type="submit" name="SENT" value="REMOVER">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href = 'enquete_local.php?COD_ENQUETE=<?=$_REQUEST["COD_ENQUETE"]?>'"></td></tr>
					</table>
				</form>	
			<?php
			 }
			else
			{
				//CRIAR UMA FUNCAO PARA REMOVER A ENQUETE
				$sucesso = EnqueteLocalRemove($_REQUEST["COD_ENQUETE"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"]);
	
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
					echo "<script> window.opener.location.href='".$url."/interacao/enquete/index.php'; </script>";
	
				}else{

				echo "<a href='enquete_local.php?COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."'>Voltar</a>\n";
				}

			 }
		/* }
		else 
		{
			echo "<br><br><p align=center>Você não possui o privilégio necessário.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";			
		 } */
		 
		 break;
		 
			
//#################################################################################################
//								ALTERAR
//#################################################################################################


	case ( "Alterar" ):
	{ 
		//CRIAR UMA FUNCAO PARA VERIFICAR ACESSO DA ENQUETE
		//if ( EnqueteVerificaAcesso($_REQUEST["COD_ENQUETE"]) )
		//{
			if ( $_REQUEST["SENT"] == "" )
			{ 
				//CRIAR UMA FUNCAO PRA LISTAR TODAS AS ENQUETES
				$rsConL = listaEnquete($_REQUEST["COD_ENQUETE"], $_REQUEST["TURMA"], "", $_REQUEST["TIPO_ACESSO"]);
				echo "<br>";
				
				if ( $rsConL )				
				{
					if (! $linhaL = mysql_fetch_array($rsConL))
						exit();
				 }
				else
					exit();
				
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Alterar&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."&TIPO_ACESSO=".$_REQUEST["TIPO_ACESSO"];

				?>
	
				<form name="form" method="post" action="">				
					<table align="center">
						<!--<tr><td><b>Descrição da Enquete para a Turma :</b>&nbsp;&nbsp;</td><td> <input type="text" name="TEXTO_ENQUETE" size="80" value="<?=$linhaL["TEXTO_ENQUETE"]?>" ></td></tr>-->
						<tr><td><b>Tipo de Acesso :</b>&nbsp;&nbsp;&nbsp;</td>
						<td>
						    <select name="TIPO_ACESSO_NOVO">
								<!--<option value=1<? if ($linhaL["COD_TIPO_ACESSO"] == 1) echo " selected";?>>Publico</option>-->
								<option value=2<? if ($linhaL["COD_TIPO_ACESSO"] == 2 ) echo " selected";?>>Restrito</option> 
								<option value=3<? if ($linhaL["COD_TIPO_ACESSO"] == 3 ) echo " selected";?>>Publico</option>
						    </select>
						    </td>
						</tr>
					
						<tr><td colspan=2 align=center><input type="submit" name="SENT" value="Enviar">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href = 'enquete_local.php?COD_ENQUETE=<?=$_REQUEST["COD_ENQUETE"]?>'"></td></tr>
					</table>
				</form>
	
			<?php
			   
			 }
			else
			{
				//CRIAR UMA FUNÇÃO PARA ALTERAR A ENQUETE
				$sucesso = EnqueteLocalAltera($_REQUEST["COD_ENQUETE"], $_REQUEST["codInstanciaGlobal"], $_REQUEST["TIPO_ACESSO"],$_REQUEST["TIPO_ACESSO_NOVO"]);
	
//				echo "Altera";
//				exit();
				
				if ($sucesso)
					echo "<br><br><p align=center>Local Alterado<br>";
				else
				{
					echo "<br><br><p align=center>ERRO na Alteração<br>";
				 }

				echo "<a href='enquete_local.php?COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."'>Voltar</a>\n";

			 }
		/* } 
		 
		else 
		{ 
			echo "<br><br><p align=center>Você não possui o privilégio necessário.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";
		 } */
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
	
			if ( $_REQUEST["COD_ENQUETE"] != "" )
			{	

				if ($_SESSION["userRole"] == PROFESSOR)
					$numNiveisImprime = NUM_NIVEIS_PROFESSOR;
				else
					$numNiveisImprime = 100;

				$numNiveisImprime = 2;

				$rsConL = listaEnqueteLocal($_REQUEST["COD_ENQUETE"]);

        $publicadoNestaInstancia = 0;  //parametro de saida, pode ser usado para determinar se já está aqui!
				echo imprimeLocais($rsConL,$_SERVER["PHP_SELF"],"COD_ENQUETE",$_REQUEST["COD_ENQUETE"],$numNiveisImprime,$_SESSION['codInstanciaGlobal'],$publicadoNestaInstancia);
	
			 }
			
			?>
			<tr>
				<td align="left" colspan=3 height="33px">
				  <?
				    if (!$publicadoNestaInstancia) {
  					  echo "<a href='enquete_local.php?OPCAO=Incluir&COD_ENQUETE=".$_REQUEST["COD_ENQUETE"]."'>";	
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
