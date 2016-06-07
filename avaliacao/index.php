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
include_once($caminhoBiblioteca."/functionsDeEdicao.inc.php");

$titleEditar='Permite visualizar/editar todos os suas avalia��es.';
$titleCriar='Criar uma nova avalia��o.';
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
