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
//session_name('multinavi_cpmd');
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security();
$nivel = getNivelAtual();

?>
	
<html>
	<head>
		<title>V�deos</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg" style="overflow:scroll">

<?php

	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
			
	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>P�gina Principal</a></p>";
		exit();
	 }
		
?>

<table cellpadding="10" cellspacing="0" border="0" width="85%"  align="center">
	<tr>
		
    <td colspan="5" align="center"><font size="4"><b>V&iacute;deos</b></font></td>
	</tr>

	<tr>	

		<?
		if ($acesso == 1)
		{
		?>	
			<td colspan="3" align="left">
				<a href="videos_operacao.php?PAGINA=<?=$_REQUEST['PAGINA'];?>&OPCAO=Inserir">Inserir novo v�deo</a>				
			</td>
			<td colspan="2" align="right">
		<? if($_REQUEST['PAGINA']=='instancia'){
			echo "<a href=\"".$url."/aulas/index.php\">Voltar para V�deo-aulas</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Ger�ncia</a>";

		}
		?>			</td>	
		<?
		}
		else
		{
		?>
			<td colspan="1" align="left">
				<a href="videos_operacao.php?OPCAO=Inserir">Inserir novo v�deo</a>				
			</td>
			<td colspan="1" align="right">
		<? if($_REQUEST['PAGINA']=='instancia'){
			echo "<a href=\"".$url."/aulas/index.php\">Voltar para V�deo-aulas</a>";	
		}else{
			echo "<a href=\"index.php\">Voltar para Ferramentas de Ger�ncia</a>";

		}
		?>			</td>			
		<?
		}
		?>

	</tr>

	<tr>
		<?php

		if (!empty($_REQUEST["FILTRO"]))
			$_SESSION["FILTRO"] = $_REQUEST["FILTRO"];

		if ($acesso == 1)
		{

			if (empty($_SESSION["FILTRO"]))
				$_SESSION["FILTRO"] = "todas";

			?>
			<td align="left"><a href="?FILTRO=todas">Todos</a></td>
			<td align="center"><a href="?FILTRO=algo">Ativos</a></td>
			<td align="center"><a href="?FILTRO=nenhum">N�o Ativos</a></td>
			<td align="center"><a href="?FILTRO=suas">Seus V�deos</a></td>	
  		<td align="center"><a href="?FILTRO=instanciaAtual">Publicados <?=$nivel->nome?> atual</td>
			<?php
		 }
		else
		if ($acesso == 2 or $acesso == 3)
		{

			if (empty($_SESSION["FILTRO"]))
				$_SESSION["FILTRO"] = "suas";

			?>
			<td align="center"><a href="?FILTRO=suas">Seus V�deos</a></td>	
  		<td align="center"><a href="?FILTRO=instanciaAtual">Publicados <?=$nivel->nome?> atual</td>
			<?php
		 }
		?>
		
	</tr>
</table>	
	
<?php

if ( !isset($_SESSION["FILTRO"]) )
	exit();
?>

<br>

<?php

$filtro = $_SESSION["FILTRO"];
$quem   = "";

if  ( ( ($filtro == "todas" or $filtro == "algo" or $filtro == "nenhum" or $filtro == "principal") and ($acesso == 1) ) or
	  ( ($filtro == "suas" or $filtro == "instanciaAtual") and ($acesso == 1 or $acesso == 2 or $acesso == 3) )
	)
{		
		if ($filtro == "todas")
			$filtro = "";
		
		if ($filtro == "suas")
			$quem = $_SESSION["COD_PESSOA"];
		
		$rsConN = listaVideosAdm($_SESSION["codInstanciaGlobal"], $filtro, $quem);		
		
		if ($rsConN)
		{
			if ($linhaN = mysql_fetch_array($rsConN))
			{
				if ($filtro == "instanciaAtual")
					echo " &nbsp;&nbsp;&nbsp; <b> Textos ".$nivel->nome." : </b> <br><br>";

				?>
				<table cellpadding="0" cellspacing="0" border="0" width="85%"  align="center">
				<tr>
					<td width="80" align="center">
						<b> Excluir - Alterar </b>				
					</td>
					<td width="40" align="right">&nbsp;   
					</td>
					<td> <font size="2"><b>Descri��o </b></font></td>
				</tr>
				
				<tr> <td colspan="3">&nbsp;  </td> </tr>
				<?php
				
				while ($linhaN) 
				{
					echo "<tr>\n".
						 "	<td align=\"center\">\n".
						 "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este v�deo ?')) { window.open('videos_envio.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Remover&COD_VIDEO=" . $linhaN["COD_VIDEO"] . "','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">\n".
						 "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">\n".
						 "		</a>\n&nbsp;&nbsp;&nbsp;\n".
						 "		<a href=\"videos_operacao.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Alterar&COD_VIDEO=".$linhaN["COD_VIDEO"]."\">\n".
						 "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">\n".
						 "		</a>".
						 "	</td>\n".
						 "	<td align=\"right\">" . $linhaN["COD_VIDEO"] . "&nbsp;&nbsp;</td>\n".
						 "	<td align=\"left\">" . $linhaN["DESC_VIDEO"] . "</td>\n".
						 "</tr>\n";
		
					$linhaN = mysql_fetch_array($rsConN);
				 }		
			 }
			else
			{	echo "  &nbsp;&nbsp;&nbsp; <b> N�o h� v�deos cadastradas. </b>";
			 }
		 }
		 
		echo "</table>";
	 }


//    Se nivel_acesso=1 then 1, 2, 3, 4, 5		acesso = 1
//    senao
//        Fazer consulta
//            Se � ADM_Curso 4					acesso = 2
//            senao
//                Se Prof 4						acesso = 3

//1. Todos
//2. Ativos
//3. N�o Ativos
//4. Seus V�deos

?>

</body>
</html>