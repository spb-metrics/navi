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
include_once("../config.php");
include_once($caminhoBiblioteca."/avaliacao.inc.php");

session_name(SESSION_NAME); session_start(); security();
include_once($caminhoBiblioteca."/functionsDeEdicao.inc.php");

$titleEditar='Permite visualizar/editar todos os suas avaliações.';
$titleCriar='Criar uma nova avaliação.';
?>
<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		
	</head>
<body>



<?php
		
	if ( ( $_SESSION["COD_ADM"] != "" ) OR ( $_SESSION["COD_PROF"] != "" ) OR ( $_SESSION["COD_AL"] != "") )
		$rsCon = listaAval("");
	else
	{
		msg("Avalia&ccedil;&otilde;es dispon&iacute;veis apenas para alunos cadastrados.");
		exit();
	 }
	echo mysql_error();
	if ( mysql_num_rows($rsCon) > 0 )
	{
		msg("Avalia&ccedil;&otilde;es dispon&iacute;veis:");
		editarCriar('avaliacao',$titleEditar,$titleCriar);
		echo "<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">".
		"<tr>". 
			"<td align=\"center\">" ;
		echo "<p align='center'>";
		while ($linha = mysql_fetch_array($rsCon) )
		{
             excluiAlteraNaInstancia('avaliacao', $linha["COD_ARQUIVO"],'COD_ARQUIVO',$linha["COD_TIPO_ACESSO"],$linha["COD_PESSOA"]);
    
			echo "<a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "','', 'fullscreen=yes, scrollbars=none');\"> ".
						$linha["DESC_AVALIACAO_INSTANCIA"] .	 "</a><br><br>";


			/*echo  "<a href='./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "'>".
				  $linha["DESC_AVALIACAO_INSTANCIA"] . "<br><br>";*/
				  
	//		$linha = mysql_fetch_array($rsCon);
		 }
		echo "</p>";
	 }
	else
	{
 		msg("N&atilde;o h&aacute; Avalia&ccedil;&otilde;es dispon&iacute;veis no momento.");
		editarCriar('avaliacao',$titleEditar,$titleCriar);
	}

?>
			</td>
		</tr>
	</table>			
</body>
</html>
