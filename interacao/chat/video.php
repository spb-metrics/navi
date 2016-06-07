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
