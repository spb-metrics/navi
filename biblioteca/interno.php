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


include('../config.php');
include($caminhoBiblioteca."/arquivo.inc.php");

session_name(SESSION_NAME); session_start(); security();
include($caminhoBiblioteca."/functionsDeEdicao.inc.php");

$titleEditar='Permite visualizar/aditar todos os seus arquivos do acervo.';
$titleCriar='Criar um novo �tem no acervo.';


echo '<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body>';

	
if ( isset($_REQUEST["COD_TIPO_ITEM_BIB"]) ) {
  $COD_TIPO_ITEM_BIB = $_REQUEST["COD_TIPO_ITEM_BIB"];
}
else {
  $COD_TIPO_ITEM_BIB = "";
}

if ( $COD_TIPO_ITEM_BIB == "" ) {
  $rsCon = biblioteca("");
  
  if ( $rsCon ) {
  	
  	if ( $linha = mysql_fetch_array ($rsCon) ) 	{
  	  echo  "<p align='center'>".
  			  "		<b><font size='1'>Acervo</font></b>";
  					 editarCriar('acervo',$titleEditar,$titleCriar);
  		echo "		\nSelecione no menu &agrave; esquerda qual se&ccedil;&atilde;o voc&ecirc; deseja visualizar. ".
  			  "</p>";					  
  	 }
  	else 			{
  		echo  "<p align='center'>".
  			  "		<b><font size='1'>Acervo</font></b>";
  		editarCriar('acervo',$titleEditar,$titleCriar);
  		echo  "		N&atilde;o h&aacute; menhum item dispon&iacute;vel na biblioteca no momento.".
  			  "</p>";
  	}
  }
}
else {
  $rsCon = biblioteca($COD_TIPO_ITEM_BIB);
  		 editarCriar('acervo',$titleEditar,$titleCriar);
  
  echo "<table border='0'><hr><td width='15px'></td><td>";
  if ( $rsCon ) {  
  	while ( $linha = mysql_fetch_array($rsCon) )			{	
      echo  "<p align='left'>";
  
  		excluiAlteraNaInstancia('acervo', $linha["COD_ARQUIVO"],'COD_ARQUIVO',$linha["COD_TIPO_ACESSO"],$linha["COD_PESSOA"]);
  
  		if(($linha["COD_TIPO_ACESSO"]==3) AND (empty($_SESSION["COD_PESSOA"])))  {
  			echo   "		<a href=\"#\" onClick=\"javascript:window.open('mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "','', 'fullscreen=yes, scrollbars=none');\">" . $linha["DESC_ARQUIVO_INSTANCIA"] . "</a> ";
  		}
      else {     
  			if(!empty($_SESSION["COD_PESSOA"])) {  		                              
  		    echo  "		<a href=\"#\" onClick=\"javascript:window.open('mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "','', 'fullscreen=yes, scrollbars=none');\">" . $linha["DESC_ARQUIVO_INSTANCIA"] . "</a> ";  		
  			}
  		}
  		echo  "</p>";    
  	}
  }
  echo "</td></hr></table>";
}
	 
 
echo '</body></html>';

?>
