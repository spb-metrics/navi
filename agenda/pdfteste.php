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
include_once($caminhoBiblioteca."/agenda.inc.php");
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();
//fecha a sessao assim q puder para bloquea-la o menos possivel
session_write_close();  
$codArquivo=$_REQUEST['COD_ARQUIVO']; 
   
$arquivo=getArquivo($codArquivo);
while ($row = mysql_fetch_array($arquivo, MYSQL_BOTH)) { 
 $caminho=$row["CAMINHO_LOCAL_ARQUIVO"];
 $tipo=$row["TIPO_ARQUIVO"];
 //$nome=$row["DESC_ARQUIVO"];
}   

if( (substr($caminho,0,4))== "http" ) { 
 header("Location: " . $caminho ); exit; 
}
else if( (substr($caminho,0,4))== "www." ) { 
 header("Location: http://" . $caminho ); exit; 
}


$arq = $caminhoUpload.$caminho;
if ( ! file_exists($arq) ) {
 $arq = $caminhoUpload1. $caminho;
 
}


$temp = explode("/", $arq);
$nome = $temp[count($temp) - 1];
echo $arq;
echo "<br>NOME:".$nome; echo "TIPO:".$tipo;  die();
 
header("Pragma: public");   
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

if ($_REQUEST["download"]) {
  header("Content-Description: File Transfer");	
  header("Content-type: application/force-download");
  header("Content-Disposition: attachment; filename='". $nome ."'"); 
  header("Content-Transfer-Encoding: binary");
}
else {
  header("Content-type: ".$tipo);
  header("Content-Disposition: inline; filename=". $nome);
}

set_time_limit(0);

//readfile($arq);
	
//exit;
//}
?>
