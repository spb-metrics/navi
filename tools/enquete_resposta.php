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
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/enquete.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Enquete</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		
	</head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">
<?php

if ( $_REQUEST["OPCAO"] == "Inserir" )
		$texto_resposta = "";
		
switch ($_REQUEST["OPCAO"])
{ 
	case "Inserir":
?>
		<table cellpadding="10" cellspacing="0" border="0" width="90%"  align="center">
		<form name="ENQUETE_RESPOSTA" method="post"  action="enquete_envio.php?OPCAO=<?=$_REQUEST["OPCAO"]?>">

		<tr>
			<td>
			<?php	
			$texto_enquete=$_REQUEST["TEXTO_ENQUETE"];
			$cod_enquete=$_REQUEST["COD_ENQUETE"];
			echo "<br>&nbsp;<b>$texto_enquete </b> <br><br>";
				
			?>
			</td>
		</tr>
		<tr>

			<td>
			<br>&nbsp;<b>Adicione uma alternativa a enquete: </b> <br><br>	
			</td>
		</tr>	
	<tr>
		<td>
		
		<script language="JavaScript">
			function CountLen(obj) 
			{
				if(obj.value.length > 400) {
					alert("N�mero m�ximo de caracteres excedido em " + (obj.value.length -400) + " caracteres.");
					obj.value = obj.value.slice(0,400);
				}
			}
		</script>
		<textarea name="TEXTO_RESPOSTA" value="<?=$texto_resposta?>" size="80"  cols="80" rows="3" onBlur="javascript:CountLen(this)"></textarea>
		</td>
	</tr>
	<tr>
		<td>
		<input type="submit" name="Submit" value="<? if ( $_REQUEST["OPCAO"]=="Remover") echo "Excluir"; else echo "Inserir";?>">
		<input type="hidden" name="COD_ENQUETE" value="<?=$cod_enquete?>">
		</td>
</form>
		
</table>

<?php
					
}
?>
</body>
</html>
