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



//include_once("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/estudo_de_casos.inc.php");
include_once ($caminhoBiblioteca."/miscelania.inc.php");
session_name(SESSION_NAME); session_start(); security();

global $campo;
global $name;


function printHeader($params="") {
	GLOBAL $url;
  echo "<html>".
	   "<head>".
		"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".

		"<link rel=\"stylesheet\" href=\".././cursos.css\" type=\"text/css\">".

		"<script language=\"JavaScript\" src=\".././funcoes_js.js\"></script>".
		"<script language=\"JavaScript\" src=\"".$url."/js/isSuportedXMLHttp.js\"></script>";

  if (!empty($params["titulo"]))
    echo "<title>{$params["titulo"]}</title>".
		 "</head>".
		 "<body {$params["body"]}>".
		 "<h3 class=\"titulo\">{$params["tituloPagina"]}</h3>";

}
$params["titulo"]="N&uacute;cleo de Aprendizagem Virtual";
 printHeader($params);
?>
		<!--<script language="JavaScript">
		function colocaVisto(){
			if(isSuportedXMLHttp()){
				xmlhttp.open("POST","Visto.php",true); 
				xmlhttp.setRequestHeader("Cache-Control", "no-store, no-cache, must-revalidate");
				xmlhttp.setRequestHeader("Cache-Control", "post-check=0, pre-check=0");
				xmlhttp.setRequestHeader("Pragma", "no-cache");
				
				xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4){
						retorno=unescape(xmlhttp.responseText);
						if (retorno!='') {        
							???
						}
					}
				}
			//Executa
			xmlhttp.send(null);  
			}
		}
		function retiraVisto(){
			var ok=isSuportedXMLHttp();

		}
		</script>-->

<?
if(Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage'])){

  if($_REQUEST['acao']=='insereVisto'){
  	if(insereVistoDadoPeloProfessor($_REQUEST['codCaso'],$_REQUEST['codCampo']))
  		echo "<p align='center'>Campo marcado como visto</p>";
  }elseif($_REQUEST['acao']=='retiraVisto'){
  	if(retiraVistoDadoPeloProfessor ($_REQUEST['codCaso'],$_REQUEST['codCampo']))
  		echo  "<p align='center'>Retirado visto do campo</p>";
  }
}

echo"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">".
	"<tr>".
	"<td width=\"100\" valign=\"top\">&nbsp;&nbsp". 
//			include "../noticias_menu_esq_turma.php"

	"</td>".
	"<td width=\"540\" valign=\"top\">". 
    "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" ><tr>";
	
	if(Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage'])){
		echo "<td valign=\"top\" ><b>Colocar Visto</b></td>";
	}
	
    echo"<td valign=\"top\">";
	
