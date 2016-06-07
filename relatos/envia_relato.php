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


include_once ("../config.php");
include_once ($caminhoBiblioteca."/relato.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {
  echo 'Sem permissao de interacao.'; die;
}
?>
<html>
	<head>
		<title>NAVi - N.uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">


		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body bgcolor="#FFFFFF" text="#000000">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
    <td width="740" valign="top" align="center"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>

			<p align="right"><a href="./index.php"> Voltar </a></p>

  <?php
 	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION["codInstanciaGlobal"] == "")
	{
		echo "<p align='center'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
		}
	if(isset($_REQUEST["RELATOENVIADO"]))
	{
		if ($_REQUEST["TITULO"]<>"")
			{
				$COD = RelatoEnvia(($_REQUEST["TITULO"]),($_REQUEST["TEXTO"]));
			
				if ($COD>0)
					{
						echo "<script> location.href=\"./mostra.php?COD_CASO=". $COD."\";</script>";
					 }
					else
					{
						echo "ERRO na Inserção<br> <a href=\"javascript:history.back()\">Voltar</a>";
						exit();
					 }
			}
			else
				echo "<script> alert('O Estudo de Caso deve possuir um Título'); </script>";
	}
?>	
			<form name="form" method="post" action="">
			<table border="0" cellspacing="15" cellpadding="0" style="width: 70%;">
			<tr>
				<td>
					<b>Título do relato:</b><br>
					<input name="TITULO" style="width: 100%;">
				</td>
			</tr>
			<tr>
				<td>
					<b>Autor(es):</b><br>
					<select name="AUTOR[]" style="width: 100%; height: 100px;" multiple onClick="linha.selected=true; linha.selected=false; user.selected = true;">
					<?php
					
						$rsCon = listaProfessores();
							
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

						echo "<option id=\"linha\" value=0 >--------------------------------------------------</option>";

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
				</td>
			</tr>
			<tr>
				<td>
					<b>Texto:</b><br>
					<textarea name="TEXTO" style="width: 100%; height: 300px;"></textarea>
				</td>
			</tr>
			<tr>
				<td align="right">
					<input type="hidden" name="RELATOENVIADO" value="casoEnviado">
					<input type="submit" value="Enviar">
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
