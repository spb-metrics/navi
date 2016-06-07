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