if (! isset($_REQUEST["ORDER"]))
	$_REQUEST["ORDER"] = "";
				  
			echo"<p align=\"right\"><a href=\"./casos_listar.php?ORDER=".$_REQUEST["ORDER"]."\"> Voltar </a></p>";

 

	if ( ($_SESSION["COD_ADM"] == "") AND ($_SESSION["COD_PROF"] == "") AND ($_SESSION["COD_AL"] == "") )
	{
		echo "<p align='center'> <b>Casos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

	if (! isset($_REQUEST["COD_CASO"]))
		exit();
	
	if (! casoAcesso("", $_REQUEST["COD_CASO"]) )
		exit();
	
	$rsCon = caso($_REQUEST["COD_CASO"]);

	if (! $rsCon)
		exit();
	
	$linha = mysql_fetch_array($rsCon);
	
	
	echo "<p><b>Autor(es): </b>";
	
	$rsCon2 = casoAutores($_REQUEST["COD_CASO"]);
	
	if ($rsCon2)
		while ($linha2 = mysql_fetch_array($rsCon2))
			echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strToLower($linha2["NOME_PESSOA"]) . "</font>";

echo "</p><br><br></td></tr>";
$visto=getEstudodeCasosVistoPeloProfessor($_REQUEST["COD_CASO"]);
$config = getConfiguracoesCampo($_SESSION['codInstanciaGlobal']);
			foreach($campo as $codCampo=>$titulo) {
			 if (!empty($config[$codCampo])) {
				 if($config[$codCampo]->aparece!='0'){
					 if( Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage'])){
						 echo "<tr><td valign=\"top\" ><form name=\"form".$codCampo."\" method=\"post\" action=\"\" >";
						 echo "<input type=\"checkbox\" name=\"codCampo\" value=\"".$codCampo."\" onClick=\"if(this.checked){document.form".$codCampo.".acao.value='insereVisto';submit();}else{document.form".$codCampo.".acao.value='retiraVisto';submit();}\"";
						 if($visto[$codCampo])
						 echo " checked";
						 echo ">";
 						 echo "<input type=\"hidden\" name=\"codCampo\" value=\"".$codCampo."\">";
						 echo "<input type=\"hidden\" name=\"codCaso\" value=\"".$_REQUEST["COD_CASO"]."\">";
						 echo "<input  type=\"hidden\" name=\"acao\" value=\"\">";
						 echo "</form></td>";	
						
					 }

					echo "<td valign=\"top\"><p><b>{$config[$codCampo]->titulo}</b><br>"; 
					echo str_replace("\n", "<br>", $linha[$name[$config[$codCampo]->codCampo]])."</p><br><br></td></tr>";
				 }
			 }
			 else{
				 if( Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage']) ){
						 echo "<tr><td valign=\"top\" ><form name=\"form".$codCampo."\" method=\"post\" action=\"\" >";
						 echo "<input type=\"checkbox\" name=\"codCampo\" value=\"".$codCampo."\" onClick=\"if(this.checked){document.form".$codCampo.".acao.value='insereVisto';submit();}else{document.form".$codCampo.".acao.value='retiraVisto';submit();}\"";
						 if($visto[$codCampo])
						 echo " checked";
						 echo ">";
						 echo "<input type=\"hidden\" name=\"codCampo\" value=\"".$codCampo."\">";
						 echo "<input type=\"hidden\" name=\"codCaso\" value=\"".$_REQUEST["COD_CASO"]."\">";
						 echo "<input  type=\"hidden\" name=\"acao\" value=\"\">";
						 echo "</form></td>";	
						 
					 }

					echo "<td valign=\"top\"><p><b>{$titulo}</b><br>"; 
					echo str_replace("\n", "<br>", $linha[$name[$codCampo]])."</p><br><br></td></tr>";
				 }
			}

	$rsCon2 = casoComentario($_REQUEST["COD_CASO"]);
			
	if (!empty($rsCon2))
	{
		echo "<tr><td colspan='2' valign=\"top\"><p><b>Comentarios: </b></p>";

		while ($linha2 = mysql_fetch_array($rsCon2))
		{
		$imagemProf=verificaSePessoaProfessor($linha2["COD_PESSOA"], $_SESSION["codInstanciaGlobal"]);
		$imagemProf=mysql_fetch_array($imagemProf);

			echo "<p align=\"justify\" style=\"background-color: #ededed\">". str_replace("\n", "<br>", $linha2["TEXTO"])."<br><div align=Right><b>";

			if($imagemProf)
			{
				echo "<img src=\"../alunos/tipoProfessor.php?codTipoProfessor=".$imagemProf["codTipoProfessor"]."\" max-height=\"40\" max-width=\"60\" title='".$imagemProf["descTipoProfessor"]."'>";
			//echo Professor::iconeTipoProfessor($imagemProf["codTipoProfessor"],$imagemProf["descTipoProfessor"]);
			}


			echo $linha2["NOME_PESSOA"] ." - ". $linha2["DATA_MODIFICADA"]  ."</b></div></p>";



		 }
	}else {echo "<br><p align=\"justify\" style=\"background-color: #ededed\">Não há comentários no momento.</p>";}
	
			echo"<p align=\"center\"><a href=\"./casos_comentario_enviar.php?COD_CASO=".$linha["COD_CASO"]."\"> Enviar seu comentario</a></p>".
			"<br><br><br><br>".
				  "</td>".
                "</tr>".
             " </table>".			
		"</td>".
	"</tr>".
"</table>".
 
 "</body>".

"</html>";
