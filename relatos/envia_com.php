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
 include_once ("../config.php");
include_once ($caminhoBiblioteca."/relato.inc.php");?>
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

		<td width="200" valign="top"> 
			<?include("../noticias_menu_esq_turma.php");?>
		</td>
		
	    <td width="540" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>

			<p align="right"><a href="./mostra.php?COD_RELATO=<?=$_REQUEST["COD_RELATO"]?>"> Voltar </a></p>

  <?php
	
	if (!(($_SESSION["COD_ADM"] <> "") or ($_SESSION["COD_PROF"] <> "") or ($_SESSION["COD_AL"] <> "")))
		echo "<p align='center'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
	else{
		if ($_REQUEST["COM"]<>"")
			{
			if RelatoEnviaCom($_REQUEST["COD_RELATO"],server.htmlencode($_REQUEST["COM"])){
				echo "<script>location.href=\"mostra.php?COD_RELATO=". $_REQUEST["COD_RELATO"]."\"</script>";}
			else
				echo "ERRO na Inser��o<br>".
							"<a href=\"javascript:history.back()\">Voltar</a>"
				
			}
		}

?>	
			<form name="form" method="post" action="">
			<p>
				<b>Comentario :</b><br>
				<textarea name="com" style="width: 60%; height: 100px;"></textarea>
			</p>	
			<p>
				<input type="hidden" name="COD_RELATO" value="<?=$_REQUEST["COD_RELATO"]?>">
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
