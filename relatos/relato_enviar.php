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
				  
			<p align="right"><a href="./relato_listar.php"> Voltar </a></p>

  <?php
	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION["codInstanciaGlobal"] == "")
	{
		echo "<p align='center' class='menu'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

	if (isset ($_REQUEST["RELATOENVIADO"]))
	{
		if ($_REQUEST["TITULO"] != "")
		{
		
			$COD = relatoEnvia($_REQUEST["AUTOR"], $_REQUEST["TITULO"], $_REQUEST["TEXTO"], $_REQUEST["emConstrucao"]);
			
			if ($COD > 0)
			{
				echo "<script> location.href=\"./relato_mostrar.php?COD_RELATO=". $COD. "\";</script>";
			 }
			else
			{
				echo "ERRO na Inserção<br> <a href=\"javascript:history.back()\">Voltar</a>";
				exit();
			 }
		 }
		else
		{
			echo "<script> alert('O Estudo de RELATO deve possuir um TÝtulo'); </script>";
		 }
	 }
 
?>
			<form name="form" method="post" action="relato_enviar.php">
			<table border="0" cellspacing="15" cellpadding="0" style="width: 70%;">
			<tr>
				<td>
					<p><b>TÝtulo do relato:<?echo ativaDesativaEditorHtml(); ?></b></p><br>
					<input name="TITULO" style="width: 100%;">
				</td>
			</tr>
			<tr>

					<b>Autor(es):</b><br>

					
					
					<select name="AUTOR[]" style="width: 100%; height: 100px;" multiple onClick=" linha.selected=true; linha.selected=false; user.selected = true; " > 
					
					 <!--<select name="AUTOR[]" style="width: 100%; height: 100px;" multiple onClick="" > -->


					<?php
					
						$rsCon = listaProfessores();
													
						if ($rsCon)
						{				
							while ($linha = mysql_fetch_array($rsCon))
							{
								if ($linha["COD_PESSOA"] == $_SESSION["COD_PESSOA"])
									echo "<option name=\"user\" id=\"user\" value=". $linha["COD_PESSOA"] ." selected>". $linha["NOME_PESSOA"] ."</option>";
								else 
									echo "<option 			value=". $linha["COD_PESSOA"] .">". $linha["NOME_PESSOA"] ."</option>";
							 } 
						 }

						echo "<option name=\"linha\" id=\"linha\" value=0 >--------------------------------------------------</option>";

						$rsCon = listaAlunos();
						
						if ($rsCon)
						{
							while ($linha = mysql_fetch_array($rsCon))
							{
								if ($linha["COD_PESSOA"] == $_SESSION["COD_PESSOA"])
									echo "<option id=\"user\" value=". $linha["COD_PESSOA"] ." selected>". $linha["NOME_PESSOA"] ."</option>";
								else 
									echo "<option 			value=". $linha["COD_PESSOA"] .">". $linha["NOME_PESSOA"] ."</option>";							
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
				<b>TEXTO:</b>
				
					<br>

					<textarea name="TEXTO" style="width: 100%; height: 100px;"><?=$_REQUEST["texto"];?></textarea>

          <!-- COLOCAR DEPOIS
					<input type="file" name="ARQUIVO_NOVO" size="60" >
					 <br>
					<font color="red">Obs.:</font>O seu arquivo deve ser obrigatµriamente um arquivo de extensÒo .txt
          -->
				</p>	
			
				</td>
			</tr>
			
				<td align="right">
					<input type="hidden" name="RELATOENVIADO" value="relatoEnviado">&nbsp;
					<?
					echo "<input type=\"button\" value=\"Deixar Em ConstruþÒo\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=1'; submit();\">&nbsp".
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
