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

include('defineSession.php');
session_name(SESSION_NAME); session_start(); security();

$_SESSION["PAGINA_ATUAL"] = "ferramentas";
?>


<html>
<head>
	<title>FERRAMENTAS</title>

</head>

<frameset frameborder="NO" border="0" framespacing="0" rows="80,*"> 
  <frame name="A" scrolling="NO" noresize src="./topoFerramentas.php">

<?php
if (isset ($_REQUEST["PAGINA"]) )
{
	if ($_REQUEST["TIPO"] == "A" )
		echo "<frame name=\"B\" src=\"" . $_REQUEST["PAGINA"] . "?OPCAO=" . $_REQUEST["OPCAO"] . "&COD_NOTICIA=" . $_REQUEST["COD_NOTICIA"] . " \">";
 }
else
	echo "<frame name=\"B\" src=\"./tools/index.php\">";
?>

<noframes><body bgcolor="#FFFFFF" text="#000000"> 

</body></noframes>
</frameset>

</html>

