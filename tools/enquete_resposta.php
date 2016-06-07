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
					alert("Número máximo de caracteres excedido em " + (obj.value.length -400) + " caracteres.");
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
