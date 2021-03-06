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
include_once("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/estudo_de_casos.inc.php");
include_once ($caminhoBiblioteca."/utils.inc.php");
session_name(SESSION_NAME); session_start(); security();
global $campo;
global $name;

function printHeader($params="") {
  global $url;
  echo "<html>".
	     "<head>".
  	   "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
		   "<link rel=\"stylesheet\" href=\".././cursos.css\" type=\"text/css\">".
	     "<script language=\"JavaScript\" src=\".././funcoes_js.js\"></script>".
       "<script language=\"JavaScript\" src=\"".$url."/js/editor.js\"></script>".
	     "<script language=\"javascript\" type=\"text/javascript\" src=\"".$url."/js/tiny_mce/tiny_mce.js\"></script>";

  if (!empty($params["titulo"]))
    echo "<title>{$params["titulo"]}</title>".
		 "</head>".
		 "<body {$params["body"]}>".
		 "<h3 class=\"titulo\">{$params["tituloPagina"]}</h3>";
		

}


$params["titulo"]="N&uacute;cleo de Aprendizagem Virtual";
 printHeader($params);


echo"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">".
	"<tr>".
	"<td width=\"200\" valign=\"top\">&nbsp;&nbsp". 
//			include "../noticias_menu_esq_turma.php" 
	"</td>".
	"<td width=\"540\" valign=\"top\">". 
    "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">".
    "<tr><td>".
    "<p align=\"right\"><a href=\"./casos_listar.php\"> Voltar </a></p>";

 	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION['codInstanciaGlobal'] == "")
	{
		echo "<p align='center'> <b>Casos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }
  if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {
    echo 'Sem permissao de interacao.'; die;
  }
  
	if (isset ($_REQUEST["CASOENVIADO"]))
	{
		if ($_REQUEST["TITULO"] != "")
		{
			$COD = casoEnvia($_REQUEST["AUTOR"], $_REQUEST["TITULO"], $_REQUEST["DIAGNOSTICO"], $_REQUEST["OBJETIVOS"], $_REQUEST["METAS"], $_REQUEST["EQUIPE"], $_REQUEST["ORCAMENTO"], $_REQUEST["CRONOGRAMA"], $_REQUEST["METODOLOGIA"], $_REQUEST["emConstrucao"]);
			
			if ($COD > 0)
			{
				echo "<script> location.href=\"./casos_mostrar.php?COD_CASO=". $COD. "\";</script>";
			 }
			else
			{
				echo "ERRO na Inser��o<br> <a href=\"javascript:history.back()\">Voltar</a>";
				exit();
			 }
		 }
		else
		{
			echo "<script> alert('O Estudo de Caso deve possuir um T�tulo'); </script>";
		 }
	 }
 	
		echo"<form name=\"form\" method=\"post\" action=\"\">".
			"<table border=\"0\" cellspacing=\"15\" cellpadding=\"0\" style=\"width: 70%;\">".
			"<tr><td>". ativaDesativaEditorHtml()."</td></tr>".
      "<tr>".
			"<b>Autor(es):</b><br>".
			"-".
			"<select name=\"AUTOR[]\" style=\"width: 100%; height: 100px;\" multiple onClick=\" linha.selected=true; linha.selected=false; user.selected = true; \" >";
				
	//			"<select name=\"AUTOR[]\" style=\"width: 100%; height: 100px;\" multiple onClick=\"\" >";


				
					
						$rsCon = listaProfessores();
													
						if ($rsCon)
						{				
							while ($linha = mysql_fetch_array($rsCon))
							{
								if ($linha["COD_PESSOA"] == $_SESSION["COD_PESSOA"])
									echo "<option name=\"user\" id=\"user\" value=". $linha["COD_PESSOA"] ." selected>". $linha["NOME_PESSOA"] ."</option>";
								else 
									echo "<option 			value=". $linha["COD_PESSOA"] .">". $linha["NOME_PESSOA"] ."</option>";
							 } 
						 }

						echo "<option name=\"linha\" id=\"linha\" value=0 >--------------------------------------------------</option>";

						$rsCon = listaAlunos();
						
						if ($rsCon)
						{
							while ($linha = mysql_fetch_array($rsCon))
							{
								if ($linha["COD_PESSOA"] == $_SESSION["COD_PESSOA"])
									echo "<option id=\"user\" value=". $linha["COD_PESSOA"] ." selected>". $linha["NOME_PESSOA"] ."</option>";
								else 
									echo "<option 			value=". $linha["COD_PESSOA"] .">". $linha["NOME_PESSOA"] ."</option>";							
							 }
						 }

				
				echo"</select>".
					"Para selecionar os autores dentre os alunos dessa turma: <br><br>".
                    "Mantenha pressionada a tecla 'CTRL' e escolha os autores ".
                    "com o mouse.". 
					"</td></tr>";


			$config = getConfiguracoesCampo($_SESSION['codInstanciaGlobal']);
			foreach($campo as $codCampo=>$titulo) {
			 if (!empty($config[$codCampo])) {
				 if($config[$codCampo]->aparece!='0'){
					echo"<tr>".
							"<td>".
							"<p><b>{$config[$codCampo]->titulo}</b></p>".
							"<textarea name=\"{$name[$config[$codCampo]->codCampo]}\" style=\"width: 100%; height: 100px;\"></textarea>".
							"</td>".
						"</tr>";
				 }
				}
			else {
				echo"<tr>".
						"<td>".
						"<p><b>{$titulo}</b></p>".
						"<textarea name=\"{$name[$codCampo]}\" style=\"width: 100%; height: 100px;\"></textarea>".
						"</td>".
					"</tr>";
				}
			}
			
			echo"<tr>".
				"<td align=\"right\">".
					"<input type=\"hidden\" name=\"CASOENVIADO\" value=\"casoEnviado\">&nbsp".
					"<input type=\"button\" value=\"Deixar Em Constru��o\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=1'; submit();\">&nbsp".
					"<input type=\"button\" value=\"Validar/Entregar\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=0';submit();\">&nbsp".
					"<input type=\"reset\" value=\"Cancelar\" onclick=\"javascript:history.back()\">".
				"</td>".
				"</tr>".
				"</table>".
				"</form>".
				  "</td>".
                "</tr>".
              "</table>".				
			"</td>".
		"</tr>".
	"</table>".			
"</body>".
"</html>";
