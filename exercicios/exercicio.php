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


/**arquivo pertencente ao tools
*esse arquivo vai premitir editar/criar tanto questões quanto provas (conjunto de questões)
*/
include("../config.php");
include($caminhoBiblioteca."/questao.inc.php");
include($caminhoBiblioteca."/exercicios.inc.php");
session_name(SESSION_NAME); session_start(); security();

function printHeader($params="") {
  global $url,$urlCss;
  echo "<html><head>".
		   "<link rel=\"stylesheet\" href=\"".$urlCss."/exercicio.css\" type=\"text/css\">";
  echo  " <script language=\"JavaScript\" src=\"".$url."/js/exercicio.js\"></script>".
       " <script language=\"JavaScript\" src=\"".$url."/js/editor.js\"></script>".
       " <script language=\"JavaScript\" src=\"".$url."/js/dateFormat.js\"></script>".
       " <script language=\"javascript\" type=\"text/javascript\" src=\"".$url."/js/tiny_mce/tiny_mce.js\"></script>";
  echo $_SESSION['configMathml']['script'];
  if (!empty($params["titulo"]))
  echo "<title>{$params["titulo"]}</title>";
  echo "</head>";
  echo "<body class='bodybg'".$params["body"].">";
  echo "<table  width='90%' border='0' cellspacing='0' cellpadding='0' align='center'>";
  echo "<tr align='center'><td class='menu'>".$params["tituloPagina"]."</td></tr>";
  echo "<tr align='center'><td valign='middle'>";
}
 $nivel = getNivelAtual();
 $acao = $_REQUEST["acao"];
 if(!empty($_REQUEST["codExercicio"])) $codExercicio=$_REQUEST["codExercicio"];
 if(!empty($_REQUEST["codQuestao"])) $codQuestao=$_REQUEST["codQuestao"];
 
 
 switch ($acao){

/**
* P-GINA DEFAULT PARA O USU-RIO DECIDIR SE QUER CRIAR EXERCICIO OU SE QUER CRIAR QUESTiES
*/
   case "criar":
     $params["tituloPagina"]="Criar Exerc&iacute;cio ou Quest&otilde;es";
		  printHeader($params);
      echo "<table  width=\"85%\"  align=\"center\"><tr>".
           "<td colspan=\"3\" align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?acao=criar_exercicio\">Criar novo Exerc&iacute;cio</a>|".
           "<a href=\"".$_SERVER['PHP_SELF']."?acao=criar_questao\">Criar novas Quest&otilde;es</a></td>".
           "<td colspan=\"2\" align=\"right\"><a href=\"./index.php\">Voltar</a></td>".
           "</tr></table>";
       echo "</td></tr></table></body></html>";
      break;

/**
*CRIAÃ+O DAS QUESTiES PARA O BANCO DE QUESTiES
*/
    case "criar_questao":
        $params["tituloPagina"]= "Criar Quest&atilde;o";
        printHeader($params);
        echo "<br><p align=\"left\">";
        if($_REQUEST["voltar"]=="naoOk") echo "<a href=\"".$_SERVER[PHP_SELF]."\">"; 
        else echo "<a href=\"javascript:history.back()\">";
        echo "<img src=\"".$urlImagem."/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></p>";
        $questao = new questao($codQuestao);
        $questao->escolheTipoQuestao();
        echo "</td></tr></table></body></html>";
    break;
/**
*  DESENHO DA QUESTAO DEPENDE DO TIPO QUE O USU-RIO ESCOLHEU
*/
    case "desenhaQuestao":
      $classe= $_REQUEST["classeQuestao"];
      $obj = new $classe();
      $params["tituloPagina"]= "Quest&atilde;o do Tipo ".$obj->tipoQuestao;
      printHeader($params);
      echo "<br><p align=\"left\">";
      echo "<a href=\"".$_SERVER[PHP_SELF]."\">";
      echo "<img src=\"".$urlImagem."/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></p>";
      $acao=$_SERVER[PHP_SELF]."?acao=insere_bd";
      $obj->mostraLayout($acao,$_REQUEST["codQuestao"]);
      echo "</td></tr></table></body></html>";
    break;
    
/**
*FORMAÃ+O DOS EXERCICIOS A PARTIR DO BANCO DE QUESTiES
*/
    case "criar_exercicio":
        $params["tituloPagina"]= "Criar Exerc&iacute;cio";
        $acao=$_SERVER[PHP_SELF]."?acao=insere_bd";
        printHeader($params);
        //echo "<br><p align=\"left\">";
        //if($_REQUEST["voltar"]=="naoOk") echo "<a href=\"javascript:history.back()\">";
        //else 
        echo "<p align='left'><a href=\"".$_SERVER[PHP_SELF]."\">";
        echo "<img src=\"".$urlImagem."/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></p>";
        $exercicio = new Exercicio();
        $exercicio->mostraLayout($acao,$codExercicio);
        echo "</td></tr></table></body></html>";
    break;
/**
*  CHAMADA DE FUNÃiES PARA GRAVAR NO BANCO DE DADOS
*/
   case "insere_bd":
      $classe=$_REQUEST["tipoQuestao"];
      $obj = new $classe();
      $ok=$obj->gravar($_SESSION["COD_PESSOA"],$_REQUEST);
      if($classe == Exercicio)  echo "<script>location.href=\"".$_SERVER[PHP_SELF]."?acao=criar_exercicio&codExercicio=".$ok."\";</script>";
      else echo "<script>location.href=\"".$_SERVER[PHP_SELF]."?acao=criar_questao&voltar=naoOk\";</script>";
      
   break;

/**
* CASO DEFAULT l
*/
   default;
      if(!empty($_REQUEST["FILTRO"])){$filtro=$_REQUEST["FILTRO"];}
      if(!empty($_REQUEST["classe"])){$classe=$_REQUEST["classe"];}
      $params["tituloPagina"]="Editar Exercicio ou Quest&otilde;es";
		  printHeader($params);
      echo "<br><p align=\"left\">";
      echo "<a href=\"index.php\">";
      echo "<img src=\"".$urlImagem."/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></p>";
      $obj = new Exercicio();
      echo $obj->layoutEditarExerciciosEQuestoes($nivel->nome,$classe);
      if ($filtro == "instanciaAtual"){	echo "<tr><td><br><br>&nbsp;&nbsp;&nbsp; <b> Textos  da ".$nivel->nome." : </b> <br><br></td></tr>";}
      if($filtro=="meus") $quem=$_SESSION["COD_PESSOA"]; else $quem="";
      if(!empty($classe)){
        if($classe=="Questao"){
           $obj=new Questao($codQuestao);
           $remover="exercicioLocal.php?opcao=removerGeral";
           $alterar="".$_SERVER['PHP_SELF']."?acao=desenhaQuestao";
           $linha=$obj->listaQuestaoAdm($_SESSION["codInstanciaGlobal"],$filtro,$quem);
        }
        else{
          $remover="exercicioLocal.php?opcao=removerGeral";
          $alterar="".$_SERVER['PHP_SELF']."?acao=criar_exercicio";
          $linha= $obj->listaExercicioAdm($_SESSION["codInstanciaGlobal"],$filtro,$quem);
        }
        
          echo "<tr><td>";
          echo $obj->layoutLista($linha,$remover,$alterar);
          echo "</td></tr>";   
        
      
      }
        echo "</body></html>";
  break;
 }
?>
