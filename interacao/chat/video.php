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

ini_set("session.use_only_cookies",1);
//include_once("./../../funcoes_bd.php");
include_once ("./../../config.php");
include_once ($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start();// security();
?>
<html>
	<head> </head>
	<body>
<?php
if ( !isset($_REQUEST["ATIVAR"]) )
	$_REQUEST["ATIVAR"] = "";
if ( !isset($_REQUEST["DESATIVAR"]) )
	$_REQUEST["DESATIVAR"] = "";

	if ( ( $_SESSION["COD_ADM"] != "" ) OR ( $_SESSION["COD_PROF"] != "" ) )
	{
		if ( $_REQUEST["ATIVAR"] != "" )
		{
			$ok = videoChat("atualizar", 1);
			echo "<Script language='JavaScript'> location.href='./index.php'; </Script>";
		 }
		else
		{
			if ( $_REQUEST["DESATIVAR"] != "" )
			{
				$ok = videoChat("atualizar", 0);
				echo "<Script language='JavaScript'> location.href='./index.php'; </Script>";
			 }
		 }
	 }

?>

	</body>
</html>
