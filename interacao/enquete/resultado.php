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


//include_once("./../../funcoes_bd.php");
include_once("./../../config.php");
include_once($caminhoBiblioteca."/enquete.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>ENQUETE</title>
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
     
		<td colspan="2" valign="top"> 		
	
			<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >		
		
				<?php							

				if (($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == ""))
				{
					echo"<p align='center'> <b>Enquetes dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
					exit();
				}	

				$rsCon  = enquete($_REQUEST["COD_ENQUETE"], "", 0);	
				$rsCon2 = enquete($_REQUEST["COD_ENQUETE"], "", 1);					
	
				if ( $rsCon )
				{
								if ( $linha = mysql_fetch_array($rsCon) )
								{					
									
									echo "<tr>\n".
										 "	<td align='center' width='650'>\n".
										 "		<font size='2'> <b>" . $linha["TEXTO_ENQUETE"] . "</b> </font> <br>\n".
										 "	</td>\n".
										 "	<td align='right'>\n".
										 "		<a href='./index.php'>Voltar</a>\n".
										 "	</td>\n".
										 "</tr>\n".
										 "<tr>\n".
										 "	<td>\n".
										 "		<br>\n";
								}		
										
						
									$tamanho = 400;
									$barra   = 0;
						
								while ( $linha )
								{
									$barra = ($barra + 1);
									
									if ($barra > 9 ) 
										$barra = 1;
									
									$total       = 0;
									$total2		 = 0;							
									$quantidade  = 0;
									$porcentagem = 0;
									
										while ( $linha2 = mysql_fetch_array($rsCon2) )	{
											
											$total = ($total + 1);
			
											if ( $linha["COD_RESPOSTA"] == $linha2["COD_RESPOSTA"] )
											
												$quantidade = ($quantidade + 1);
										       
										     
										 }
									if ( mysql_num_rows($rsCon2) > 0 )
										mysql_data_seek($rsCon2, 0);
									
									if ( $total > 0 )
									{
										$porcentagem = ($quantidade*100/$total);
										$quantidade = (int)(( $quantidade * $tamanho ) / $total);
										$total2 = $total2 + $total;
									 }
									
									if ( $quantidade != 0 )
										$src = "./images/barra" . $barra . ".gif";
									else
										$src = "";
									
									echo "<table border='0' cellspacing='0' cellpadding='0' width='". $tamanho ."'>" . "\n".
										 "<tr>" . "\n".
										 "	<td>" . "\n".
										 "		<br>\n<b>" . $linha["TEXTO_RESPOSTA"] . "</b>" . "\n".
										 "	</td>" . "\n".
										 "</tr>" . "\n".
										 "<tr>" . "\n".
										 "	<td>" . "\n".
									    "   <table style='border:1px #000000 solid;' cellspacing='0' cellpadding='0' width='". $tamanho ."'>" . "\n".									
									    // "   <table border='1' bordercolor=#CCCCCC cellspacing='0' cellpadding='0' width='". $tamanho ."'>" . "\n".									
										 "	  <tr>" . "\n".
										 "	   <td>" . "\n".
										 "		<img src='" . $src . "' width='". $quantidade ."' height='15'>" . "\n". // MOSTRA A BARRA
										 "	   </td>" . "\n".
										 "	  </tr>" . "\n".
										 " 	 </table>" . "\n".
										 "	</td> " . "\n".
										 "  <td>" . "\n".
										 "	 <table border='0'>" . "\n".
										 "	  <tr>" . "\n".
										 "	   <td valign=middle>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . "\n". // MOSTRA A PORCENTAGEM QUE VOTOU
										 "		<b>" .number_format($porcentagem, 2, ',', ' '). "% </b>". "\n".
										 "	   </td>" . "\n".
										 "	  </tr>" . "\n".
										 "	 </table>" . "\n".
										 "	</td>" . "\n".
										 "</tr>" . "\n".
										 "</table>" . "\n";
									$linha = mysql_fetch_array($rsCon);
								}
							echo "<br>TOTAL DE VOTOS: " . $total2;
				}
					?>
		  </table>
		</td>		 
	</tr>
  </table>
 </body>
</html>
