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

//include_once("../funcoes_bd.php");
include("../config.php");
include($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();
session_write_close();
include($caminhoBiblioteca."/functionsDeEdicao.inc.php"); //rever para nao precisar da sessao

$titleEditar='Permite visualizar/editar todos os seus arquivos de conteúdos .';
$titleCriar='Criar um novo conteúdo.';
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="<?=$urlCss;?>/padraogeral.css" type="text/css">
	</head>



<?php

echo "<body class='bodybg'>";	
$instancia = new InstanciaGlobal((int)$_SESSION['codInstanciaGlobal']);
$alinhamento = $instancia->getAlinhamentoConteudos();

if ($_SESSION["COD_PESSOA"] != "") {
	$acesso = 2;  //privado
}
else {
	$acesso = 1;
}	
if(!empty($_REQUEST["order"])&&!empty($_REQUEST["by"])){
$order=$_REQUEST["order"];
$by=$_REQUEST["by"];
}else{
$by='DESC_ARQUIVO_INSTANCIA';
$order="ASC";
}
	$rsCon = apoioAulas($acesso,$order,$by);
if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) ) 	{
	msg("N&atilde;o h&aacute; conte&uacute;dos dispon&iacute;veis no momento.");
	editarCriar('apoio',$titleEditar,$titleCriar);
 }		
else {
	if ($_SESSION["COD_PESSOA"] != "") 	{
		msg("Conte&uacute;dos dispon&iacute;veis:");
	}
	else 	{
		msg("Conte&uacute;dos dispon&iacute;veis para visitante:");
	}
	
	editarCriar('apoio',$titleEditar,$titleCriar);
echo formListConteudos($order,$by);
	echo "<p align='".$alinhamento."'>";
	while ($linha = mysql_fetch_array($rsCon))	{
		excluiAlteraNaInstancia('apoio', $linha["COD_ARQUIVO"],'COD_ARQUIVO',$linha["COD_TIPO_ACESSO"],$linha['COD_PESSOA']);
		
		echo "<a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "','', 'fullscreen=yes, scrollbars=none');\"> ".
				$linha["DESC_ARQUIVO_INSTANCIA"] .	 "</a>";
		echo "<a href=\"./mostrar.php?download=1&COD_ARQUIVO=" . $linha["COD_ARQUIVO"]."\"> - Download</a>";

		echo "<BR> <BR>";
	}
}

?>
		
</body>
</html>

