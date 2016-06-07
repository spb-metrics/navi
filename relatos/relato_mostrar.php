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
include_once ($caminhoBiblioteca."/forum.inc.php");
include_once ($caminhoBiblioteca."/miscelania.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
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

  <?php

	if ( ($_SESSION["COD_ADM"] == "") AND ($_SESSION["COD_PROF"] == "") AND ($_SESSION["COD_AL"] == "") )
	{
		echo "<p align='center' class='menu'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

	if (! isset($_REQUEST["COD_RELATO"]))
		exit();
	
	if (! relatoAcesso("", $_REQUEST["COD_RELATO"]) )
		exit();
	
	$rsCon = relato($_REQUEST["COD_RELATO"]);

	if (! $rsCon)
		exit();
	
	$linha = mysql_fetch_array($rsCon);
	
	echo "<p><b>Título do estudo: </b>" . str_replace("\n", "<br>", $linha["TITULO"]) . " </p>";
	
  //========================Exibe data da postagem do arquivo =========================================================
  echo "<p><b>Data da postagem: </b>". $linha["DATA_MODIFICADA"] ."</p>"; 
	//=================================================================================
  
  echo "<p><b>Autor(es): </b>";
	
	$rsCon2 = relatoAutores($_REQUEST["COD_RELATO"]);
	
	if ($rsCon2)
		while ($linha2 = mysql_fetch_array($rsCon2))
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strToLower($linha2["NOME_PESSOA"]) . "</font>";

	echo "</p><p><b>Texto: </b><br>"; 
	echo str_replace("\n", "<br>", $linha["TEXTO"]);

	
	$rsCon2 = relatoComentario($_REQUEST["COD_RELATO"]);
			
	if ($rsCon2)
	{
		echo "</p><p><b>Comentarios: </b></p>";

		while ($linha2 = mysql_fetch_array($rsCon2))
		{
			$imagemProf=verificaSePessoaProfessor($linha2["COD_PESSOA"], $_SESSION["codInstanciaGlobal"]);
			$imagemProf=mysql_fetch_array($imagemProf);
			echo "<p align=\"justify\" style=\"background-color: #ededed\">". preparaTexto($linha2["TEXTO"])."<br><div align=Right><b>";

			if($imagemProf)
			{
				echo "<img src=\"../alunos/tipoProfessor.php?codTipoProfessor=".$imagemProf["codTipoProfessor"]."\" max-height=\"40\" max-width=\"60\" title='".$imagemProf["descTipoProfessor"]."'>";
			//echo Professor::iconeTipoProfessor($imagemProf["codTipoProfessor"],$imagemProf["descTipoProfessor"]);
			}


		
			echo  $linha2["NOME_PESSOA"] ." - ". $linha2["DATA_MODIFICADA"]  ."</b></div></p>";
		 }
	 }
?>	
			<p align="center"><a href="./relato_comentario_enviar.php?COD_RELATO=<?=$linha["COD_RELATO"]?>"> Enviar seu comentario</a></p>
			<br><br><br><br>
				  </td>
                </tr>
              </table>			
		</td>
	</tr>
</table>
 
 </body>

</html>
