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


//include_once("./../../funcoes_bd.php");
include_once("./../../config.php");
include_once($caminhoBiblioteca."/enquete.inc.php");

session_name(SESSION_NAME); session_start(); security();
include_once($caminhoBiblioteca."/functionsDeEdicao.inc.php");

$titleEditar='Permite visualizar/aditar todos as suas enquetes.';
$titleCriar='Criar uma nova enquete.';


?>
<html>
	<head>
		<title>F&Oacute;RUM</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 
		
			<table width="100%" height='100%' border="0" cellpadding="0" cellspacing="0" >
				<tr> 
					<td>
	<?php
	
		
	if ( ($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == "") )
	{
		msg("Enquete dispon&iacute;vel apenas para alunos cadastrados.");
		exit();
	 }
		
	$rsCon = enquete("", "", "");

	if ( mysql_num_rows($rsCon)>0 )
	{
		msg("Lista das enquetes dispon&iacute;veis no momento:");

		  editarCriar('enquete',$titleEditar,$titleCriar);

	
		while ( $linha = mysql_fetch_array($rsCon) )
		{
		  /*
			 if ( strlen($linha["TEXTO_ENQUETE"]) > 105 ) {}
			 	$texto =(( substr(($linha["TEXTO_ENQUETE"]),0,105) ) . "...");
		     else {} 
			 {*/
			    $texto = $linha["TEXTO_ENQUETE"];
       

			  echo "<p style='border-bottom:2px #000000 solid;'>";
	            excluiAlteraNaInstancia('enquete', $linha["COD_ENQUETE"],'COD_ENQUETE',$linha["COD_TIPO_ACESSO"],$linha["COD_PESSOA"]);

		    	echo "<a href=\"./votar.php?COD_ENQUETE=" . $linha["COD_ENQUETE"] . "\"> Votar </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href=\"./resultado.php?COD_ENQUETE=" . $linha["COD_ENQUETE"] . "\"> Ver Resultado</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $texto . "</p>";					  
		 }
	 }	
	else{
		msg("N&atilde;o h&aacute; enquetes dispon&iacute;veis no momento.");
		editarCriar('enquete',$titleEditar,$titleCriar);

		}
	?>  
	
					</td>
				</tr> 
	      </table>
		</td> 
	</tr>
</table>
</body>
</html>
