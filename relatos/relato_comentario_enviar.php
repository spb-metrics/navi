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
session_name(SESSION_NAME); session_start(); security();
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
if (! isset($_REQUEST["COD_RELATO"]))
	$_REQUEST["COD_RELATO"] = "";
?>				  
				  
			<p align="right"><a href="./relato_mostrar.php?COD_RELATO=<?=$_REQUEST["COD_RELATO"]?>"> Voltar </a></p>

  <?
	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION["codInstanciaGlobal"] == "" )
	{
		echo "<p align='center' class='menu'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }
	
	if (isset ($_REQUEST["COM"]))
	{
		if (relatoEnviaCom($_REQUEST["COD_RELATO"], $_REQUEST["COM"]) )
		{
			echo "<script> location.href=\"./relato_mostrar.php?COD_RELATO=". $_REQUEST["COD_RELATO"]. "\";</script>";
		 }
		else
		{
			echo "ERRO na Inser��o<br> <a href=\"javascript:history.back()\">Voltar</a>";
			exit();
		 }
	 }
?>	
 <script language="JavaScript" type="text/javascript">initHtmlEditorCompleto('200'); </script>
			<form name="form" method="post" action="">
			<p>
				<b>Comentario :</b><br>
				<textarea name="COM" style="width: 60%; height: 100px;"></textarea>
			</p>	
			<p>
				<input type="hidden" name="COD_RELATO" value="<?= $_REQUEST["COD_RELATO"] ?>">
				<input type="submit" value="Enviar">&nbsp;
				<input type="reset" value="Cancelar" onclick="javascript:history.back()">
			</p>
			</form>
	
				  </td>
                </tr>
              </table>	
			</td>
		</tr>
	</table>			
</body>
</html>
