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
    <p align="left"><b>Apresentação Pessoal:</b></p>
    <p align="left">DEVERIA TER UMA DESCRIÇÂO</p>
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