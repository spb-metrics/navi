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
/*
ini_set("display_errors",1);
error_reporting(E_ALL);
*/

include_once("../config.php"); 
include_once($caminhoBiblioteca."/pessoa.inc.php");
include_once($caminhoBiblioteca."/menu.inc.php");
include_once($caminhoBiblioteca."/defaultpage.inc.php");
include_once($caminhoBiblioteca."/portfolio.inc.php");
include_once($caminhoBiblioteca."/utils.inc.php");
session_name(SESSION_NAME); session_start(); security();

function printHeader($params="") {
  global $url;
  echo "<html>".
		   "<head>".
		   //"<link rel=\"stylesheet\" href=\"./sca.css\" type=\"text/css\">".
		   "<link rel=\"stylesheet\" href=\"".$url."/css/configuracao.css\" type=\"text/css\">";
		   "<link rel=\"stylesheet\" href=\"".$url."/css/padraogeral.css\" type=\"text/css\">";
	echo "<script language=\"JavaScript\" src=\"".$url."/js/utils.js\"></script>";	   
       if (!empty($params["titulo"]))
  echo "<title>{$params["titulo"]}</title>";
  echo "</head>";
  echo "<body class=\"bodybg\"{$params["body"]}>";
  echo "<table  width=\"700\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
  echo "<tr><td><br></td></tr>";
  echo "<tr align=\"center\"><td class=\"nomeInstancia\">{$params["tituloPagina"]}</td></tr>";
  echo "<tr align=\"center\"><td valign='middle'>";
} 
 
$nivelAtual = getNivelAtual();
$instancia = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]); 
$instanciaAtual = new InstanciaNivel($nivelAtual,$instancia->codInstanciaNivel);
$params["tituloPagina"]="Painel de Controle - ".$instanciaAtual->getAbreviaturaOuNomeComPai();

printHeader($params);
$menuInicial =  getMenuInicial($_SESSION["codInstanciaGlobal"]);

$okMenuInicial = mysql_num_rows($menuInicial); 
$menuInicial = mysql_fetch_array($menuInicial);
$menus = getItensMenuAtivos($_SESSION["codInstanciaGlobal"]);

$tipoPublicacao=verificaPermissaoParticularGeral ($_SESSION['codInstanciaGlobal']);

