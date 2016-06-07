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
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start();
if (($_SESSION['userRole'] == PUBLICO) || (empty($_SESSION['userRole']))) {$sp = 1;} else {$sp = 0;}
security($sp);
include_once($caminhoBiblioteca."/functionsDeEdicao.inc.php");
$titleEditar='Permite visualizar/editar todos os seus v�deos.';
$titleCriar='Criar um novo v�deo.';
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<Body>

<?php

if ($_SESSION["codInstanciaGlobal"] == "")
	exit();

if ($_SESSION["COD_PESSOA"] != "")
	$acesso = 2;  //privado
else
	$acesso = 3;
	
$rsCon = videoAulas($acesso);
	
if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) )		{
  msg("N&atilde;o h&aacute; v&iacute;deo-aulas dispon&iacute;veis no momento.");
  editarCriar('videos',$titleEditar,$titleCriar);
  exit;
}		

if ($_SESSION["COD_PESSOA"] != "") {
	msg("V&iacute;deo-aulas dispon&iacute;veis:");
}
else {
	msg("V&iacute;deo-aulas dispon&iacute;veis para visitante:");
}


editarCriar('videos',$titleEditar,$titleCriar);

echo "<br>";
echo "<table width=\"100%\" align=\"center\" cellspacing=\"0\" style=\"background-color:#000000\"><tr><td>";
echo "<table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"1\">".
	"<tr align=\"center\" class=\"CelulaEscura\"><td width=\"40%\">&nbsp;</td><td colspan=\"2\" width=\"29%\"><p class=\"menu\" align=\"center\">Assista<img src=\"assista.gif\" border=\"no\"></p></td><td colspan=\"3\"><p class=\"menu\" align=\"center\" width=\"27%\">Fa�a Download<img src=\"download.gif\" border=\"no\"></p></td></tr>".
	"<tr align=\"center\" class=\"CelulaClara\"><td>&nbsp;</td><td>Banda Larga<br>(aprox. 185kbps)</td><td>Linha Discada<br>(aprox. 50kbps)</td><td>Banda Larga<br>(aprox.185kbps)</td><td>Linha Discada<br>(aprox.50kbps)</td></tr>";
	$celula="CelulaClara";
	
	
while ($linha = mysql_fetch_array($rsCon))		{
  /*
  //Verifica se nao foi colocad o link
	if( (substr($linha["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"],0,4))== "http") { 
	  echo "<a href=\"". $linha["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"]. "?COD_VIDEO=" . $linha["COD_VIDEO"] . "\"  target=_blank > " . $linha["DESC_VIDEO_INSTANCIA"] . "</a>";
	  echo"</td></tr>";							 
	}*/
	
  if($celula=="CelulaClara") {
	  $celula="CelulaEscura";
  }
	else {
    $celula="CelulaClara";
  }

	echo "<tr align=\"center\" class=\"".$celula."\"><td align=\"center\" >";
			excluiAlteraNaInstancia('videos', $linha["COD_VIDEO"],'COD_VIDEO', $linha["COD_TIPO_ACESSO"], $linha['COD_PESSOA']);

	echo "<b>".$linha["DESC_VIDEO_INSTANCIA"]."</b></td>";


  //VIDEO DE ALTA RESOLUCAO  
  if($linha["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"]=="") {
    echo"<td class=\"perfil\">N�o disponibilizado</td>";
  }
  else {
    echo "<td>";
    //se for link http entao somente monta o link
  	if( (substr($linha["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"],0,4))== "http") { 
  	  echo "<a href=\"". $linha["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"]. "?COD_VIDEO=" . $linha["COD_VIDEO"] . "\"  target=_blank > ";
    }
    else { //se nao entao mos 
    	echo "<a href='./mostrar.php?RESOLUCAO=alta&COD_VIDEO=" . $linha["COD_VIDEO"] . "'>";
    }
    echo "<img src=\"banda_larga.gif\" border=\"no\"></a></td>";
  }

  //VIDEO DE BAIXA RESOLUCAO  
  if($linha["CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO"]=="") {
    echo"<td class=\"perfil\">N�o disponibilizado</td>";
  }
  else {
    echo "<td>";
    //se for link http entao somente monta o link
  	if( (substr($linha["CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO"],0,4))== "http") { 
  	  echo "<a href=\"". $linha["CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO"]. "?COD_VIDEO=" . $linha["COD_VIDEO"] . "\"  target=_blank > ";
    }
    else { //se nao entao mos 
    	echo "<a href='./mostrar.php?RESOLUCAO=baixa&COD_VIDEO=" . $linha["COD_VIDEO"] . "'>";
    }
    echo "<img src=\"linha_discada.gif\" border=\"no\"></a></td>";
  }
  
	            
	if($linha["DOWNLOAD"]=="1")	{
    if($linha["CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO"]!="") {
				echo "<td ><a href='./download.php?RESOLUCAO=alta&COD_VIDEO=" . $linha["COD_VIDEO"] . "'>".
					 "<img src=\"banda_larga.gif\" border=\"no\">".
					 "</a></td>";
    }
		else {
      echo"<td class=\"perfil\">N�o disponibilizado</td>";
    }
		if($linha["CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO"]!="") {
		  echo "<td><a href='./download.php?RESOLUCAO=baixa&COD_VIDEO=" . $linha["COD_VIDEO"] . "'>".
					 "<img src=\"linha_discada.gif\" border=\"no\">".
					 "</td></a>";
    }
		else {
      echo"<td class=\"perfil\">N�o disponibilizado</td>";
    }
	}
	else {
    echo "<td colspan=\"4\"  class=\"perfil\">N�o disponibilizado para Download</td>";
  }
   
}
 
echo"</tr></table></table>";	
		
?>
</body>
</html>
