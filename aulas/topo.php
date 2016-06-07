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

//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Texto</title>	
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="../cursos.css" type="text/css">
	</head>
<body>

  <?php
       

	if ( ($_SESSION["codInstanciaGlobal"] == "") OR ($_REQUEST["COD_VIDEO"] == "") )
	{
		echo "Acesso Negado";
		exit();
	 }
	
	$rsCon = videoCaminho($_REQUEST["COD_VIDEO"],$_REQUEST["RESOLUCAO"]);
		
	if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) )
		exit();
		
	$linha = mysql_fetch_array($rsCon);
	if ( ($_SESSION["COD_PESSOA"] != "") and ($linha["COD_TIPO_ACESSO"] == 1) )
		exit();
			  
   
	

		?>
			<table width="700" align="center" >
				<tr>
					<td align="center" width="500">										
						<b> <?= $linha["DESC_VIDEO_INSTANCIA"] ?> </b>
					</td>
					<td align="right">
						<a class="menu" href="javascript:parent.location.href='./index.php'" >Fechar video</a>
					</td>
				</tr>
			</table>										
</body>
</html>