switch($_REQUEST["acao"]) {
  case "":
    echo "<form name=\"frm_configuracao\" method=\"post\" action=\"".$_SERVER["PHP_SELF"]."?acao=submit\">";
    
    echo "<table class=\"tabelaFundo\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>"; 
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">";
    if (Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage'])) {
      echo "<tr><td class=\"titulo\" colspan=\"2\">Recurso Inicial</td></tr>";
      echo "<tr><td class=\"branco\" colspan=\"2\" align=\"center\">";
      if(!mysql_fetch_array($menus)){
       echo "<font color=\"red\" ><br>Não há menus em ". $instanciaAtual->getAbreviaturaOuNomeComPai()."<br><br></font>";
      }
      $menus = getItensMenuAtivos($_SESSION["codInstanciaGlobal"]);
      echo "<table width=\"100%\"><tr>";
      while($itensMenuAtivos = mysql_fetch_array($menus)){
        if ($itensMenuAtivos["codMenu"] == $menuInicial['codMenuInicial']) {
          echo "<td><table><tr><td align=\"center\"><img src='".$urlImagem."/abaixo.png'></td></tr><tr><td><img src='".$urlImagem."/".$itensMenuAtivos["imagem"]."' title='".$itensMenuAtivos["descricaoMenu"]."' border='1'></td></tr></table></td>";
        }
        else {
          echo "<td><a href='recursos_fixos.php?acao=submit&reloadpage=1&codMenu=".$itensMenuAtivos["codMenu"]."'><img src='".$urlImagem."/".$itensMenuAtivos["imagem"]."' title='".$itensMenuAtivos["descricaoMenu"]."' border='no'></td>";
        }       
      }
      echo "</tr></table>";
      echo "<tr><td class=\"titulo\">Geral</td>";
      echo "<td class=\"titulo\" >Inscri&ccedil;&#337;es</td></tr>";  
      echo "<tr><td  class=\"branco\">";
      echo "<table>";
      echo "<tr><td><a href=\"menu.php\"><img src=\"".$urlImagem."/edita.gif\" border=\"no\"></a>&nbsp;</td>".
                       "<td><a href=\"menu.php?acao=criar\"><img src=\"".$urlImagem."/criar.gif\" border=\"no\"></a></td><td> Menu</td></tr>";
      //adm nivel pode editar apenas niveis-filho,
      //entao verificamos se o nivel atual nao é o nivel mais acima na navegacao
      if ($_SESSION['userRole']==ADM_NIVEL) {
        $primeiroNivel = $_SESSION['navegacao']->getNivelInicial(); 
        if ($nivelAtual->codNivel!=$primeiroNivel->codNivel) {
          echo '<tr><td>';
          echo "<a href=\"./instanciasNiveis.php?codNivel=".$n->codNivel."\"><img src=\"".$urlImagem."/edita.gif\" border=\"no\"></a></td>";
          echo "<td><a href=\"./instanciasNiveis.php?OPCAO=Inserir&codNivel=".$nivelAtual->codNivel."\"><img src=\"".$urlImagem."/criar.gif\" border=\"no\"></a></td>"; 
          echo "<td> ".$nivelAtual->nome." </td></tr>";
        }
      } 
      else {
          echo '<tr><td>';
          echo "<a href=\"./instanciasNiveis.php?codNivel=".$n->codNivel."\"><img src=\"".$urlImagem."/edita.gif\" border=\"no\"></a></td>";
          echo "<td><a href=\"./instanciasNiveis.php?OPCAO=Inserir&codNivel=".$nivelAtual->codNivel."\"><img src=\"".$urlImagem."/criar.gif\" border=\"no\"></a></td>"; 
          echo "<td> ".$nivelAtual->nome." </td></tr>";
      }

      $filhos = $nivelAtual->getSubNiveis();
      foreach($filhos->records as $n) {
        echo "<tr><td><a href=\"./instanciasNiveis.php?codNivel=".$n->codNivel."\"><img src=\"".$urlImagem."/edita.gif\" border=\"no\"></a></td>".
                       "<td><a href=\"./instanciasNiveis.php?OPCAO=Inserir&codNivel=".$n->codNivel."\"><img src=\"".$urlImagem."/criar.gif\" border=\"no\"></a></td>". 
                       "<td> ".$n->nome." </td></tr>"; 
      }
      
      echo "<tr><td colspan=\"3\"><a href='".$url."/tools/pesquisaavaliacaoaluno.php'>Criar as avalia&ccedil;&otilde;es da inst&acirc;ncia</a></td></tr>";
      echo "<tr><td colspan=\"3\"><a href='".$url."/pesquisaavaliacaoinstanciapeloaluno/visualizaavaliacoes.php'>".
           "Ver resultados das avalia&ccedil;&otilde;es</a></td></tr>";
      echo "<tr><td colspan=\"3\"><a href='".$url."/tools/admPessoa/index.php?iniciarNavegacao=1'>".
           "Administrar Pessoas</a></td></tr>";
      echo "</table></td>";
      getInscricaoPainelControle($nivelAtual);
      echo "<tr><td class=\"branco\">";   
      echo "<table width=\"100%\">";
      echo "<tr><td class=\"titulo\">Configura&ccedil;&#259;o Portf&oacute;lio</td></tr>";
      echo "<tr><td class=\"celulaClara\"><input type=\"checkbox\" name=\"permiteArquivoGeral\" value=\"1\"";
      if($tipoPublicacao['permiteArquivoGeral']) echo "checked";
      echo">Geral</td></tr>";
      echo "<tr><td class=\"celulaEscura\"><input type=\"checkbox\" name=\"permiteArquivoParticular\" value=\"1\"";
      if($tipoPublicacao['permiteArquivoParticular']) echo "checked";
      echo ">Particular</td></tr>";
      echo "<tr><td class=\"titulo\">Ferramentas Edi&ccedil;&#259;o</td></tr>";
      echo "<tr><td class=\"celulaClara\"><input type=\"checkbox\" name=\"usaMathMl\" value=\"1\"";
      
      if($instancia->getUsoMathml())  { echo 'checked'; }
      echo ">Usar Editor MathMl</td></tr>";
        //  echo "<tr><td class=\"celulaEscura\"><input type=\"checkbox\" name=\"editorHtml\" value=\"1\" style=\"visibility:hidden;\">Usar Editor HTML</td></tr>";
      echo "</table>";   
      echo "</td>";
      echo "<td class=\"branco\">";   
      echo "<table width=\"100%\">";
      //Ativa os recursos fixos da plataforma
      echo "<tr><td class=\"titulo\">Ativar Recursos Fixos</td></tr>";
      echo "<tr><td class=\"celulaClara\"><input type=\"checkbox\"  name=\"suporteTecnico\" value=\"1\" ";
      if ($menuInicial['suporteTecnico'] or empty($okMenuInicial)) { echo 'checked'; } 
      echo ">".
            " <img src='".$urlImagem."/suportetecnico.gif' title='Suporte Técnico:\n\rAbrir chamado para a equipe técnica resolver dúvidas ou problemas' border='0'></td></tr>";
      echo "<tr><td class=\"celulaEscura\"><input type=\"checkbox\"  name=\"indicadores\" value=\"1\"";
      if ($menuInicial ['indicadores'] or empty($okMenuInicial)) { echo 'checked'; }
      
      echo ">".
            " <img src=".$urlImagem."/indicadores.gif title='Indicadores:\n\rIndicadores da utilização dos recursos da plataforma.' border='0'></td></tr>";
      echo "<tr><td class=\"celulaClara\"><input type=\"checkbox\"  name=\"correio\" value=\"1\" ";
      
      if ($menuInicial['correio'] or empty($okMenuInicial)) { echo 'checked'; }
      echo ">".
            " <img src='".$urlImagem."/correio.gif' border='0' title='Correio:\n\rComunicação via mensagens. Pode copiar as mensagens para seu correio internet.'ii></td></tr>";
      echo "</table>"; 
      echo "</td></tr>";
      
      echo "<tr><td class=\"Titulo\" >Pessoal</td><td class=\"Titulo\">Alinhamento de Conte&uacute;dos</td></tr>"; 
      echo "<tr><td class=\"branco\" ><table width=\"100%\">";
      
      //Disponibiliza o uso do editor HTML 
      echo "<tr><td class=\"celulaEscura\" ><input type=\"checkbox\" name=\"ativaEditorHTML\"";
      if(getUsoEditorHTML($_SESSION['COD_PESSOA']))  { echo 'checked'; }
      echo " value=\"1\">Usar Editor HTML</td></tr>";
      echo "</table></td>";
      
      // Disponibiliza o alinhamento de conteúdos 
      $alinhamento = $instancia->getAlinhamentoConteudos();
      echo "<td class=\"branco\"><table width=\"100%\"><tr>";
      echo "<td class=\"celulaEscura\" ><input type=\"radio\" name=\"alinhar\"  ";
      if ($alinhamento=='left') { echo ' checked '; }
      echo 'value="left">';
      echo "<img src='".$urlImagem."/alinharEsquerda.png' border='0' title='alinhar conteúdos a esquerda' alt='alinhar a esquerda'>";

      echo "&nbsp&nbsp&nbsp <input type=\"radio\" name=\"alinhar\"  ";
      if ($alinhamento=='center') { echo ' checked '; }
      echo 'value="center">';
      echo "<img src='".$urlImagem."/centralizar.png' border='0' title='centralizar conteúdos' alt='centralizar'>";

      echo "&nbsp&nbsp&nbsp<input type=\"radio\" name=\"alinhar\"  ";
      if ($alinhamento=='right') { echo ' checked '; }
      echo 'value="right">';
      echo "<img src='".$urlImagem."/alinharDireita.png' border='0' title='alinhar conteúdos a direita' alt='alinhar a direita'></td></table></td></tr>";
      /* echo "&nbsp&nbsp&nbsp <input type=\"radio\" name=\"alinhar\" value=\"justify\">".
        "<img src='".$urlImagem."/justificado.png' border='0' title='justificar conteúdos' alt='justificado'></td></table></td></tr>";*/
                                              
      // Envia as especificações 
      //echo "<tr><td class=\"branco\" align=\"center\" colspan=\"2\"><br>";
      //echo "<input type=\"submit\" class=\"okButton\" name=\"submeter\" value=\" Salvar e continuar editando menus\">";
      //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    else {
      
      //Disponibiliza o uso do editor HTML 
      echo "<td class=\"branco\" colspan='2'><table width=\"100%\">";

      echo "<tr><td class=\"Titulo\" >Pessoal</td></tr>"; 
      echo "<tr><td class=\"celulaEscura\" ><input type=\"checkbox\" name=\"ativaEditorHTML\"";
      if(getUsoEditorHTML($_SESSION['COD_PESSOA']))  { echo 'checked'; }
      echo " value=\"1\">Usar Editor HTML</td></tr>";
    }
    echo "</table>";
    echo "</td></tr></table>";
    
    echo '<br />';
    // Envia as especificações
    echo "<input type=\"submit\"  value=\"Enviar\" class=\"okButton\"> ";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo " <input type=\"reset\" value=\"Cancelar\" class=\"cancelButton\" align=\"center\" onClick=\"window.location.href='".$url."/alunos/index.php'\"><br><br></td></tr>";

    echo "</form>";
    
    
    break;
    
  case 'submit': 
    //$codInstanciaGlobal =  $_SESSION['codInstanciaGlobal'];
    $instancia->gravaConfiguracaoInstancia($_SESSION['codInstanciaGlobal'],$_REQUEST);
    //echo $_REQUEST;die();
    if($_REQUEST["ativaEditorHTML"]) {
      $_SESSION['naoUsarEditorHTML']=0;
    }
    else {
      $_SESSION['naoUsarEditorHTML']=1;
    }
    if($_REQUEST["reloadpage"]){
      echo "<script>location.href=\"".$_SERVER["PHP_SELF"]."?\"</script>";

    }
    else {
      echo "<script>window.location.href='".$url."/tools/recursos_fixos.php';</script>";
    }
    
    break;

}
?>
