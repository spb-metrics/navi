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
	
	
	/*// N�s estaremos enviando um PDF	
	header ("Content-type: " . $linha["TIPO_ARQUIVO"] );

	// Ser� chamado downloaded.pdf - attachment - inline
	
	
	header("Content-Disposition: inline; filename=". $nome);
	//header("Content-Disposition: attachment; filename=". $nome );
	// A fonte do PDF � original.pdf	*/
  flush();
	readfile($arq);
	  
  }
   else
	 {
	 	echo "Caminho vazio";
		exit();
	  }		
?>