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


include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/estudo_de_casos.inc.php");
session_name(SESSION_NAME); session_start(); security();
/*defines:*/
global $campo;
global $name;

if (empty($acao)){$acao="";} else{$acao= $_REQUEST["acao"];}

//cabeçalho
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

//está função deve mostrar uma tabela que lista os campos atuais "desta turma", e possibilidade de 
// $codCampo= deve conter o código do campo a ser modificado Ex:1, 2, 3
// $titulo= deve conter o nome do campo que será associado a variável $campo . Ex:Diagnóstico->2, objetivos->3
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

 $params["tituloPagina"]="Configuração dos campos do Estudo de Caso";
 printHeader($params);

echo"<p align=\"right\"><a href=\"./index.php\"> Voltar </a></p>";
    $config = getConfiguracoesCampo($_SESSION['codInstanciaGlobal']);
	echo "<table><tr align=\"center\"><td><b>Campos</b></td><td><b>Aparece</b></td><td><b>Modificar</b></td><td><b>Restaurar<br> Padrão</b></td></tr><tr>";
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
