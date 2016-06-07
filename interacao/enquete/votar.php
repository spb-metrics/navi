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


include("./../../config.php");
include($caminhoBiblioteca."/enquete.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>Enquete</title>
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 

		<td colspan="2" valign="top"> 		
	
			<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >		
		
				<?php							
				if ( ($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == "") )
				{
					echo "<p align='center'> <b>Enquetes dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
					exit();
				}
				else if ($_SESSION['userRole']!=ALUNO) {
 					echo "<p align='center'> <b>A resposta a enquetes � dispon&iacute;vel apenas para alunos.</b> </p>";
					exit();
        }
			//Verifica se o aluno j� votou

				$rsCon = enquete($_REQUEST["COD_ENQUETE"], "", 1);	
        
				if ( $rsCon ) {
					if ( $linha = mysql_fetch_array($rsCon)) {
						while ( $linha )	{
							if ($_SESSION["COD_AL"] == $linha["COD_AL"])	{					
  							echo "	<tr>".
  								 "	<td align='center' width='650'>\n".
  								 "		<font size='2'><b>Voc� j� votou nesta enquete.</b></font>\n".
  								 "		<br><br><br>\n".
  								 "		A sua resposta foi: &nbsp;<b>" . $linha["TEXTO_RESPOSTA"]. "</b>\n".
  								 "	</td>\n".
  								 "	<td align='right'>\n".
  								 "		<a href='./index.php'>Voltar</a>\n".
  								 "	</td>\n".
  								 "	</tr>\n";								 
								exit();
							}
							$linha = mysql_fetch_array($rsCon);
					  }
				  }
				}
//				Caso nao tenha votado ent�o retorna as op��es para que ele vote
				$rsCon = enquete($_REQUEST["COD_ENQUETE"], "", 0);	
					
				if ( mysql_num_rows($rsCon) > 0 )
					if ($linha = mysql_fetch_array($rsCon)) {					
						
						echo "<tr> <td align='center' width='650'> <font size='2'> <b>" . $linha["TEXTO_ENQUETE"] . " </b> </font> <br> </td> <td align='right'> <a href='./index.php'>Voltar</a> </td> </tr>";
						echo "<tr> <td> <BR> <BR>";					
						echo "<table align='center'> <form name='form1' method='post' action='./enviar_resposta.php'>";						
							
						while ( $linha ) {														
							echo " <tr> <td> <input type='radio' name='COD_RESPOSTA' value='" . $linha["COD_RESPOSTA"] . "'></td>";
							echo " <td> &nbsp; &nbsp; &nbsp; <font size='2'> <b>" . $linha["TEXTO_RESPOSTA"] . " </b> </font> <BR> </td> </tr>";		
							
							$linha = mysql_fetch_array($rsCon);						
					  }

						echo " <input type='hidden' name='COD_ENQUETE' value='" . $_REQUEST["COD_ENQUETE"] . "'> </td>";
						echo " <tr> <td></td> <td align='center'> <BR> <input type='Submit' name='SUBMIT' value='Enviar Resposta'>  </form> </td> </tr> </table>";

						echo "</td> <td> </td> </tr> ";
					 }
           	
				  ?>
	  	
			</table>
		</td> 
	</tr>
</table>
</body>
</html>
