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

