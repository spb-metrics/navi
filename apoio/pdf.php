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
include("../config.php");
include($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security(0,1);
//fecha a sessao assim q puder para bloquea-la o menos possivel
session_write_close();
$rsCon = apoioCaminho($_REQUEST["COD_ARQUIVO"]);
	
if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) )
  exit();

$linha = mysql_fetch_array($rsCon);
if ( ($_SESSION["COD_PESSOA"] != "") and ($linha["COD_TIPO_ACESSO"] == 1) )
  exit();

		
		
if ($linha["CAMINHO_LOCAL_ARQUIVO"]!= "" ) {
  $arq= $linha["CAMINHO_LOCAL_ARQUIVO"];
  if( (substr($arq,0,4))== "http")  { 
    header("Location: " . $arq );
   }
  else { 
     $arq = $caminhoUpload.$linha["CAMINHO_LOCAL_ARQUIVO"];

     if ( ! file_exists($arq) ) {
       $arq = $caminhoUpload1. $linha["CAMINHO_LOCAL_ARQUIVO"];
       
     }
     $temp = explode("/", $arq);
     $nome = $temp[count($temp) - 1];
  }

  //flush();
  /* TESTE DE CONTROLE DO CABECALHO
  $filename=""; $linenum=0;
  if (headers_sent($filename, $linenum)) {
    echo "Os cabeçalhos já foram enviados em ". $filename ."na linha".$linenum;
  }
  else { 
    echo "os cabs ainda nao foram enviados!!"; 
  }
  exit;
  */
  header("Pragma: public");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

  if ($_REQUEST["download"]) {
    header("Content-Description: File Transfer");	
    header("Content-type: application/force-download");
    header("Content-type: ".$linha["TIPO_ARQUIVO"]);
    header("Content-Disposition: attachment; filename=". $nome ); 
    header("Content-Transfer-Encoding: binary");
  }
  else {
    /*
    echo "<PRE>"; print_r($linha);
    echo "-->"; echo $linha["TIPO_ARQUIVO"];
    
    echo "<br>==>".$nome; */
    header("Content-type: ".$linha["TIPO_ARQUIVO"]);
    header("Content-Disposition: inline; filename=". $nome);
  }
  set_time_limit(0);
  readfile($arq);
	
  //exit;
}
?>