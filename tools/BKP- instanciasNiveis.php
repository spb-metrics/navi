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

/** SCRIPT PARA VISUALIZACAO / INCLUSAO / ALTERACAO / EXCLUSAO DE INSTANCIAS DE NIVEIS **/
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once($caminhoBiblioteca."/pessoa.inc.php");
include_once($caminhoBiblioteca."/professor.inc.php");
include_once($caminhoBiblioteca."/aluno.inc.php");
include_once($caminhoBiblioteca."/administradornivel.inc.php");
session_name(SESSION_NAME); session_start(); security();

//nivel atual da navegacao
$nivelAtual = getNivelAtual();

//obtem o nivel informado. Por default, pega o nivel atual
if (empty($_REQUEST['codNivel'])) {
  if (empty($_SESSION['nivelGerenciado'])) { 
    $nivelGerenciado = $nivelAtual;
    $_SESSION['nivelGerenciado'] = $nivelGerenciado;
  }
  else {
    $nivelGerenciado = $_SESSION['nivelGerenciado'];
  }
}
else {
  $nivelGerenciado = new Nivel($_REQUEST['codNivel']);
  $_SESSION['nivelGerenciado'] = $nivelGerenciado;
}

//VERIFICA SE A PESSOA TEM DIREITO DE ADMINISTRACAO, PARA PODER 
//INCLUIR, ALTERAR E EXCLUIR INSTANCIAS
if(!Pessoa::isAdm($_SESSION["userRole"])) {
  echo 'Permissao negada!'; die; 
}
//adm nivel pode gerenciar apenas os niveis-filho
if ($_SESSION['userRole']==ADM_NIVEL) {
  $primeiro = $_SESSION['navegacao']->getNivelInicial();          
  $codNivelPrimeiro=$primeiro->codNivel;
  if ($nivelGerenciado->codNivel==$codNivelPrimeiro) { echo 'Permissao negada!'; die; }
}
else {
  $codNivelPrimeiro=-1;
}

function htmlTop($title,$imprimeLinkInserir=0,$mostraComboNiveis=1) {
  global $nivelAtual,$nivelGerenciado,$urlCss,$urlImagem,$codNivelPrimeiro;
  echo "<html>".
		   "<head>".
			 "<title>Gerenciamento de Instancias</title>".
			 "<link rel=\"stylesheet\" href=\"".$urlCss."/padraogeral.css\" type=\"text/css\">".
			 "</head>".
			 "<body text=\"#000000\" class=\"bodybg\" style=\"width:95%; text-align:center;\">";
  echo "<div style=\"font-weight: bold; font-size: 14px; text-align: center\">".$title."</div>";
  
  if ($imprimeLinkInserir) {
		echo '<div style="text-align:left; margin-left:15px;"><a href="'.$_SERVER['PHP_SELF'].'?OPCAO=Inserir&codNivel='.$nivelGerenciado->codNivel.'"><img src="'.$urlImagem.'/criar.gif" border="0">Inserir '.$nivelGerenciado->nome.' </a> </div>';
  }

  echo '<span style="float:right;">';
  echo "<a href=\"recursos_fixos.php\">Ir para Painel de Controle</a><br>";
  echo "<a href=\"index.php\">Ir para Ferramentas de Ger&ecirc;ncia</a>";
  echo "</span>";

  if ($mostraComboNiveis) {
    echo '<span style="float:right;margin-right:20px;">Selecione o n&iacute;vel<br>';
    echo '<form name="selecaoNivel" method="POST">';
    echo '<select name="codNivel" onChange="document.selecaoNivel.submit();">';
    //Lista o nivel atual
    if ($nivelAtual->codNivel==$nivelGerenciado->codNivel) { $selected='selected'; } else { $selected='';}
    echo '<option value="'.$nivelAtual->codNivel.'" '.$selected.'>'.$nivelAtual->nome.'</option>';
    //Lista os filhos
    echo '<optgroup label="Pr&oacute;ximos n&iacute;veis">';
    $filhos = $nivelAtual->getSubNiveis();   
    foreach($filhos->records as $n) {   
      if ($_SESSION['userRole']==ADMINISTRADOR_GERAL || $n->codNivel!=$codNivelPrimeiro) { 
        if ($n->codNivel==$nivelGerenciado->codNivel) { $selected='selected'; } else { $selected='';} //adm nivel gerencia apenas as instancia-filho
        echo '<option value="'.$n->codNivel.'" '.$selected.' '.$disabled.'>'.$n->nome.'</option>';
      }
    }
    echo '</optgroup>';
    echo '</select>';
    echo '</form>';
    echo '</span>';
  }
}

