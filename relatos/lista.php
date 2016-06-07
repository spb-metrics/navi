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


include_once("../config.php");
include_once ($caminhoBiblioteca."/relato.inc.php")?>
session_name(SESSION_NAME); session_start(); security();
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

		 
		

	    <td width="740" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>
  <?php
	
	
	if (($_SESSION["COD_ADM"] <> "") or ($_SESSION["COD_PROF"] <> "") or ($_SESSION["COD_AL"] <> "") )
		$rsCon = listaRelato($_REQUEST["ORDER"]);
	else
		echo "<p align='center'> <b>Casos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		
		if($rsCon)
			echo " <p align='center'> <b>N&atilde;o h&aacute; relatos dispon&iacute;veis no momento.</b> <br><br> </p>"	;				
		else{
				echo "<p align='center'>";
				echo 		"<b>Relatos dispon&iacute;veis:</b> <br><br>";			
			$ant = 0;
			if ($_REQUEST["ORDER"]="nome")
			{
				while ($linha = mysql_fetch_array($rsCon))
				{
					if ($ant <> $linha["NOME_PESSOA"])
						{ 
						echo "</p><p align=\"justify\">".
										"<b><font style=\"text-transform: capitalize;\">" . strtolower($linha["NOME_PESSOA"]) . "</font></b>";
						echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=\"./mostra.php?COD_RELATO=" . $linha["COD_RELATO"] . "\">".
										$linha["TITULO"].
										"</a>";
						$ant = $linha["NOME_PESSOA"];
						}
					else
						echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=\"./mostra.php?COD_RELATO=" . $linha["COD_RELATO"]. "\">".
										$linha["TITULO"].
										"</a>";
			
				 }	
				 echo "</p><p align='center'><a href=\"?ORDER=titulo\">Ordenar por Titulo </a></p>";
			}
				
			else
			{
					while ($linha = mysql_fetch_array($rsCon))
						{
					if ($ant <> $linha["COD_RELATO"])
						{ 
						echo "</p><p align=\"justify\">".
										"<a href='./mostra.php?COD_RELATO=" .$linha["COD_RELATO"] . "'>".
										$linha["TITULO"].
										"</a>";
						echo "<br>.nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strtolower($linha["NOME_PESSOA"]) . "</font>";
						$ant = $linha["COD_RELATO"];
						}
					else
						echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strtolower($linha["NOME_PESSOA"]) . "</font>";
					
												
						}
				echo "</p><p align='center'><a href=\"?ORDER=nome\">Ordenar por Nome </a></p>";
			}

		}
		
		if ($_SESSION["COD_AL"] <> "")
			{ 
				echo "</p><p align='center'><a href=\"./envia_relato.php\">Enviar seu Relato </a></p>";			

				echo "<p align='left'><a href=\"./index.php\"><b>< VOLTAR</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
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
