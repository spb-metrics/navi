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


include_once ("../config.php");
include_once ($caminhoBiblioteca."/relato.inc.php");
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
    <td width="740" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>

			<p align="right"><a href="./lista.php"> Voltar </a></p>

  <?php
	
	if(($_SESSION["COD_ADM"] == "") AND ($_SESSION["COD_PROF"] == "") AND ($_SESSION["COD_AL"] == "")) 
		{
			echo "<p align='center'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
			exit();
		}

	
	$rsCon = Relato($_REQUEST["COD_RELATO"]);
	if ($linha = mysql_fetch_array($rsCon))
	{
	
		echo "<p><b>Título do estudo: </b>" . $linha["TITULO"] . " </p>";
	
		echo "<p><b>Autor(es): </b>";
	
		$rsCon2 = RelatoAluno($_REQUEST["COD_RELATO"]);
		while($linha2 = mysql_fetch_array($rsCon2))
			{
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strtolower($linha2["NOME_PESSOA"]) . "</font>";
			}
	
		echo "</p><p><b>Texto: </b><br>" ;
		echo str_replace("\n","<br>",$linha["TEXTO"]);
		echo "</p><p><b>Comentarios: </b></p>";
	
		$rsCon2 = RelatoCom($_REQUEST["COD_RELATO"]);
		while( $linha2 = mysql_fetch_array($rsCon2))
			{
			echo  "<p align=\"justify\" style=\"background-color: #ededed\">". preparaTexto($linha2["TEXTO"]).
			  "<br><div align=Right><b>". $linha2["NOME_PESSOA"] ." - ". $linha2["DATA"]. "</b></div></p>";
			}
	}
?>	
			<p align="center"><a href="./envia_com.php?COD_RELATO=<?=$linha["COD_RELATO"]?>"> Enviar seu comentario</a></p>
			<br><br><br><br>
			
				  </td>
                </tr>
              </table>			
		</td>
	</tr>
</table>
</body>
</html>
