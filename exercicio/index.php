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
include($caminhoBiblioteca."/exercicio.inc.php");

session_name(SESSION_NAME); session_start(); security();
include($caminhoBiblioteca."/functionsDeEdicao.inc.php");

$titleEditar='Permite visualizar/aditar todos os seus arquivos de exercício.';
$titleCriar='Criar um novo exercício.';


?>
<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="../cursos.css" type="text/css">
	</head>

<Body>

<?php
	
	
	if ( empty($_SESSION["COD_PESSOA"]) || empty($_SESSION["codInstanciaGlobal"])) 	{
	//	msg("Exerc&iacute;cios dispon&iacute;veis.");
		
	}
	$rsCon = ExerAulas(""); 

if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) )
{
  msg("N&atilde;o h&aacute; exercícios dispon&iacute;veis no momento.");
    editarCriar('exercicio',$titleEditar,$titleCriar);

}	
else	
 	{
		msg("Exerc&iacute;cios dispon&iacute;veis:");	
 	   editarCriar('exercicio',$titleEditar,$titleCriar);

		
echo "<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">".
		"<tr>". 
			"<td align=\"center\">" ;

		echo "<p align='center'>";
	
		while ($linha = mysql_fetch_array($rsCon)) {
		
      excluiAlteraNaInstancia('exercicio', $linha["COD_ARQUIVO"],'COD_ARQUIVO',$linha["COD_TIPO_ACESSO"],$linha["COD_PESSOA"]);
			if(($linha["COD_TIPO_ACESSO"]==2)AND (!empty($_SESSION["COD_PESSOA"])))
			{	
				 echo"<a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "','', 'fullscreen=no, scrollbars=none');\">". $linha["DESC_EXERCICIO_INSTANCIA"] ."</a>";
			//echo "<a href='./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "'>" . $linha["DESC_EXERCICIO_INSTANCIA"] . "</a>";	
		  //echo " - <a href='./mostrar.php?download=1&COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "'>Download</a>";
			   echo " - <a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?download=1&COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "','', 'fullscreen=no, scrollbars=none');\">Download</a>";			
			}else
			{
				if($linha["COD_TIPO_ACESSO"]==3)
				{
				echo "<a href='./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "'>" . $linha["DESC_EXERCICIO_INSTANCIA"] . " </a>";	
				
        echo " - <a href='./mostrar.php?download=1&COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "'>Download</a>";
				
        }
			}

      echo "<BR>";
    }
    echo"</p>";
	}
	
				
?>
			</td>
		</tr>
	</table>			
</body>
</html>
