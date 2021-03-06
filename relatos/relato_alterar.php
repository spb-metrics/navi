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
include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/relato.inc.php");
include_once ($caminhoBiblioteca."/utils.inc.php");

session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {
  echo 'Sem permissao de interacao.'; die;
}

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
		<script language="JavaScript" src="<?=$url?>/js/editor.js"></script>
	  <script language="javascript" type="text/javascript" src="<?=$url?>/js/tiny_mce/tiny_mce.js"></script>
	 
  </head>

<body bgcolor="#FFFFFF" text="#000000">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 

		
		
	    <td width="100%" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>
				  
<?php
if (! isset($_REQUEST["ORDER"]))
	$_REQUEST["ORDER"] = "";
?>				  
			<p align="right"><a href="./relato_listar.php?ORDER=<?= $_REQUEST["ORDER"] ?> "> Voltar </a></p>

  <?
	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION["codInstanciaGlobal"] == "" )
	{
		echo "<p align='center'> <b>relato dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

	if (! relatoAcesso("ok", $_REQUEST["COD_RELATO"]) )
		exit();
	
	if ( isset($_REQUEST["ALTERAR"]))
	{
		$COD = relatoAltera($_REQUEST["COD_RELATO"], $_REQUEST["AUTOR"], $_REQUEST["TITULO"], $_REQUEST["TEXTO"],$_REQUEST["emConstrucao"]);

		if ($COD > 0)
		{
			echo "<script> location.href=\"./relato_mostrar.php?COD_RELATO=". $COD. "\";</script>";			
		 }
		else
		{
			echo "ERRO ao alterar Estudo de RELATO - <a href=\"javascript:history.back()\">Voltar</a>";
			exit();
		 }
	 }
	$RELATOTabela = relato($_REQUEST["COD_RELATO"]);
	
	if (! $RELATOTabela)
	{
		echo "Erro na consulta ao Banco de Dados";
		exit();
	 }
	 
	$RELATOInf = mysql_fetch_array($RELATOTabela);
?>	
		<form name="form" method="post" action="">
			<table border="0" cellspacing="15" cellpadding="0" style="width: 70%;">
			<tr>
				<td>
				<b>T�tulo do estudo:<?echo ativaDesativaEditorHtml(); ?></b><br>
					<input name="TITULO" style="width: 100%;" value="<?= $RELATOInf["TITULO"]?>">
				</td>
			</tr>
			<tr>
				<td>
					<b>Autor(es):</b><br>
					<select name="AUTOR[]" style="width: 100%; height: 100px;" multiple onClick=" linha.selected=true; linha.selected=false; user.selected = true; " >
					
					<?
						$professoresTurma = listaProfessores();
						$autoresRELATO      = relatoAutores($_REQUEST["COD_RELATO"]);
						
						while ($linhaProfessoresTurma = mysql_fetch_array($professoresTurma))
						{
							if ($linhaProfessoresTurma["COD_PESSOA"] == $_SESSION["COD_PESSOA"] )
							{
								echo "<option name=\"user\" id=\"user\" value=". $linhaProfessoresTurma["COD_PESSOA"] ." selected>". $linhaProfessoresTurma["NOME_PESSOA"] ."</option>";
							 }
							else
							{
								echo "<option value=". $linhaProfessoresTurma["COD_PESSOA"];
																
								while ($linhaAutoresRELATO = mysql_fetch_array($autoresRELATO))
								{
									if ($linhaProfessoresTurma["COD_PESSOA"] == $linhaAutoresRELATO["COD_PESSOA"])
									{
										echo " selected";
										break;
									 }										
								 }
								
								if ($autoresRELATO)
									mysql_data_seek($autoresRELATO, 0); 
								
								echo ">". $linhaProfessoresTurma["NOME_PESSOA"] . "</option>";
							 }
							
						 }

						echo "<option id=\"linha\" value=0 >--------------------------------------------------</option>";
												
						$alunosTurma = listaAlunos();
						$autoresRELATO = relatoAutores($_REQUEST["COD_RELATO"]);
						
						while ($linhaAlunosTurma = mysql_fetch_array($alunosTurma))
						{
							if ($linhaAlunosTurma["COD_PESSOA"] == $_SESSION["COD_PESSOA"])
							{
								echo "<option name=\"user\" id=\"user\" value=". $linhaAlunosTurma["COD_PESSOA"] ." selected>". $linhaAlunosTurma["NOME_PESSOA"] ."</option>";
							 }
							else
							{
								echo "<option value=". $linhaAlunosTurma["COD_PESSOA"] ;
								
								while ($linhaAutoresRELATO = mysql_fetch_array($autoresRELATO))
								{
									if ($linhaAlunosTurma["COD_PESSOA"] == $linhaAutoresRELATO["COD_PESSOA"])
									{
										echo " selected";
										break;
									 }									
								 }
								
								if ($autoresRELATO)
									mysql_data_seek($autoresRELATO, 0); 
								
								echo ">". $linhaAlunosTurma["NOME_PESSOA"] ."</option>";
							 }
						 }
					?>
					</select>
					Para selecionar os autores dentre os alunos dessa turma: <br><br>
                    Mantenha pressionada a tecla 'CTRL' e escolha os autores 
                    com o mouse. 
					
				</td>
			</tr>
			<tr>
				<td>
			
					<b>Texto:</b><br>
				
					<textarea name="TEXTO" style="width: 100%; height: 100px;"><?= $RELATOInf["TEXTO"]?></textarea>
				</td>
			</tr>
			<tr>
				<td align="right">
					
					<input type="hidden" value="ok" name="ALTERAR">
					<input type="hidden" value="<?= $_REQUEST["COD_RELATO"]?>" name="COD_RELATO">
					<?
					echo "<input type=\"button\" value=\"Deixar Em Constru��o\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=1'; submit();\">&nbsp".
					"<input type=\"button\" value=\"Validar/Entregar\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=0';submit();\">&nbsp";
					?>
					<input type="reset" value="Cancelar" onclick="javascript:history.back()">
				</td>
			</tr>
			</table>
			</form>
	
				  </td>
                </tr>
              </table>				
			</td>
		</tr>
	</table>			
</body>
</html>
