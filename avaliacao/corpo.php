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
include_once($caminhoBiblioteca."/avaliacao.inc.php");
session_name(SESSION_NAME); session_start(); security(0,1);
    // Apenas mostra os arquivos caso os arquivos sejam de acesso publico ou se o video for da mesma turma que a pessoa esta no momento
		if ( ( $_SESSION["codInstanciaGlobal"] == "" ) OR ( $_REQUEST["COD_ARQUIVO"] == "" ) ) {
			exit();
                }
                
		
		$rsCon = listaAval($_REQUEST["COD_ARQUIVO"]);

		$mostrar = false;
		
		if ( $rsCon )
		{
			while ( ($mostrar == false) AND ($linha = mysql_fetch_array($rsCon)) )
			{ 
				if ( $linha["COD_INSTANCIA_GLOBAL"] == $_SESSION["codInstanciaGlobal"] )
					$mostrar = true;
			 }
		 }
		
		//encerra a sessao assim q puder
		session_write_close();
		
		if ( $mostrar == true ){

			$arq = $caminhoUpload.$linha["CAMINHO_LOCAL_ARQUIVO"];
                        if ( ! file_exists($arq) ) {
                           $arq = $caminhoUpload1. $linha["CAMINHO_LOCAL_ARQUIVO"];
       
                         }
			$temp = explode("/", $arq); 

			$nome_texto = $temp[count($temp) - 1];

			
    	                
			//flush();
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	
		 header("Content-type: "  .$linha["TIPO_ARQUIVO"]);
  		 header("Content-Disposition: inline; filename=". $nome_texto );
		
		/*	header("Cache-Control: public"); 
			header("Content-Description: File Transfer");	
			header("Content-type: "  .$linha["TIPO_ARQUIVO"]);
      header("Content-Disposition: attachment; filename=". $nome_texto); 
			header("Content-Transfer-Encoding: binary");*/
			readfile($arq);

		 }
?>