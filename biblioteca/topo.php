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

/*
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();*/
?>
<html>
	<head>
		<title>Texto</title>	
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body>


  <?php
  /*
  if ( !isset($_REQUEST["COD_ARQUIVO"]) )
  	$_REQUEST["COD_ARQUIVO"] = "";
  
	if ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) OR ( $_REQUEST["COD_ARQUIVO"] == "" ) )
		exit();
	
   // Apenas mostra os arquivos caso os arquivos sejam de acesso publico ou se o video for da mesma turma que a pessoa esta no momento
	
		$rsCon = bibliotecaitem($_REQUEST["COD_ARQUIVO"]);

		$mostrar = false;
		
		if ( $rsCon )
		{
			while ( ( $linha = mysql_fetch_array($rsCon) ) AND ( $mostrar == false) )
				if ( $linha["COD_INSTANCIA_GLOBAL"] == $_SESSION["codInstanciaGlobal"] )
					$mostrar = true;
		 }
		
		//encerra a sessao o qto antes para nao bloquear a sessao no outro frame  
		session_write_close();
		
		if ( $mostrar == true ) {
		
			if ( isset($linha["CAMINHO_LOCAL_ARQUIVO"]) ) {
				if ( !file_exists($linha["CAMINHO_LOCAL_ARQUIVO"]) )			{
			
//			Set objFSO = CreateObject("Scripting.FileSystemObject")
//			if rsCon("CAMINHO_LOCAL_ARQUIVO") <> "" then
//				If not objFSO.FileExists( rsCon("CAMINHO_LOCAL_ARQUIVO") ) Then
*/
					?>
	<!-- COMENTADO POR ENQUANTO. APENAS O FECHAR HABILITADO
  				<table width="750" align="center" >
						<tr>
							<td align="center">
								<b>	Arquivo n�o Existe </b>
							</td>
							<td align="right">
								<a class="menu" href="#" onClick='window.close();'>Fechar</a>
							</td>
						</tr>
					</table>
			-->		
  
			<table width="600" align="center" >
				<tr>
					<td align="center">
						<b> <?//= $linha["DESC_ARQUIVO_INSTANCIA"] ?> </b>
					</td>
					<td align="right">
						<a class="menu" href="#" onClick='window.parent.close();'>Fechar</a>
					</td>
				</tr>
			</table>
			
	<?
        /*
      }
    }
  }*/
	?>
</body>
</html>
