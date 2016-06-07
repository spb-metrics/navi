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
include_once("../config.php");
include_once($caminhoBiblioteca."/arquivo.inc.php");
include_once($caminhoBiblioteca."/acervo.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body>

<table width="150" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 
		
			<table width="150" height='100%' border="0" cellpadding="0" cellspacing="0" >
				<tr> 
					<td>
	
						<?php
						//if  ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) )
						
 

						if(existeMsgAulaInterativa()){
						$classe = "CelulaEscura";

									echo  "<tr>".
										  "		<td class=\"". $classe ."\">".
										  "			<a href='aula_interativa.php' target='main'>Aula Interativa</a>".
										  "		</td>".
										  "</tr>".
										  "<tr>".
										  "		<td height='10'> &nbsp; </td> ".
										  "</tr>";
						}	


						$rsCon = biblioteca("");
					
						if ( $rsCon )		{
							while ( $linha = mysql_fetch_array($rsCon) )
							{
								if ( $classe == "CelulaEscura" )
									$classe = "CelulaClara";
								else
									$classe = "CelulaEscura";
							    
									echo  "<tr>".
										  "		<td class='". $classe ."'>".
										  "			<a href='interno.php?COD_TIPO_ITEM_BIB=" . $linha["COD_TIPO_ITEM_BIB"] . "' target='main'>" . $linha["DESC_TIPO_ITEM_BIB"] . "</a>".
										  "		</td>".
										  "</tr>".
										  "<tr>".
										  "		<td height='10'> &nbsp; </td> ".
										  "</tr>";												
								

								
							 }							
						 }
						
					 	?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
