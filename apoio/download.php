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
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security(0,1);

	if (($_SESSION["COD_PESSOA"] =="") OR ($_SESSION["codInstanciaGlobal"] == "")  OR ($_REQUEST["COD_ARQUIVO"] == "")) 	
		exit();
		
// Apenas assiste o video caso o videos sejam de acesso publico ou se o video for da mesma turma que a pessoa esta no momento
	
		$rsCon = $arquivos($_REQUEST["COD_ARQUIVO"]);
		$mostrar = false;
		
		if ($rsCon) 
			while ( ( $mostrar == false ) and ( $linha = mysql_fetch_array($rsCon) ) );
				if ($linha["COD_INSTANCIA_GLOBAL"] == $_SESSION["codInstanciaGlobal"])
					$mostrar = true;

		if ($mostrar == true) 
		{
			if ( isset($linha["CAMINHO_LOCAL_ARQUIVO"]) )
        $arq=$caminhoUpload.$linha["CAMINHO_LOCAL_ARQUIVO"]
				if ( !file_exists($arq)) 
				{
					echo"<html> <head> <link rel='stylesheet' href='./../cursos.css' type='text/css'> </head> <body class='bodybg'> ";				
					?>
					<table width="750" align="center" >
						<tr>
							<td align="center">
								<b>	Arquivo n�o Existe </b>
							</td>
							<td align="right">
								<a class="menu" href="javascript:parent.location.href='./index.php'">Voltar</a>
							</td>
						</tr>
					</table>
					<?
					exit();
				}
			}


		
			$caminho_video = split("//", $arq);
			$nome_video = $caminho_video(count($caminho_video));
			
			
			header("Content-type: " . $linha["TIPO_ARQUIVO"]);

			header("Content-Disposition: attachment; filename=\"" . $nome_video . "\"");

			flush();

			readfile($arq);
		}		
?>	

