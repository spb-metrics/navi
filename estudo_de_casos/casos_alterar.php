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
include_once ($caminhoBiblioteca."/utils.inc.php");
session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {
  echo 'Sem permissao de interacao.'; die;
}


global $campo;
global $name;


function printHeader($params="") {
   global $url;
  echo "<html>".
	   "<head>".
		"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".

		"<link rel=\"stylesheet\" href=\".././cursos.css\" type=\"text/css\">".

		"<script language=\"JavaScript\" src=\".././funcoes_js.js\"></script>".
    "<script language='JavaScript' src='".$url."/js/editor.js'></script>".
	  "<script language='javascript' type='text/javascript' src='".$url."/js/tiny_mce/tiny_mce.js'></script>";


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
    "<tr><td>";
				  

if (! isset($_REQUEST["ORDER"]))
	$_REQUEST["ORDER"] = "";
			  
			echo"<p align=\"right\"><a href=\"./casos_listar.php?ORDER=".$_REQUEST["ORDER"]."\"> Voltar </a></p>";

  
	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION['codInstanciaGlobal'] == "" )
	{
		echo "<p align='center'> <b>Casos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

	if (! casoAcesso("ok", $_REQUEST["COD_CASO"]) )
		exit();
	
	if ( isset($_REQUEST["ALTERAR"]))
	{
		$COD = casoAltera($_REQUEST["COD_CASO"], $_REQUEST["AUTOR"], $_REQUEST["TITULO"], $_REQUEST["DIAGNOSTICO"], $_REQUEST["OBJETIVOS"], $_REQUEST["METAS"], $_REQUEST["EQUIPE"], $_REQUEST["ORCAMENTO"], $_REQUEST["CRONOGRAMA"], $_REQUEST["METODOLOGIA"],$_REQUEST["emConstrucao"]);

		if ($COD > 0)
		{
			echo "<script> location.href=\"./casos_mostrar.php?COD_CASO=". $COD. "\";</script>";			
		 }
		else
		{
			echo "ERRO ao alterar Estudo de Caso - <a href=\"javascript:history.back()\">Voltar</a>";
			exit();
		 }
	 }
	$casoTabela = caso($_REQUEST["COD_CASO"]);
	
	if (! $casoTabela)
	{
		echo "Erro na consulta ao Banco de Dados";
		exit();
	 }
	 
	$casoInf = mysql_fetch_array($casoTabela);
  

  echo"<form name=\"form\" method=\"post\" action=\"\">".
			"<table border=\"0\" cellspacing=\"15\" cellpadding=\"0\" style=\"width: 70%;\">".
			"<tr><td align=\"center\">". ativaDesativaEditorHtml()." </td></tr>".
      "<tr>".
			"<b>Autor(es): </b><br>".
			"-".
			"<select name=\"AUTOR[]\" style=\"width: 100%; height: 100px;\" multiple onClick=\" linha.selected=true; linha.selected=false; user.selected = true; \" >";


						$professoresTurma = listaProfessores();
						$autoresCaso      = CasoAutores($_REQUEST["COD_CASO"]);
						
						while ($linhaProfessoresTurma = mysql_fetch_array($professoresTurma))
						{
							if ($linhaProfessoresTurma["COD_PESSOA"] == $_SESSION["COD_PESSOA"] )
							{
								echo "<option id=\"user\" value=". $linhaProfessoresTurma["COD_PESSOA"] ." selected>". $linhaProfessoresTurma["NOME_PESSOA"] ."</option>";
							 }
							else
							{
								echo "<option value=". $linhaProfessoresTurma["COD_PESSOA"];
																
								while ($linhaAutoresCaso = mysql_fetch_array($autoresCaso))
								{
									if ($linhaProfessoresTurma["COD_PESSOA"] == $linhaAutoresCaso["COD_PESSOA"])
									{
										echo " selected";
										break;
									 }										
								 }
								
								if ($autoresCaso)
									mysql_data_seek($autoresCaso, 0); 
								
								echo ">". $linhaProfessoresTurma["NOME_PESSOA"] . "</option>";
							 }
							
						 }

						echo "<option id=\"linha\" value=0 >--------------------------------------------------</option>";
												
						$alunosTurma = listaAlunos();
						$autoresCaso = CasoAutores($_REQUEST["COD_CASO"]);
						
						while ($linhaAlunosTurma = mysql_fetch_array($alunosTurma))
						{
							if ($linhaAlunosTurma["COD_PESSOA"] == $_SESSION["COD_PESSOA"])
							{
								echo "<option id=\"user\" value=". $linhaAlunosTurma["COD_PESSOA"] ." selected>". $linhaAlunosTurma["NOME_PESSOA"] ."</option>";
							 }
							else
							{
								echo "<option value=". $linhaAlunosTurma["COD_PESSOA"] ;
								
								while ($linhaAutoresCaso = mysql_fetch_array($autoresCaso))
								{
									if ($linhaAlunosTurma["COD_PESSOA"] == $linhaAutoresCaso["COD_PESSOA"])
									{
										echo " selected";
										break;
									 }									
								 }
								
								if ($autoresCaso)
									mysql_data_seek($autoresCaso, 0); 
								
								echo ">". $linhaAlunosTurma["NOME_PESSOA"] ."</option>";
							 }
						 }
				
					echo"</select>".
					"Para selecionar os autores dentre os alunos dessa turma: <br><br>".
                    "Mantenha pressionada a tecla 'CTRL' e escolha os autores ".
                    "com o mouse.". 
					"</td></tr>";
					





$getVisto=getEstudodeCasosVistoPeloProfessor($_REQUEST["COD_CASO"]);

$config = getConfiguracoesCampo($_SESSION['codInstanciaGlobal']);
			foreach($campo as $codCampo=>$titulo) {
			 if (!empty($config[$codCampo])) {
				 if($config[$codCampo]->aparece!='0'){
					echo"<tr>".
							"<td>".
							"<p><b>{$config[$codCampo]->titulo}</b></p>".
						"<textarea name=\"{$name[$config[$codCampo]->codCampo]}\" title=\"".$getVisto[$codCampo]->frase."\"style=\"width: 100%; height: 100px; ".$getVisto[$codCampo]->style." \"".$getVisto[$codCampo]->visto.">".$casoInf[$name[$config[$codCampo]->codCampo]]."</textarea>".
							"</td>".
						"</tr>";
				 }
				}
			else {
				echo"<tr>".
						"<td>".
						"<p><b>{$titulo}</b></p>".
						"<textarea name=\"{$name[$codCampo]}\" title=\"".$getVisto[$codCampo]->frase."\"style=\"width: 100%; height: 100px; ".$getVisto[$codCampo]->style."\"".$getVisto[$codCampo]->visto.">".$casoInf[$name[$codCampo]]."</textarea>".
						"</td>".
					"</tr>";
				}
			}

			echo"<tr>".
				"<td align=\"right\">".
				"<input type=\"hidden\" value=\"ok\" name=\"ALTERAR\">".
					"<input type=\"hidden\" value=\"".$_REQUEST["COD_CASO"]."\" name=\"COD_CASO\">".
					"<input type=\"button\" value=\"Deixar Em Construção\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=1';submit();\">&nbsp".
					"<input type=\"button\" value=\"Validar/Entregar\" onclick=\"document.form.action='".$_SERVER["PHP_SELF"]."?emConstrucao=0';submit();\">&nbsp".
					
					"<input type=\"reset\" value=\"Cancelar\" onclick=\"javascript:history.back()\">".
				"</td>".
			"</tr>".
			"</table>".
			"</form>".
	
			"	  </td>".
             "   </tr>".
              "</table>".				
			"</td>".
		"</tr>".
	"</table>".			
"</body>".
"</html>";
?>
