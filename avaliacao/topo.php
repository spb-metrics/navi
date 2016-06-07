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
session_name(SESSION_NAME); session_start(); security();

?>
<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="../cursos.css" type="text/css">
	</head>

 <body class="bodybg">
        <?php

		if (($_SESSION["codInstanciaGlobal"]== "") OR ($_REQUEST["COD_ARQUIVO"] == "") )
		{
			exit();
		}
		
		
//  Apenas mostra os arquivos caso os arquivos sejam de acesso publico ou se o video for da mesma turma que a pessoa esta no momento
	
		
		$rsCon = listaAval($_REQUEST["COD_ARQUIVO"]);
		$mostrar = false;
		
		if ( $rsCon)
		    {
			  if ( mysql_num_rows($rsCon)>0)
				 { 
				 while (($mostrar ==false) AND ( $linha = mysql_fetch_array($rsCon)))
				 	   {
						if ( $linha["COD_INSTANCIA_GLOBAL"] == $_SESSION["codInstanciaGlobal"])
							$mostrar = true;
									
							}
				  }
 	          }
		
		//encerra a sessao assim q puder
		session_write_close();
		
	  if ( $mostrar == true )
	  {
	 			
			if ( $linha["CAMINHO_LOCAL_ARQUIVO"] != "" )
			   {
				$mostrar_2=False;
				if(file_exists($caminhoUpload.$linha["CAMINHO_LOCAL_ARQUIVO"])){
					$mostrar_2=true;}
				else{
					if(file_exists($caminhoUpload1.$linha["CAMINHO_LOCAL_ARQUIVO"])){
						$mostrar_2=true;}
					}
				if($mostrar_2==False){
					
					?>
					<table width="750" align="center" >
						<tr>
							<td align="center">
								<b>	Arquivo n�o Existe </b>
							</td>
							<td align="right">
								<a class="menu" href="javascript:parent.location.href='./index.php'">Voltar para Avalia��o</a>
							</td>
						</tr>
					</table>
					<?php
					exit();
				      }
			     }
			        ?>
			
			
			
			<table border="0" width="750" align="center" >
				<tr>
					<td width="500" align="center">
						<b> <?= $linha["DESC_AVALIACAO_INSTANCIA"]?> </b>
					</td>
					<td align="right">
						<a class='menu' href='javascript:top.close();' >Fechar arquivo</a>
					</td>
				</tr>
				
			</table>
			
 <?php
		    
		  }
				
 ?>	
   </body>
</html>
