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
	
include("../config.php");
include($caminhoBiblioteca."/perfil.inc.php");
include($caminhoBiblioteca."/utils.inc.php");
session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die; }
?>
<html>
<head>
<title>Perfil</title>
<link rel="stylesheet" href=".././cursos.css" type="text/css">
<link rel="stylesheet" href="<?echo $urlCss; ?>/padraogeral.css" type="text/css">
<script language="JavaScript" src="<?=$url?>/js/editor.js"></script>
<script language="javascript" type="text/javascript" src="<?=$url?>/js/tiny_mce/tiny_mce.js"></script>
</head>
<?
$rsCon=dadosPerfil($_SESSION['COD_PESSOA']);
  $linha = mysql_fetch_array($rsCon);
	if(!empty($linha)) {
		$foto2= str_replace("\\","/",$linha["FOTO_REDUZIDA"]);
		
		$foto= str_replace("\\","/",$linha["FOTO"]);
	   
		$desc= $linha["DESC_PERFIL"];	
		$linkPerfil=$linha["LINK_PERFIL"];
	}
  else {
    $foto="";$desc="";$foto2="";$linkPerfil=""; 
  }
   ?>

<body class='bodybg'>
<!--<script language="JavaScript" type="text/javascript">initHtmlEditorCompleto('300'); </script>-->
 <div style="padding-left:50px;">
 <form name="form1" method="post" enctype="multipart/form-data" action="upload.php">
   
   <img src="foto.php?COD_PESSOA=<?=$_REQUEST["COD_PESSOA"]?>&CASE=FOTO" height="<?echo ALTURA_FOTO; ?>" width="<?echo LARGURA_FOTO; ?>" border="0" align='center'><br>
   
   <br><b> Atualizar foto: </b><br><input type="file" name="ARQUIVO_NOVO" size="60"><br>
   A foto ser&aacute; redimensionada para o padr&atilde;o <?echo LARGURA_FOTO; ?> (largura) x <?echo ALTURA_FOTO;?> (altura)
<?
if (!function_exists('imagecreate')&&!function_exists('imagecopyresampled')) {
   echo "<br><br><b>Mini foto (Max.30kB):</b><br><input type=\"file\" name=\"MINI_FOTO\" size=\"60\">";
 }
?>
   <br><br>
   <br><b>Apresentacao Pessoal</b><br>
   <? echo ativaDesativaEditorHtml()?>
   <br>
	 <textarea cols='86' rows='10' name="DESC_PERFIL" MAXLENGTH="500"><?=$desc?></textarea>

   <br><br><b>Links de Apresentação</b><br>	
   <input type="text" name="LINK_PERFIL" value="<?=$linkPerfil?>" size="86"><br>
   <span onMouseOver="ajuda.style.visibility = 'visible'" onMouseOut="ajuda.style.visibility = 'hidden'">Quero colocar + links<img src="383.jpg"></span>
   <div id=ajuda align="center" style="position: absolute; overflow: visible; visibility: hidden; width: 320; left: 23%; top: 60%; background-color: white; border: 1px solid black; z-index:3;">
   <table class="menu">
    <tr>
	  <td><p align="center">Voce pode colocar mais de 1 (um) link de apresentacao, <br>
			Apenas precisa separa-los por ; (ponto e virgula)<br>
			Ex:<b>http://www.google.com.br;http://www.gmail.com</b></p>		
  	 </td>
  	</tr>
   </table>
   </div>

   <br><br>
   <div align='center'>	
   <input type="submit" name="Submit" value="Enviar" class="okButton">  
   <input type="reset" value="cancelar" onclick="location.href='./index.php'"  class="cancelButton">  
   <!--
   <input type="hidden" name="CAMINHO_FOTO" value="<?=$foto?>">
   <input type="hidden" name="CAMINHO_FOTO_REDUZIDA" value="<?=$foto2?>">
   -->
   </div>
  </form >
  </div>
    
</body>
</html>
