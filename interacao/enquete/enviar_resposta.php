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


//include_once("./../../funcoes_bd.php");
include_once("./../../config.php");
include_once($caminhoBiblioteca."/enquete.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>F&Oacute;RUM</title>
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 		
			<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >		
				<?php
				if ( ($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == "") )
					exit();
				
					$cod_resposta = $_REQUEST["COD_RESPOSTA"];
					$cod_enquete  = $_REQUEST["COD_ENQUETE"];

				if ( $cod_resposta == "" ) 
					echo "<tr> <td align='center' width='650'> <font size='2'> <b> Voc� n�o escolheu nenhuma das alternativas. </b> </font> </td> <td align='right'> <a href='./votar.php?COD_ENQUETE=" . cod_enquete . "'>Voltar</a> </td> </tr>";											
				else
					{
					$rsCon = enquete($cod_enquete, $cod_resposta, "2");

					//echo "<tr> <td align='center' width='650'> <font size='2'> <b> Resposta enviada com sucesso </b> </font> </td> <td align='right'> <a href='./resultado.php?COD_ENQUETE=" . $cod_enquete . "'>Ver vota��o</a> <BR><BR> <a href='./index.php'>Ver enquetes</a> </td> </tr>";													
				  echo "<script>location.href=\"./resultado.php?COD_ENQUETE=" . $cod_enquete . "\"</script>";  
            }
				?>
	  	
			</table>
		</td> 
	</tr>
</table>
</body>
</html>
