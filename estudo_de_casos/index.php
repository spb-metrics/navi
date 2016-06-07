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
session_name(SESSION_NAME); session_start(); security();
if ( ($_SESSION["COD_ADM"] == "") and ( $_SESSION["COD_PROF"] == "") and ($_SESSION["COD_AL"] == "") ){
	?>
	<html>
	<head>
		<link rel="stylesheet" href=".././cursos.css" type="text/css">
	</head>
	<?
	msg("Casos dispon&iacute;veis apenas para alunos cadastrados.");
	die();
}

include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/estudo_de_casos.inc.php");

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href=".././cursos.css" type="text/css">
		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body bgcolor="#FFFFFF" text="#000000">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<?if(Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage'])){?>
	<tr><td>
		<p align='right'><a href="configuracao_campos_casos.php">|CONFIGURA��O DOS CAMPOS DO ESTUDO DE CASO| </a></p></td></tr>
<?}?>
	<tr> 
	
	    <td width="740" align="center"> 
           <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td>
					<p align="justify"><b>ESTUDO DE CASO</b><br><br>
					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A atividade do Curso denominada &quot;Estudos de Caso&quot; prop&otilde;e a an&aacute;lise e identifica&ccedil;&atilde;o 
					  de uma situa&ccedil;&atilde;o problem&aacute;tica. Escolhido 
					  o problema a ser solucionado, enseja a elabora&ccedil;&atilde;o 
					  de um PROJETO de trabalho, para solucion&aacute;-lo. </p>

					<p align='center'><a href="casos_listar.php?ORDER=titulo">Ordenar por Titulo </a></p>			
					<p align='center'><a href="casos_listar.php?ORDER=nome">Ordenar por Nome </a></p>			
					<?php
          if (podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {	
					  echo '<p align="center"><a href="./casos_enviar.php">Escreva seu Estudo de Caso </a></p>';
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
