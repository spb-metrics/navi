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
								<b>	Arquivo não Existe </b>
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

