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
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security(0,1);

	$rsCon = apoioCaminhoAluno($_REQUEST["COD_ARQUIVO"],$_REQUEST["COM"]);
	
	if ( (! $rsCon)  )
	{
		exit();
	}

	$linha = mysql_fetch_array($rsCon);
if ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) ){
		exit();
		}
	
if ($linha["CAMINHO_LOCAL_ARQUIVO"]!="" )
	{
	    $arq= $caminhoUpload .$linha["CAMINHO_LOCAL_ARQUIVO"];
		if ( file_exists($arq) ) {
			$temp = explode("/", $arq);
			$nome = $temp[count($temp) - 1];
		}else{
	        $arq= $caminhoUpload1 .$linha["CAMINHO_LOCAL_ARQUIVO"];
			$temp = explode("/", $arq);
			$nome = $temp[count($temp) - 1];

		}
		
		
	      if (empty($nome)) { $nome="portfolio aluno"; }


			header("Pragma: public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Description: File Transfer");	
			header("Content-type: "  .$linha["TIPO_ARQUIVO"]);
			header("Content-Disposition: inline; filename=". $nome); 
			header("Content-Transfer-Encoding: binary");
	
	
	/*// Nós estaremos enviando um PDF	
	header ("Content-type: " . $linha["TIPO_ARQUIVO"] );

	// Será chamado downloaded.pdf - attachment - inline
	
	
	header("Content-Disposition: inline; filename=". $nome);
	//header("Content-Disposition: attachment; filename=". $nome );
	// A fonte do PDF é original.pdf	*/
  flush();
	readfile($arq);
	  
  }
   else
	 {
	 	echo "Caminho vazio";
		exit();
	  }		
?>