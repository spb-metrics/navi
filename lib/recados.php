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

<p class="menu" align="center">Recados<img src="148.jpg" height="30" width="40"></p>
 
<table width="94%" border="0" cellspacing="0" cellpadding="0" align="center">
  <form name="form1" method="post" action="recado_envia.php">
   <tr>
	  <td width="184" height="150"  > 
        <p align="left"><img src="foto.php?COD_PESSOA=<?=$_REQUEST["COD_PESSOA_RECEBE"]?>&CASE=FOTO" height="90" width="120" border="2" ><br><?echo $linha["NOME_PESSOA"];?></p>
    	 
   	</td>
  <td>&nbsp;&nbsp;&nbsp;</td>
	  <td width="618" > 
        <p align="left" >Deixe seu Recado:<br><textarea cols=90 rows=7 name="TEXTO" MAXLENGTH="500"><?=$texto?></textarea></p>
	</td>
      <td align="center" width="153" > 
        <input type="hidden" name="COD_PESSOA_RECEBE" value="<?=$cod_pessoa_recebe;?>">
    <input type="submit" name="Submit" value="Enviar"><br><br> 
	<input type="reset" value="cancelar" onclick="window.close()">  
   </td>
  </tr>
   </form >
 </table>
 <table width='600px' border="0" cellspacing="0" cellpadding="0" align="center">
<?
$rsConN = listaRecados($cod_pessoa_recebe);

$var_classe = "CelulaEscura";

  echo "<tr ><td align='center' class='" . $var_classe . "' colspan='3'><p><b><font style=\"text-transform: capitalize;\">".
       " Página de Recados de ". $linha["NOME_PESSOA"]. 
       " </font></b></p></td></tr>";

$var_classe = "CelulaClara";
while ($linha = mysql_fetch_array($rsConN)){
	echo "<tr><td width='10%'align='center' class='" . $var_classe . "'><p><b>".
         " <a href='recados.php?COD_PESSOA_RECEBE=". $linha["COD_PESSOA"] ."'>" . 
		 "<img src='foto.php?COD_PESSOA=".$linha["COD_PESSOA"]."&CASE=FOTO_REDUZIDA' height='30' width='40' border='2'>".
		 "</a></b></p></td>".
	     " <td align='left' class='" . $var_classe . "' width='60%'><p><b><font style=\"text-transform: capitalize;\"><a href='recados.php?COD_PESSOA_RECEBE=". $linha["COD_PESSOA"] ."'>" .  strtolower($linha["USER_PESSOA"]) . "</a>:&nbsp;" .  strtolower($linha["TEXTO"]) . "</font></b></p></td>".
         " <td align='left' class='" . $var_classe . "' width='10%'><p><b><font style=\"text-transform: capitalize;\">" .  strtolower($linha["DATA"]) . "</font></b></p></td>";
      
  
  if(($cod_pessoa_recebe == $_SESSION["COD_PESSOA"]) OR ($linha["COD_PESSOA"]==$_SESSION["COD_PESSOA"]))
  {
  echo "<td width='10%'><p><a href='recado_envia.php?OPCAO=Excluir&COD_RECADO=".$linha["COD_RECADO"]."&COD_PESSOA_RECEBE=". $cod_pessoa_recebe ."'>" . "Apagar </a></p></td></tr>";
	}
	
  if ($var_classe == "CelulaClara" ) 
	  $var_classe = "CelulaEscura";
	else 
		$var_classe = "CelulaClara";
}					
?>
  </table>
 <p align="center"><a href="recados.php?COD_PESSOA_RECEBE=<?=$_SESSION["COD_PESSOA"];?>">Voltar</a></p>
 </body>
 </html>