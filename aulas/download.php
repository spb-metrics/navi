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


//include_once("../funcoes.bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security(0,1);

global $caminho;
$rsCon =  videoCaminho2($_REQUEST["COD_VIDEO"],$_REQUEST["RESOLUCAO"]);


$linha = mysql_fetch_array($rsCon);


if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) )
  exit();


if ( $_SESSION["COD_PESSOA"] == "" )
  exit();


		



if ($linha[$caminho[$_REQUEST["RESOLUCAO"]]]!= "" ) {

  $arq= $$linha[$caminho[$_REQUEST["RESOLUCAO"]]];
  

  /*
  if( (substr($arq,0,4))== "http") { header("Location: " . $arq );}
  else {
	$arq = $caminhoVideo.$linha["CAMINHO_HTTP_VIDEO"];
  if (file_exists($arq) ){
		$caminho_video = explode("\\", $arq);
		$nome_video = $caminho_video[count($caminho_video)-1];
       
   }
    else {
        echo "<!-- ".$arq. " -->";
        exit();
        
    }
   */
 
	$arq = $caminhoVideo.$linha[$caminho[$_REQUEST["RESOLUCAO"]]];	
	$caminho_video = explode("/", $arq);
	$nome_video = $caminho_video[count($caminho_video)-1];


  //flush();	

  /*header("Content-type: video/x-ms-wmv");

  header("Content-Disposition: attachment; filename=" . $nome_video );*/


  header("Cache-Control: public"); 
  header("Content-Description: File Transfer");	
  header("Content-type: application/force-download");
  header("Content-type: video/x-ms-wmv");
  header("Content-Disposition: attachment; filename=". $nome_video ); 
  header("Content-Transfer-Encoding: binary");  

  flush();
  readfile($arq);
    
  exit();

}
else {
  echo "Houve um problema ao fazer o download. Contate o suporte tecnico.";
}

?>
