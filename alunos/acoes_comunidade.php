<?php

include("../config.php");
include($caminhoBiblioteca."/pessoa.inc.php");
include($caminhoBiblioteca."/professor.inc.php");
include($caminhoBiblioteca."/aluno.inc.php");
include($caminhoBiblioteca."/administraPessoa.inc.php");
session_name(SESSION_NAME); session_start(); security();

$nivelAtual = getNivelAtual();

$codInstanciaNivelAtual = getCodInstanciaNivelAtual();
$instanciaNivel = new InstanciaNivel($nivelAtual,$codInstanciaNivelAtual);
$acao  = $_REQUEST['acao'];


switch ($acao) {

  case "sairComunidade":
     /*CONFIRMAÇÃO QUE O ALUNO SAI DA COMUNIDADE*/
   	htmlTop("Saindo  de ".$nivelAtual->nome);
		
		echo "<p>Voc&ecirc; realmente deseja sair da(o) ".$nivelAtual->nome." ".$instanciaNivel->nome."? </p>";
		echo "<br>";
		echo "<input type=\"button\" value=\"Sair\" onclick=\"window.location.href = '".$_SERVER["PHP_SELF"]."?acao=sairComunidadeMake';\">";
		echo "<input type=\"button\" value=\"Cancelar\" name=\"cancel\" onclick=\"window.location.href = '".$url."/alunos/index.php?';\">";		
 
		htmlBottom();
    break;
    
    
  case "sairComunidadeMake":
    /** EXCLUSAO DO ALUNO DA COMUNIDADE NO BANCO DE DADOS **/
		htmlTop("Saindo de ".$nivelAtual->nome);
   
    //$nivel= new $_SESSION['userRole']();
    if($_SESSION["userRole"]==ALUNO){ $pessoa= new Aluno($_SESSION["COD_AL"]);}
    if($_SESSION["userRole"]==PROFESSOR){$pessoa= new Professor($_SESSION["COD_PROF"]);}
    if($_SESSION["userRole"]==ADM_NIVEL){ $pessoa= new AdministradorNivel($_SESSION['COD_ADM']);}
	  if($_SESSION["userRole"]==ADMINISTRADOR_GERAL){$pessoa = new Pessoa($_SESSION['COD_PESSOA']);}
    $ok = $pessoa->retirarInstancia($nivelAtual,$codInstanciaNivelAtual);

		if ($ok) {
		   $instanciaNivel = instanciaNivelInicial($_SESSION['COD_PESSOA']);
		   $sucesso = $pessoa->deleteInstanciaNivelInicial($_SESSION['COD_PESSOA'],$nivelAtual, $instanciaNivel);
		
      echo "<p>Sa&iacute;da de ".$nivelAtual->nome." com sucesso!</p>";
		}
		else {
		  //echo 'ee'.mysql_error();
			echo "<p>Houve algum erro ao excluir a inst&acirc;ncia.</p>";
		}

    echo "<input type=\"button\" value=\"Voltar para o NAVi\" onclick=\"window.top.location.href = '".$url."/index.php';\">";

		htmlBottom();
    break;
    
    
  case "excluirComunidade":
		/** CONFIRMACAO DA EXCLUSAO DA INSTANCIA **/
    if (!Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage'])) {
      echo 'Permissao negada'; die;
    }
		htmlTop("Exclus&atilde;o de ".$nivelAtual->nome);

		echo "<p>Voc&ecirc; realmente deseja excluir a(o) ".$nivelAtual->nome.','.$instanciaNivel->nome."? Essa a&ccedil;&atilde;o n&atilde;o poder&aacute; ser desfeita.</p>";
		echo "<br>";
		echo "<input type=\"button\" value=\"Excluir\" onclick=\"window.location.href = '".$_SERVER["PHP_SELF"]."?acao=excluirComunidadeMake';\">";
		echo "<input type=\"button\" value=\"Cancelar\" name=\"cancel\" onclick=\"window.location.href ='".$url."/alunos/index.php';\">";		

		htmlBottom();
	  break;

	case "excluirComunidadeMake":
		/** EXCLUSAO DA INSTANCIA DO BANCO DE DADOS **/
    if (!Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage'])) {
      echo 'Permissao negada'; die;
    }

		htmlTop("Exclus&atilde;o de ".$nivelAtual->nome.'/'.$instanciaNivel->nome);

		$sucesso = $nivelAtual->excluiInstancia($codInstanciaNivelAtual);

		if ($sucesso) {
			echo '<p>'.$nivelAtual->nome.' exclu&iacute;da com sucesso!</p>';
		}
		else {
			echo '<p>Houve algum erro ao excluir a '.$nivelAtual->nome.'</p>';
		}
    echo "<input type=\"button\" value=\"Voltar para o NAVi\" onclick=\"window.top.location.href = '".$url."/index.php';\">";
		htmlBottom();
	  break;
}


function htmlTop($title) {
  global $nivelAtual,$nivelGerenciado,$urlCss,$urlImagem,$codNivelPrimeiro;
  echo "<html>".
		   "<head>".
			 "<title>Gerenciamento de Instancias</title>".
			 "<link rel=\"stylesheet\" href=\"".$urlCss."/padraogeral.css\" type=\"text/css\">".
			 "</head>".
			 "<body text=\"#000000\" class=\"bodybg\" style=\"width:95%; text-align:center;\">";
  echo "<div style=\"font-weight: bold; font-size: 14px; text-align: center\">".$title."</div>";
}

function htmlBottom() {
	echo "</body>";
	echo "</html>";
}

?>
