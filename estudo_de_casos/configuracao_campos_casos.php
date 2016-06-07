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


include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/estudo_de_casos.inc.php");
session_name(SESSION_NAME); session_start(); security();
/*defines:*/
global $campo;
global $name;

if (empty($acao)){$acao="";} else{$acao= $_REQUEST["acao"];}

//cabe�alho
function printHeader($params="") {
GLOBAL $url;
  echo "<html>";
  echo "<head>";
  echo "<link rel=\"stylesheet\" href=\"".$url."/cursos.css\" type=\"text/css\">";
   echo "<link rel=\"stylesheet\"  href=\"".$url."/css/cssnavi.css\" type=\"text/css\">";
  echo "<link rel=\"stylesheet\" href=\"../interacao/correio/correio.css\" type=\"text/css\">";
  if (!empty($params["titulo"]))
    echo "<title>{$params["titulo"]}</title>";
  echo "</head>";
  echo "<body {$params["body"]}>";
  echo "<h4 class=\"titulo\">{$params["tituloPagina"]}</h4>";

}

//est� fun��o deve mostrar uma tabela que lista os campos atuais "desta turma", e possibilidade de 
// $codCampo= deve conter o c�digo do campo a ser modificado Ex:1, 2, 3
// $titulo= deve conter o nome do campo que ser� associado a vari�vel $campo . Ex:Diagn�stico->2, objetivos->3
function printLine($aparece,$codInstanciaGlobal,$campo,$titulo)
{
	echo "<form name=\"form".$campo."\" method=\"POST\" action=\"\" >".
		 "<tr  align=\"center\">".
		 "<td><input type=\"text\" name=\"texto\" value=\"".$titulo."\" size=\"60\"></td>";
	if($campo!=1){
	echo "<td><input type=\"checkbox\" name=\"aparece\" value=\"1\"";
		if($aparece)
	echo "checked";
	echo "></td>";
	}else{echo"<td>&nbsp;&nbsp;</td>";}
	echo "<td><a href=\"#\"".  " onclick=\"javascript:document.form".$campo.".action='".$_SERVER["PHP_SELF"]."?acao=gravaAlteracao&codInstanciaGlobal=".$codInstanciaGlobal."&codCampo=".$campo."'; document.form".$campo.".submit();\" ><img src=\"modificar.jpg\" height=\"24\" width=\"24\" border=\"0\"></a></td>".
		 "<td><a href=\"#\"". " onclick=\"javascript:document.form".$campo.".action='".$_SERVER["PHP_SELF"]."?acao=gravaExclusao&codInstanciaGlobal=".$codInstanciaGlobal."&codCampo=".$campo."'; document.form".$campo.".submit();\" ><img src=\"restaurar.gif\" border=\"0\"></a></td>".
		"</tr></form>";
}

switch ($_REQUEST["acao"]) {  


  case "": 

 $params["tituloPagina"]="Configura��o dos campos do Estudo de Caso";
 printHeader($params);

echo"<p align=\"right\"><a href=\"./index.php\"> Voltar </a></p>";
    $config = getConfiguracoesCampo($_SESSION['codInstanciaGlobal']);
	echo "<table><tr align=\"center\"><td><b>Campos</b></td><td><b>Aparece</b></td><td><b>Modificar</b></td><td><b>Restaurar<br> Padr�o</b></td></tr><tr>";
    foreach($campo as $codCampo=>$titulo) {
      if (!empty($config[$codCampo])) {
		printLine($config[$codCampo]->aparece,$config[$codCampo]->codInstanciaGlobal,$config[$codCampo]->codCampo,$config[$codCampo]->titulo);
      }
      else {
		printLine($aparece=1,$_SESSION['codInstanciaGlobal'],$codCampo, $titulo);

      }

    }

echo "</tr></table>";
echo"</body></html>";
    break;

  case "gravaAlteracao":
 if(modificaConfiguracaoCampo($_REQUEST["codInstanciaGlobal"],$_REQUEST["codCampo"],$_REQUEST["texto"],$_REQUEST["aparece"]))
	 echo"<script>location.href=\"".$_SERVER["PHP_SELF"]."\";</script>";
	  
			
	    break;

  case "gravaExclusao":
 
 if(exclueConfiguracaoCampo($_REQUEST["codInstanciaGlobal"],$_REQUEST["codCampo"]))
	  echo"<script>location.href=\"".$_SERVER["PHP_SELF"]."\";</script>";    break;

}
?>
