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

//ini_set("display_errors",1);
//error_reporting(E_ALL);
include ("../config.php");
//include_once($caminhoBiblioteca."/autenticacao.inc.php");
include($caminhoBiblioteca."/exercicios.inc.php");
session_name(SESSION_NAME); session_start(); security();

echo "<html><head>";
echo "<title>Ferramentas de Administrador - Exercicios</title>";		
echo "<link rel=\"stylesheet\" href='".$urlCss."/padraogeral.css' type=\"text/css\">";
echo "</head>";
echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\" class=\"bodybg\" style=\"background-image: none\">";
switch ($_REQUEST["opcao"]) { 

case "Incluir":
   $exercicio= new Exercicio(); 
		if ( $_REQUEST["LOCAL"] == "" ) {
			  $sucesso = $exercicio->ExeLocalInsere($_REQUEST["codExercicio"], $_SESSION["codInstanciaGlobal"]);
			  if ( $sucesso )	echo "<br><br><p align=center>Exercicio Publicado<br>\n";
				else echo "<br><br><p align=center>ERRO ao publicar exercicio <br>";
				echo "<a href=\"exercicioLocal.php?codExercicio=".$_REQUEST["codExercicio"]."\">Voltar</a>\n";
		
		}
	 break;
case "removerLocal":
        $url= $_SERVER["PHP_SELF"];
        $nivel = getNivelAtual();
        $exe= new Exercicio();
				$sucesso = $exe->ExercicioLocalRemove($_REQUEST["codExercicio"], $_REQUEST["codInstanciaGlobal"]);
				if ( $sucesso )	{ echo "<br><br><p align=center>Local Removido<br>\n";}
				else{	echo "<br><br><p align=center>ERRO na Remo��o<br>";	 }
				if($_REQUEST['PAGINA']=='instancia'){
					echo "<a href=\"javascript:window.close()\">fechar</a>\n";
					echo "<script> window.opener.location.href=\"index.php\"; </script>";
	
				}
        else{
				echo "<a href=\"exercicioLocal.php?codExercicio=".$_REQUEST["codExercicio"]."\" >Voltar</a>\n";
				}
  break;
	case "removerGeral":
	  if(!empty($_REQUEST["codExercicio"])){
     $exe=new Exercicio();
     $exe->excluirExercicio($_REQUEST["codExercicio"]);
      echo "<script>location.href=\"exercicio.php?\";</script>";
    }
    if(!empty($_REQUEST["codQuestao"])){
     $questao=new Questao($_REQUEST["codQuestao"]);
     $questao->excluirQuestao($_REQUEST["codQuestao"]);
     echo "<script>location.href=\"exercicio.php?\";</script>";
    }
  break;

  default:
    $nivelAtual = getNivelAtual();
    $exercicio = new Exercicio();
    $instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
    if(!empty($_REQUEST["codExercicio"])) $codExercicio=$_REQUEST["codExercicio"];
    echo "<table width=\"100%\">";
		echo "<tr><td>&nbsp;</td>";
    echo "<td align=\"left\" width=\"120px\"><U>Local</u></td>";
    echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";
		if(!empty($_REQUEST["codExercicio"])){
       $numNiveisImprime = 2;
	     $exercicio->publicadaNestaInstancia=0;//parametro de saida
	     echo $exercicio->imprimeLocaisExercicio($_SERVER["PHP_SELF"],"codExercicio",$codExercicio,$numNiveisImprime,$_SESSION["codInstanciaGlobal"]);
      
    }
    echo "<tr><td align=\"left\" colspan=\"3\" height=\"33px\">";
	  if (!$exercicio->publicadaNestaInstancia) {
			echo "<a href=\"exercicioLocal.php?opcao=Incluir&codExercicio=".$codExercicio."\">";
			echo "Incluir em ".$instanciaAtual->getAbreviaturaOuNomeComPai();
			echo "</a>";
	  }
		echo "</td></tr></table>";
  break;
}
echo "</body></html>";
 
 

?>