function htmlBottom() {
	echo "</body>";
	echo "</html>";
}

switch($_REQUEST["OPCAO"]) {

	case "":
    /** VISUALIZACAO DE INSTANCIAS **/
    htmlTop("Edi&ccedil;&atilde;o de ".$nivelGerenciado->nome,1);
    
    if ($_REQUEST['instanciaNiveisMostraTodos'])        {  $_SESSION['instanciaNiveisMostraTodos']=1; }
    if ($_REQUEST['instanciaNiveisNAOMostraTodos']) {  $_SESSION['instanciaNiveisMostraTodos']=0; }

    $instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
    if ($nivelAtual->codNivel==$nivelGerenciado->codNivel) {     $paiFiltro = $instanciaAtual->getPai();     }
    else {       $paiFiltro = $instanciaAtual;         }

    //ao buscar as instancias, filtra pelo pai (se existir)
    if (!empty($paiFiltro)) {
      if ($_SESSION['instanciaNiveisMostraTodos']) {
        $linkMostraInstancias = '<a href="'.$_SERVER['PHP_SELF'].'?instanciaNiveisNAOMostraTodos=1">Mostrar apenas relacionadas a ';
        $linkMostraInstancias.= $paiFiltro->nivel->Nome.': '.$paiFiltro->getAbreviaturaOuNome();
        $linkMostraInstancias.='</a>';
        $paiFiltrar='';
      }
      else {
        //ajusta variaveis
        $paiFiltrar = $paiFiltro;        
        $linkMostraInstancias = '<a href="'.$_SERVER['PHP_SELF'].'?instanciaNiveisMostraTodos=1">Mostrar Todos</a>';
      }
    }
    echo $linkMostraInstancias;
    //usa o metodo getregistros, pois na edicao nao interessa a cardinalidade com o nivel pai
    //$instancias = $nivelGerenciado->getRegistros($paiFiltro);
    $instancias = $nivelGerenciado->getInstancias($codInstanciaNivelPai,$pkPai);
    
    echo '<div style="float:left;margin-left:15px;">';
    
    if  (!empty($instancias->records)) {		    
      echo '<table style="width:650px;">';            
      echo '<tr><th  class="CelulaTitulo" style="width:80px;">Excluir/Alterar</th><th class="CelulaTitulo" >Nome ou Descri&ccedil;&atilde;o</th></tr>';
      $par=0;
      $codInstanciaAtual = getCodInstanciaNivelAtual();
      //getInstancias() traz como atributos nome e chave, independente do nome fisico
      //no caso NxM usamos a chave fraca, para editar a instancia
      foreach($instancias->records as $instancia) {
        if ($par) { $classe='CelulaClara';  $par=0;} else { $classe='CelulaEscura'; $par=1; }
        echo '<tr class="'.$classe.'">';
        echo "<td align='center'>";
        //instancia do nivel atual nao pode ser excluida, pois esta sendo exibida 
        if ($codInstanciaAtual!=$instancia->chave || $nivelAtual->codNivel!=$nivelGerenciado->codNivel) {
          echo "<a href=\"".$_SERVER["PHP_SELF"]."?OPCAO=Excluir&frm_codInstanciaNivel=".$instancia->chave."&frm_chaveFraca=".$instancia->chaveFraca."&voltar=".$_SERVER["PHP_SELF"]."\"><img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\" title=\"Remover\"></a>&nbsp;&nbsp;&nbsp;";
          echo "<a href=\"".$_SERVER["PHP_SELF"]."?OPCAO=Alterar&frm_codInstanciaNivel=".$instancia->chave."&frm_chaveFraca=".$instancia->chaveFraca."\"><img src=\"../imagens/edita.gif\" border=0 alt=\"Remover\" title=\"Alterar\"></a>";
        }
        else {
          echo 'Atual';
        }
        echo "</td>";
        echo "<td>".$instancia->nome."</td>";
        echo "</tr>";
      }
      
      echo "</table>";
    }
    else {
      echo "<p>N&atilde;o existem inst&acirc;ncias neste n&iacute;vel.</p>";
    }
    echo '</div>';
    
		htmlBottom();
		break;

	case "Inserir":
		
    /** FORMULARIO DE INCLUSAO DE INSTANCIAS **/	
    $descricao = $nivelGerenciado->nome;
    if ($nivelAtual->codNivel!=$nivelGerenciado->codNivel) { 
      $instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
      $descricao.=' em '.$nivelAtual->nome.': '.$instanciaAtual->getAbreviaturaOuNome(); 
    }
    htmlTop("Inclus&atilde;o de ".$descricao);
    echo '<div style="float:left;padding-left:150px;">';
    echo "<form name=\"form1\" action=\"".$_SERVER["PHP_SELF"]."?OPCAO=InserirMake&codNivel=".$nivelGerenciado->codNivel."\" method=\"POST\">";
		echo "<div style='text-align:left'>Informe o nome ou descri&ccedil;&atilde;o <br><input type=\"text\" size=\"60\" maxlength=\"80\" name=\"frm_nomeInstancia\"></div>";
		if (!empty($nivelGerenciado->nomeFisicoCampoAbreviatura)) {
      echo "<div style='text-align:left'>Informe uma abreviatura (deixe em branco para pegar os 3 primeiros caracteres)<br><input type=\"text\" size=\"5\" maxlength=\"3\" name=\"frm_abreviatura\"> </div>";
		}		
    echo '<div style="text-align:right">';
    echo "<input type=\"submit\" value=\"Incluir\" name=\"sub\" class=\"okButton\">&nbsp;&nbsp;&nbsp;";
		echo "<input type=\"button\" value=\"Cancelar\" name=\"cancel\"  class=\"cancelButton\" onclick=\"window.location.href = '".$_SERVER["PHP_SELF"]."';\">";
    echo '</div>';
		echo "</form>";
    echo '</div>';

		echo "<script>document.form1.frm_nomeInstancia.focus();</script>";

		htmlBottom();
    break;

	case "InserirMake":
		/** SALVA OS DADOS DA INCLUSAO NO BD **/
		htmlTop("Inclus&atilde;o de ".$nivelGerenciado->nome,0,1);
		//insere a instancia
		//$instanciaAtual = $_SESSION['navegacao']->getInstanciaNivelAtual();
		
    //obtem nivel pai, quer seja do nivel atual, quer do filho do atual selecionado 
    // (nesse caso o pai passa a ser o nivel atual)
    //ja ajusta o link para navegar para a instancia criada
    $instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual());
    if ($nivelGerenciado->codNivel == $nivelAtual->codNivel) {                      
      $instanciaPai = $instanciaAtual->getPai(); 
    }
    else {
      $instanciaPai = $instanciaAtual;           
    }
    $chavePai=array();  
    if (!empty($instanciaPai)) {
      $pk = $instanciaPai->nivel->getPK();
      //$campos[$pk] = $instanciaPai->$pk;
      $chavePai[$pk] = $instanciaPai->codInstanciaNivel;
      //abreviatura automatica, se houver
      if (!empty($nivelGerenciado->nomeFisicoCampoAbreviatura)) {
        if (!empty($_REQUEST["frm_abreviatura"]) ) { 
          $campos[$nivelGerenciado->nomeFisicoCampoAbreviatura]=$_REQUEST["frm_abreviatura"];
        }
        else  {
          $campos[$nivelGerenciado->nomeFisicoCampoAbreviatura]=substr($_REQUEST["frm_nomeInstancia"],0,3);
        }
      }              
    }
    //insere a instancia propriamente dita
    $sucesso = $nivelGerenciado->insereInstancia($_REQUEST["frm_nomeInstancia"],$campos,$chavePai);

    if ($sucesso) {
      echo "<p>Inst&acirc;ncia inclu&iacute;da com sucesso!</p>";
      //ajustar reload (Nivel->codInstanciaNivel contem a chave da instancia recem criada
      //util quando esta se incluindo instancias de niveis filho e se quer continuar a descer na hierarquia
      if($nivelGerenciado->codNivel != $nivelAtual->codNivel) {
        echo "<a href='".$url."/index.php?administraInstancia=1&seguirAdiante=1&codNivel=".$nivelGerenciado->codNivel."&codInstanciaNivel=".$nivelGerenciado->codInstanciaNivel."' target='_top'>Clique aqui para gerenciar ".$_REQUEST["frm_nomeInstancia"]."</a>";
      }
    }
    else {
      echo mysql_error();
      echo "<p>Houve algum erro ao incluir a inst&acirc;ncia.</p>";
    }

    echo "<br><a href=\"instanciasNiveis.php\">Voltar</a>";

    htmlBottom();
    break;

	case "Alterar":
		/** FORMULARIO DE ALTERACAO DE INSTANCIAS **/	
    $descricao = $nivelGerenciado->nome;
    if ($nivelAtual->codNivel!=$nivelGerenciado->codNivel) { 
      $instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
      $descricao.=' em '.$nivelAtual->nome.': '.$instanciaAtual->getAbreviaturaOuNome(); 
    }

		htmlTop("Altera&ccedil;&atilde;o de ".$descricao);

    echo '<div style="float:left;padding-left:150px;">';
		echo "<form name=\"form1\" action=\"".$_SERVER["PHP_SELF"]."?OPCAO=AlterarMake&frm_codInstanciaNivel=".$_REQUEST["frm_codInstanciaNivel"]."&frm_chaveFraca=".$_REQUEST["frm_chaveFraca"]."\" method=\"POST\">";
		//edita a    
    
    if ($nivelGerenciado->tipoRelacionamentoComNivelPai==1) {
      //obtem nivel/instancia pai 
      if (empty($instanciaAtual)) { $instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual()); }      
      if ($nivelGerenciado->codNivel == $nivelAtual->codNivel) {     $instanciaPai = $instanciaAtual->getPai();    }
      else {   $instanciaPai = $instanciaAtual;     }
      $nivelPai = $instanciaPai->nivel;
       
      echo '<div style="text-align:left">Inst&acirc;ncia Superior<br />';
      echo '<select name="codInstanciaPai">';
      $pais = $nivelPai->getInstancias();       
      foreach($pais->records as $p) {
        if ($p->chave==$instanciaPai->codInstanciaNivel) { $selected='selected'; } else { $selected='';} //pai atual
        echo '<option value="'.$p->chave.'" '.$selected.' >'.$p->nome.'</option>';
      }
      echo '</select>';
      echo '</div>';
    }

    //para instancias NxM, o que interessa no CRUD é a chaveFraca, para o "update tabela where pk=.."
    if (!empty($_REQUEST["frm_chaveFraca"])) {  $chaveInstancia = $_REQUEST["frm_chaveFraca"];   }
    else { $chaveInstancia = $_REQUEST["frm_codInstanciaNivel"]; }
    
    $instanciaNivel = new InstanciaNivel($nivelGerenciado,$chaveInstancia);
    
		//edita nome e abreviatura
    echo "<div style='text-align:left'>Nome/Descri&ccedil;&atilde;o de ".$nivelGerenciado->nome."<br><input type=\"text\" size=\"60\" maxlength=\"80\" name=\"frm_nomeInstancia\" value=\"".$instanciaNivel->nome."\"></div>";
		if (!empty($nivelGerenciado->nomeFisicoCampoAbreviatura)) {
		  $campoAbreviatura = $nivelGerenciado->nomeFisicoCampoAbreviatura;
      echo "<div style='text-align:left'>Abreviatura de ".$nivelGerenciado->nome."<br><input type=\"text\" size=\"60\" maxlength=\"80\" name=\"frm_abreviatura\" value=\"".$instanciaNivel->$campoAbreviatura."\"></div>";
		}
    if ($nivelGerenciado->tipoRelacionamentoComNivelPai==2) {
      echo '<div style="text-align:left;margin-top:20px;">Inst&acirc;ncias superiores relacionadas com esta inst&acirc;ncia<br />';
      mostraRelacionamentosInstancia($instanciaNivel);
      echo '</div>';
    }
		
		echo '<div style="text-align:right">';
    echo "<input type=\"submit\" value=\"Alterar\" name=\"sub\" class='okButton'>&nbsp;&nbsp;&nbsp;"; 
		echo "<input type=\"button\" value=\"Cancelar\" name=\"cancel\" class='cancelButton' onclick=\"window.location.href = '".$_SERVER["PHP_SELF"]."';\">";
		echo "</form>";
    echo '</div>';
    echo '</div>';
    
		htmlBottom();
	  break;

	case "AlterarMake":
		/** SALVA OS DADOS DA ALTERACAO NO BD **/
		htmlTop("Altera&ccedil;&atilde;o de ".$nivelGerenciado->nome,0,1);
    //para instancias NxM, o que interessa no CRUD é a chaveFraca, para o "update tabela where pk=.."
    if (!empty($_REQUEST["frm_chaveFraca"])) {  $chaveInstancia = $_REQUEST["frm_chaveFraca"];   }
    else { $chaveInstancia = $_REQUEST["frm_codInstanciaNivel"]; }
    
		$sucesso = $nivelGerenciado->alteraInstancia($chaveInstancia ,$_REQUEST["frm_nomeInstancia"],$_REQUEST["frm_abreviatura"]);

		if ($sucesso) {
			echo "<p>Inst&acirc;ncia alterada com sucesso!</p>";
		}
		else {
			echo "<p>Houve algum erro ao alterar a inst&acirc;ncia.</p>";   echo mysql_error();
		}

		echo "<br><br><a href=\"instanciasNiveis.php\">Voltar</a>";

		htmlBottom();
	  break;

	case "Excluir":
		/** CONFIRMACAO DA EXCLUSAO DA INSTANCIA **/
		htmlTop("Exclus&atilde;o de ".$nivelGerenciado->nome);
	
		$instanciaNivel = new InstanciaNivel($nivelGerenciado,$chaveInstancia);
		echo "<p>Voc&ecirc; realmente deseja excluir a(o) ".$nivelGerenciado->nome." ".$instanciaNivel->nome."? Essa a&ccedil;&atilde;o n&atilde;o poder&aacute; ser desfeita.</p>";
		echo "<br>";
		echo "<input type=\"button\" value=\"Excluir\" onclick=\"window.location.href = '".$_SERVER["PHP_SELF"]."?OPCAO=ExcluirMake&frm_codInstanciaNivel=".$_REQUEST["frm_codInstanciaNivel"]."&frm_chaveFraca=".$_REQUEST['frm_chaveFraca']."'\">";
		echo "<input type=\"button\" value=\"Cancelar\" name=\"cancel\" onclick=\"window.location.href = 
		'".$_REQUEST['voltar']."';\">";		

		htmlBottom();
	  break;

	case "ExcluirMake":
		/** EXCLUSAO DA INSTANCIA DO BANCO DE DADOS **/

		htmlTop("Exclus&atilde;o de ".$nivelGerenciado->nome);
    //para instancias NxM, o que interessa no CRUD é a chaveFraca, para o "update tabela where pk=.."
    if ($_REQUEST["frm_chaveFraca"]) {  $chaveInstancia = $_REQUEST["frm_chaveFraca"];   }
    else { $chaveInstancia = $_REQUEST["frm_codInstanciaNivel"]; }
		
		$sucesso = $nivelGerenciado->excluiInstancia($chaveInstancia);

		if ($sucesso) {
			echo "<p>Inst&acirc;ncia exclu&iacute;da com sucesso!</p>";
		}
		else {
			echo "<p>Houve algum erro ao excluir a inst&acirc;ncia.</p>";
		}
		echo "<br><br><a href=\"instanciasNiveis.php\">Voltar</a>";
		htmlBottom();
	  break;
	
  case "sairComunidade":
     /*CONFIRMAÇÃO QUE O ALUNO SAI DA COMUNIDADE*/
   	htmlTop("Saindo  de ".$nivelGerenciado->nome);
	
		$instanciaNivel = new InstanciaNivel($nivelGerenciado,$_REQUEST["frm_codInstanciaNivel"]);
		echo "<p>Voc&ecirc; realmente deseja sair da(o) ".$nivelGerenciado->nome." ".$instanciaNivel->nome."? </p>";
		echo "<br>";
		echo "<input type=\"button\" value=\"Sair\" onclick=\"window.location.href = '".$_SERVER["PHP_SELF"]."?OPCAO=sairComunidadeMake&frm_codInstanciaNivel=".$_REQUEST["frm_codInstanciaNivel"]."'\">";
		echo "<input type=\"button\" value=\"Cancelar\" name=\"cancel\" onclick=\"window.location.href = '../alunos/index.php?';\">";		
 
		htmlBottom();
  break;
  case "sairComunidadeMake":
    /** EXCLUSAO DO ALUNO DA COMUNIDADE NO BANCO DE DADOS **/

		htmlTop("Saindo de ".$nivelGerenciado->nome);
   
     //$nivel= new $_SESSION['userRole']();
    if($_SESSION["userRole"]==ALUNO){ $pessoa= new Aluno($_SESSION["COD_AL"]);}
    if($_SESSION["userRole"]==PROFESSOR){$pessoa= new Professor($_SESSION["COD_PROF"]);}
    if($_SESSION["userRole"]==ADM_NIVEL){ $pessoa= new AdministradorNivel($_SESSION['COD_ADM']);}
	  if($_SESSION["userRole"]==ADMINISTRADOR_GERAL){$pessoa = new Pessoa($_SESSION['COD_PESSOA']);}
   $ok = $pessoa->retirarInstancia($nivelGerenciado,$_REQUEST["frm_codInstanciaNivel"]);

		if ($ok) {
		   $instanciaNivel = $pessoa->getInstanciaNivelInicial($_SESSION['COD_PESSOA']);
		   $sucesso = $pessoa->deleteInstanciaNivelInicial($_SESSION['COD_PESSOA'],$nivelGerenciado, $instanciaNivel);
		
      echo "<p>Inst&acirc;ncia exclu&iacute;da com sucesso!</p>";
		}
		else {
			echo "<p>Houve algum erro ao excluir a inst&acirc;ncia.</p>";
		}

		echo "<br><br><a href=\"../cadastro/inscricaoComunidade.php\">Voltar</a>";

		htmlBottom();
  break;
}

function mostraRelacionamentosInstancia() {
  
  
  echo '<input type="radio" name="instancia001" value="001"> Instancia superior 1<br>';
  echo '<input type="radio" name="instancia001" value="001"> Instancia superior 2<br>';
  
}
?>