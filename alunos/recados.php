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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);	
include_once("../config.php");
include_once ($caminhoBiblioteca."/perfil.inc.php");
session_name(SESSION_NAME); session_start(); security();	

?>
<head>
<title>Recados</title>
<link rel="stylesheet" href=".././cursos.css" type="text/css">
<link rel="stylesheet" href="./../sca.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<html>
<body bgcolor="#FFFFFF" text="#000000">
<?php
$cod_pessoa_recebe= $_REQUEST["COD_PESSOA_RECEBE"];
  
$rsCon=dadosPerfil($cod_pessoa_recebe);
	if($rsCon) {
		if ($linha = mysql_fetch_array($rsCon)){
			$foto= str_replace("\\","/",$linha["FOTO"]);
			
		}
		else{$foto="";}
	}

?>

<p class="menu" align="center">Recados<img src="148.jpg" height="64" width="64"></p>
 
<table width="94%" border="0" cellspacing="0" cellpadding="0" align="center">
  <form name="form1" method="post" action="recado_envia.php">
   <tr>
	  <td width="184" height="150"  > 
        <p align="left"><img src="foto.php?COD_PESSOA=<?=$_REQUEST["COD_PESSOA_RECEBE"]?>&CASE=FOTO" height='<?echo ALTURA_FOTO; ?>' width='<?echo LARGURA_FOTO; ?>' border='0'><br><a href="<?echo $url."/consultar.php?BUSCA_PESSOA=".$cod_pessoa_recebe."";?>"  target="_blank"><?echo $linha["NOME_PESSOA"];?></a></p>
    	 
   	</td>
  <td>&nbsp;&nbsp;&nbsp;</td>
	  <td width="618" > 
       <?php if (podeInteragir($_SESSION['userRole'],$_SESSION['interage']))  { ?>
        <p align="left" >Deixe seu Recado:<br><textarea cols=90 rows=7 name="TEXTO" MAXLENGTH="500"><?=$texto?></textarea></p>
        <?php } ?>
	</td>
      <td align="center" width="153" > 
       <?php if (podeInteragir($_SESSION['userRole'],$_SESSION['interage']))  { ?>
        <input type="hidden" name="COD_PESSOA_RECEBE" value="<?=$cod_pessoa_recebe;?>">
        <input type="submit" name="Submit" value="Enviar"><br><br> 
        <input type="reset" value="cancelar" onclick="window.close()">  
        <?php } ?>
   </td>
  </tr>
   </form >
 </table>
 <table width='700px' border="0" cellspacing="0" cellpadding="0" align="center">
<?
$rsConN = listaRecados($cod_pessoa_recebe); 

$var_classe = "CelulaEscura";

  echo "<tr ><td align='center' class='" . $var_classe . "' colspan='3'><p><b>".
       " Página de Recados de ". $linha["NOME_PESSOA"]. 
       " </font></b></p></td><td>&nbsp;</td><td><b>Marcar como lida</b></td></tr>";

$var_classe = "CelulaClara";
while ($linha = mysql_fetch_array($rsConN))
  {

  if ($linha["msgLida"]) $var_classe = "CelulaClara";
  else $var_classe = "CelulaEscura";

	echo "<tr><td align='center' class='" . $var_classe . "'><p><b>".
         " <a href='recados.php?COD_PESSOA_RECEBE=". $linha["COD_PESSOA"] ."'>" . 
		 "<img src='foto.php?COD_PESSOA=".$linha["COD_PESSOA"]."&CASE=FOTO_REDUZIDA' height='".ALTURA_FOTO_PEQUENA."' width='".LARGURA_FOTO_PEQUENA."' border='0'>".
		 "</a></b></p></td>".
	     " <td align='left' class='" . $var_classe . "' width='80%'><p><b><a href='recados.php?COD_PESSOA_RECEBE=". $linha["COD_PESSOA"] ."'>" .  $linha["NOME_PESSOA"] . "</a>:&nbsp;" .  $linha["TEXTO"] . "</font></b></p></td>".
       " <td align='left' class='" . $var_classe . "' width='10%'><p><b>" .  $linha["DATA"] . "</font></b></p></td>";
      
  if(($cod_pessoa_recebe == $_SESSION["COD_PESSOA"]) OR ($linha["COD_PESSOA"]==$_SESSION["COD_PESSOA"]))
  {
    if (podeInteragir($_SESSION['userRole'],$_SESSION['interage']))  { 
       echo "<td width='10%'><p><a href='recado_envia.php?OPCAO=Excluir&COD_RECADO=".$linha["COD_RECADO"]."&COD_PESSOA_RECEBE=". $cod_pessoa_recebe ."'>" . "Apagar&nbsp; </a></p></td>";
	  }
	  echo "<td width='5%'><p><input type=\"checkbox\" value=\"\" onclick=\"if(this.checked){location.href='recado_envia.php?OPCAO=Lida&COD_RECADO=".$linha["COD_RECADO"]."&COD_PESSOA_RECEBE=". $cod_pessoa_recebe ."';}else{location.href='recado_envia.php?OPCAO=Lida&COD_RECADO=".$linha["COD_RECADO"]."&COD_PESSOA_RECEBE=". $cod_pessoa_recebe ."';}\"";

    if ($linha["msgLida"])
      echo "checked";
		echo"></p></td></tr>";
 	}
	
}					
?>
  </table>
<p align='center'>
<?
 if (empty($_REQUEST['linkExterno'])) { 
   echo "<a href='recados.php?COD_PESSOA_RECEBE=".$_SESSION["COD_PESSOA"]."'>Meus Recados</a>";
 }
 if ( $_SERVER['HTTP_REFERER']!= $_SERVER['PHP_SELF'] ) {
   echo "<a href='".$_SERVER['HTTP_REFERER']."'>&nbsp;&nbsp;&nbsp;<< Voltar</a>";
 }
 else {
   echo "<a href='index.php'>&nbsp;&nbsp;&nbsp;<< Ir para página de Apresentação</a>"; 
 }
 ?>
 </p>
 </body>
 </html>