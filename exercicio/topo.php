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
include_once ("../config.php");
include_once ($caminhoBiblioteca."/exercicio.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body>
        <?php
	

		/*if (($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == "" ) OR ($_REQUEST["COD_ARQUIVO"] == ""))
			exit();*/
		if ($_REQUEST["COD_ARQUIVO"] == "")
			exit();
		
		
// Apenas mostra os arquivos caso os arquivos sejam de acesso publico ou se o video for da mesma turma que a pessoa esta no momento
	
		
		$rsCon = ExerAulas($_REQUEST["COD_ARQUIVO"]);	
		$mostrar = false;
		
		if ($rsCon)
			while (($mostrar == false) and ($linha = mysql_fetch_array($rsCon)))
				if ($linha["COD_INSTANCIA"] == $_SESSION["COD_INSTANCIA"])
					 $mostrar = true;
					
		//encerra a sessao assim q puder para nao bloquear o outro frame
		session_write_close();

		if ($mostrar == true)
			if ($linha["CAMINHO_LOCAL_ARQUIVO"] != "" )
			{
					?>
					<table border="0" width="750" align="center" >
						<tr>
							<td width="600" align="center">
								<b>  <?= $linha["DESC_EXERCICIO_INSTANCIA"] ?></b>
							</td>
							<td align="right">
					         <a class="menu" href="javascript:top.close();">Fechar arquivo</a> <!--target="_top";-->
					 		</td>
						</tr>
					</table>
					<?php
				
				}
					?>		
</body>
</html>
