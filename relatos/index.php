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
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/relato.inc.php");
session_name(SESSION_NAME); session_start(); security();
//A partir de agora, apenas le da sessao
session_write_close();

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body bgcolor="#FFFFFF" text="#000000">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
	   <td width="100%" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>
  <?php

	if ( ($_SESSION["COD_ADM"] == "") and ( $_SESSION["COD_PROF"] == "") and ($_SESSION["COD_AL"] == "") )
	{
		echo "<p align='center'class='menu'> <b>relato dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }
	
	?>

	<p align='center' class='menu' ><b>Relatos: </b></p>
	
  <p align='center'><a href="relato_listar.php?ORDER=titulo">Ordenar por Titulo </a></p>			
  <p align='center'><a href="relato_listar.php?ORDER=nome">Ordenar por Nome </a></p>			
	<?php
  if (podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {	
	  echo '<p align="center"><a href="./relato_enviar.php">Escreva seu Relato </a></p>';			
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
