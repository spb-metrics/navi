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
	include_once("../config.php");
		include_once ($caminhoBiblioteca."/perfil.inc.php");?>
<html>
<head>
<title>Perfil</title>
<link rel="stylesheet" href=".././cursos.css" type="text/css">
<link rel="stylesheet" href="./../sca.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">

<table width="606" border="1" height="347">
  <tr>
    <td height="348"> 
      <table width="597" border="0" height="342">
        <tr> 
          <td height="326" width="374"  valign="top"><br>
    <p align="left"><b>Apresenta��o Pessoal:</b></p>
    <p align="left">DEVERIA TER UMA DESCRI��O</p>
   </td>
          <td width="213" valign="top" height="326"> 
            <p align="right"><?echo mostraFotoReduzida($_REQUEST["COD_PESSOA"]);?></p></td>
  </tr>
</table>
</td></tr>
</table>
<p align="center"><a href="javascript:window.close()">Fechar Janela</a></p>
</body>
</html>