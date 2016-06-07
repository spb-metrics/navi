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
include("../config.php");
include($caminhoBiblioteca."/pessoa.inc.php");
include($caminhoBiblioteca."/aluno.inc.php");
include($caminhoBiblioteca."/professor.inc.php");
include($caminhoBiblioteca."/administradornivel.inc.php");
include($caminhoBiblioteca."/comunidade.inc.php");

session_name(SESSION_NAME); session_start(); security();
session_write_close();
//Busca a instancia global para filtrar as comunidades
$codInstanciaGlobal = (int)$_SESSION['codInstanciaGlobal'];
//Busca o papel atual da pessoa da session
$tipoPessoa = (int)$_SESSION['userRole'];
//Busca o nivel sistêmico que implementa as comunidades temáticas
$nivelComunidade = Nivel::getNivelComunidade();


//imprime a parte inicial da página somente quando necessário,
//para nao gerar conflito com o header
function printHeader() {
  global $url,$urlJs,$nivelComunidade;
  echo "<html>";
  //echo "<link rel='stylesheet' href='".$url."/css/navi.css' type='text/css'>";
  echo "<link rel='stylesheet' href='".$url."/css/comunidade.css' type='text/css'>";
  echo "<script language='JavaScript' src='".$urlJs."/utils.js'></script>";

  echo "<body class='bodybg'>";
  echo "<center>";
  //exibe alguma mensagem de retorno para o usuário, se aplicável
  $msg[1]="Inscri&ccedil;&atilde;o encaminhada para libera&ccedil;&atilde;o por um administrador.";
  $msg[2]="Pessoa Liberada.";
  $msg[4]=$nivelComunidade->nome." criada com sucesso!";
  $msg[5]="Erro ao criar ".$nivelComunidade->nome;
  $msg[6]="Pessoa Recusada.";
  $msg[7]="Comunidade Excluida.";
  if (!empty($_REQUEST['msg'])) { echo "<div style='color:#FA2233;'>".$msg[(int)$_REQUEST['msg']]."</div>"; }
}



//busca a acao adequada
$acao = (string)$_REQUEST['acao'];

/*
 * Instancia o objeto adequado, de acordo com o papel da pessoa
 */ 
switch ($tipoPessoa) {
  case PUBLICO:
    printHeader();
    echo "<div style='color:#FA4444;'>Acesso negado para usuários não registrados.</div>"; exit();
    break;
  case ADMINISTRADOR_GERAL:
    //printHeader();
    if (empty($acao)) { $acao='pendentes';} 
    else if ($acao!='pendentes' && $acao!='liberar' && $acao!='criarComunidade' && $acao!='criarComunidadeBanco') { exit(); }
    $pessoa = new Pessoa($_SESSION['COD_PESSOA']);
    break;
  case ALUNO:
    $pessoa = new Aluno($_SESSION['COD_AL']);
    $descTipoPessoa = 'Participante';
    break;
  case PROFESSOR:
    $pessoa = new Professor($_SESSION['COD_PROF']);
    $descTipoPessoa = 'Moderador';
    break;
  case ADM_NIVEL:
    $pessoa = new AdministradorNivel($_SESSION['COD_ADM']);
    $descTipoPessoa = 'Administrador de '.$nivelComunidade->nome;
    break;
}

/*
 * ACOES DESTE SCRIPT
 */ 
switch($acao) {

  case "":
    printHeader();
    $nivelAtual = getNivelAtual();
    
    $instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
    //Busca todas as comunidades OU, por padrao, 
    //Mostra somente as comunidades relacionadas a instancia de nivel atual
    if (!((int)($_REQUEST['verComunidadesInstanciaGlobal']))) { 
      $codGlobalComunidade='';
      $opcoes = "<a href='".$_SERVER['PHP_SELF']."?acao=&verComunidadesInstanciaGlobal=1'>Ver somente as comunidades relacionadas com ".$instanciaAtual->getAbreviaturaOuNomeComPai()."</a>";
    } 
    else { 
      $codGlobalComunidade=$codInstanciaGlobal;
      $opcoes = "<a href='".$_SERVER['PHP_SELF']."?acao='>Ver TODAS as comunidades</a>";
    }
    $comunidades = $pessoa->getComunidadesAusente($codGlobalComunidade,(string)$_REQUEST['buscaComunidade']);    
    $max = count($comunidades->records);    $linMax = 6;      $count = 0;     $lin =0;
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    
    echo "<div class='tituloInscricaoComunidade'>Inscrição em ".$nivelComunidade->nome."</div>";
    //Ver todas as comunidades ou apenas as ligadas a instancia atual
    echo $opcoes;
    
    if ( Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage']) ) {
      //ver opção de liberar dentro das comunidades
      if ($nivelAtual->nivelComunidade) {
        echo "| <a href='".$_SERVER['PHP_SELF']."?acao=pendentes'>Pend&ecirc;ncias aqui</a>";
      }

      echo "| <a href='".$_SERVER['PHP_SELF']."?acao=pendentes'>Pend&ecirc;ncias nas Minhas Comunidades</a>";

      //Ver opção de criar comunidade 
      echo " | <a href='".$_SERVER['PHP_SELF']."?acao=criarComunidade'>Criar Nova Comunidade</a>";
    }

    echo "<form name='buscaComunidade' method='GET' action='".$_SERVER['PHP_SELF']."'>";
    echo "<input type='hidden' name='verComunidadesInstanciaGlobal' value='".(int)($_REQUEST['verComunidadesInstanciaGlobal'])."'>";
    echo "Busca por nome <input type='text' name='buscaComunidade' value='".$_REQUEST['buscaComunidade']."'><input type='submit' value='Buscar'></form>";
    echo "<div class='subTituloInscricaoComunidade'>Procure a ".$nivelComunidade->nome." desejada e clique nela para solicitar o ingresso como ".$descTipoPessoa;
    echo "<br>Pairando o mouse em cima do nome da ".$nivelComunidade->nome.", &eacute; apresentada uma descri&ccedil;&atilde;o dela.";
    echo "</div><br>";    
    
    echo "<table cellpadding='3' cellspacing='3'><tr>";
    while($count < $max ){
      $obj = $comunidades->records[$count]; 
      echo "<td class='comunidade' title='".$obj->descricao."'>";
      echo "<a href='".$_SERVER['PHP_SELF']."?acao=inscrever&codComunidade=".$obj->$pkComunidade."'>";
      //Mostra a imagem própria, se houver
      if (!empty($obj->codArquivoLogotipo)) { 
        $arq = new ArquivoMultiNavi($obj->codArquivoLogotipo);
        echo "<div><img src='".$urlImagem."/".PASTA_LOGOTIPOS."/".$arq->caminhoFisico."' border='no'></div>";     
      }
      echo "<small>".$obj->nome."</small></a>";
      echo "</td>";
      $count++;  //controle do laco
      //verifica se precisa pular de linha
      $lin++;
      if ($lin==$linMax) { $lin =0;   echo "</tr><tr>"; }
    }
    if ($lin<$linMax) { echo "</tr>"; }
    echo "</table>";


    //Mostra as comunidades pendentes de aprovação
    echo "<br><br>";
    echo "<div class='tituloPendenciaComunidade'>Meus pedidos de inscri&ccedil;&atilde;o pendentes</div><br>";

    $comunidadesPendentes = $pessoa->getComunidades(1); //apenas as pendentes
    if (!empty($comunidadesPendentes->records)) {
      $max = count($comunidadesPendentes->records);
      $count =0;

      echo "<table cellpadding=\"3\" cellspacing=\"1\">";
      while($count < $max ){
        $obj = $comunidadesPendentes->records[$count]; 
        
        echo "<tr><td align='center'>";
        //Mostra a imagem própria, se houver
       echo "<span><a href=\"".$_SERVER['PHP_SELF']."?acao=desistirIncrever&codComunidade=".$obj->codComunidadeTematica."\"><img src='".$urlImagem."/excluir.gif' border=\"no\" title=\"Excluir pedido\"></a></span>";
        if (!empty($obj->codArquivoLogotipo)) { 
          $arq = new ArquivoMultiNavi($obj->codArquivoLogotipo);
          echo "<span><img src='".$urlImagem."/".PASTA_LOGOTIPOS."/".$arq->caminhoFisico."' border='no'></span>";     
        }
        echo "<small>".$obj->nome."</small></td></tr>";

        $count++;  //controle do laco
      }
      echo "</table>";
    }
    else {
      echo "<div>N&atilde;o h&aacute; pend&ecirc;ncias.</div>";
    }
    break;
    
  case "desistirIncrever":
  
    $codComunidade = (int)$_REQUEST['codComunidade'];
     switch($_SESSION['userRole']) {
       case ALUNO:
          $cod =  $codComunidade;
          $pessoa = new Aluno($_SESSION["COD_AL"]);
          break;
        case PROFESSOR:
          $cod =  $codComunidade;
          $pessoa = new Professor($_SESSION["COD_PROF"]);
          break;
      } 
     $pessoa->recusarPessoaComunidade($cod);
     //header('Location: '.$_SERVER['PHP_SELF']."?msg=7");
     echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=&msg=7";</script>';
     break;
  
  case "inscrever":
    $codComunidade = (int)$_REQUEST['codComunidade'];
    $comunidade  = new InstanciaNivel($nivelComunidade,$codComunidade);

    $pessoa->inscreverComunidade($codComunidade);
    
    //header('Location: '.$_SERVER['PHP_SELF']."?acao=&msg=1");
    echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=&msg=1";</script>';
    break;

  case "liberar":
    printHeader();
    if (!Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage']))  {
      echo "Acesso negado"; exit();
    }
    if (empty($_REQUEST['liberacao'])) { die;  }
    
    foreach($_REQUEST['liberacao'] as $foo=>$dados) {
      list($codComunidade,$codigo,$userRole) = explode("|",$dados);

      //instancia a pessoa adequada
      switch($userRole) {
        case ADM_NIVEL:
          $comunidade  = new InstanciaNivel($nivelComunidade,$codComunidade);
          $cod = $comunidade->codInstanciaGlobal;
          $pessoa = new AdministradorNivel($codigo);
          break;
        case ALUNO:
          $cod =  $codComunidade;
          $pessoa = new Aluno($codigo);
          break;
        case PROFESSOR:
          $cod =  $codComunidade;
          $pessoa = new Professor($codigo);
          break;
      }
      //Libera a pessoa (até mesmo outros administradores) de acordo com o papel
      if(empty ($_REQUEST['recusarSelecionados'])){
          $pessoa->liberarPessoaComunidade($cod);
          echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=pendentes&msg=2";</script>';
       }
    
      else {
       $pessoa->recusarPessoaComunidade($cod);
       echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=pendentes&msg=6";</script>';
       //header('Location: '.$_SERVER['PHP_SELF']."?acao=pendentes&msg=6");
      }
    }
    //header('Location: '.$_SERVER['PHP_SELF']."?acao=pendentes&msg=2");
    break;


  case "pendentes":
    if($_SESSION['userRole']==ADMINISTRADOR_GERAL){
      echo "<div align='right'>";
      echo " | <a href='".$_SERVER['PHP_SELF']."?acao=criarComunidade'>Criar Nova Comunidade</a><br>";
      echo "<div>";
    }
       //$codComunidade = (int)$_REQUEST['codComunidade'];
    $codComunidade=0; $flagTodasModeradas=0;
    $titulo = "Libera&ccedil;&atilde;o ";
    if ($_SESSION['userRole']!=ADMINISTRADOR_GERAL) {
       if ($_REQUEST['somenteAtual']) { 
         $codComunidade=getCodInstanciaNivelAtual();
         $titulo .= ' nesta Comunidade';
       }
       else { 
         $flagTodasModeradas=1;
         $titulo .= ' em todas as Minhas Comunidades';   
       } 
    } 
    else {  
      $titulo  .= ' em todas as Comunidades';   
    }
    
    //professores e administradores de nivel veem apenas as inscricoes pendentes da comunidade atual
    $pendencias = $pessoa->getInscricoesPendentesEmComunidades($codComunidade,$flagTodasModeradas);
    $pkComunidade =$nivelComunidade->nomeFisicoPK;
    
    printHeader();
    

    if (empty($pendencias)) {
      echo "<P color='red'>N&atilde;o h&aacute; pend&ecirc;ncias.</p>";
      echo "<a href='".$_SERVER['PHP_SELF']."'>Voltar</a>";
    }
    else {
      $botaoPendencias = "<tr><td colspan='4'><div align='right'><input type='submit' name='recusarSelecionados' value='Recusar Selecionados' style='background-color:#FFC5A8;'> <input type='submit' value='Liberar Selecionados'></div><div align='right'></div></td></tr>";
     
      echo "<h2>".$titulo."</h2>";
      echo "<form name='liberacaoComunidade' id='liberacaoComunidade' action='".$_SERVER['PHP_SELF']."?acao=liberar' method='POST'>";
      echo "<div align='right'>";
      echo " <span style='cursor:pointer; font-weight:bold;' onClick=\"marcaCombos('liberacaoComunidade',1);\"> Marcar Todos </span>";
      echo " | <span style='cursor:pointer; font-weight:bold;' onClick=\"marcaCombos('liberacaoComunidade',0);\"> Desmarcar Todos </span>";
      echo "</div>";
      echo "<table>
            <tr style='background-color:navy; color:white;'><th width='80'>Tipo de Pessoa</th>
            <th width='380'>Nome</th><th width='180'>Comunidade</th><th width='10'></th></tr>";
      echo $botaoPendencias;
      $par=0;
      
      foreach($pendencias as $p) {
        //$link.="&COD_PROF=".$p->COD_PROF."&COD_ADM=".$p->COD_ADM."&userRole=".;
        if ($par) { $sty='background-color: #C0C0C0; color:#000000;'; $par=0; } else { $sty='background-color: #EFEFEF; color:#000000;'; $par=1; }
        echo "<tr style='".$sty."'><td>";
        switch($p->userRole) {
          case ALUNO:  echo "Aluno "; $codigo=$p->COD_AL; break;
          case PROFESSOR:  echo "Professor "; $codigo=$p->COD_PROF; break;          
          case ADM_NIVEL:  echo "Administrador de N&iaute;l "; $codigo=$p->COD_ADM; break;
        }
        echo "</td><td><a href='".$url."/consultar.php?BUSCA_PESSOA=".$p->COD_PESSOA."' target='_blank'>".$p->NOME_PESSOA."</a></td><td>".$p->nome."</td>";
        echo "<td><INPUT TYPE='checkbox' name='liberacao[]' value='".$p->$pkComunidade."|".$codigo."|".$p->userRole."'>";
        echo "</td></tr>";
      }
      echo $botaoPendencias;
      echo "</table></form>";
      
    }
    break;
    
  case "criarComunidade":
    printHeader();
    
    //somente administradores e professores podem criar comunidades
    if ($tipoPessoa!=ADMINISTRADOR_GERAL && $tipoPessoa!=ADM_NIVEL  && $tipoPessoa!=PROFESSOR) {
      echo "Acesso negado"; exit();
    }
    $instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual());        


    echo "<div class='tituloInscricaoComunidade'>Cria&ccedil;&atilde;o de ".$nivelComunidade->nome."</div>";
     
    echo "<br>Esta ".$nivelComunidade->nome." será automaticamente associada a ".$instanciaAtual->getAbreviaturaOuNomeComPai().".";
    echo "<br>Voc&ecirc; ser&aacute; administrador da comunidade criada.";
    //echo "Você poderá fazer outras associações. ";
    echo "<form name='criaComunidade' method='POST' action='".$_SERVER['PHP_SELF']."?acao=criarComunidadeBanco' enctype='multipart/form-data'>";
    //div do formulário   
    echo "<div style='text-align:left; padding-left:125px; font-weight:bold;'>";  
    echo "  <div>Nome ".$nivelComunidade->nome."</div><div style='padding-bottom:5px;'><input type='text' name='nomeComunidade' value='' size=80 max-size=255></div>";
    echo "  <div>Imagem de identifica&ccedil;&atilde;o</div><div style='padding-bottom:5px;'><input type='file' name='logotipoComunidade' size=40></div>";
    echo "  <div>Descri&ccedil;&atilde;o</div><div style='padding-bottom:5px;'><textarea name='descricao' rows=10 cols=60></textarea></div>";
    echo "  <div><input type='checkbox' name='alunoParticipa'checked>Aluno pode participar desta ".$nivelComunidade->nome."</div>";
    echo "</div>";
    echo "<input type='submit' value='  Criar Comunidade  '></form>";
    break;

  case "criarComunidadeBanco":
     //somente administradores e professores podem criar comunidades
    if ($tipoPessoa!=ADMINISTRADOR_GERAL && $tipoPessoa!=ADM_NIVEL  && $tipoPessoa!=PROFESSOR) {
      echo "Acesso negado"; exit();
    }
    if (empty($_REQUEST['nomeComunidade'])) {
      echo "Acesso negado"; exit();
    }
    //permitir a colocação da imagem
    include($caminhoBiblioteca."/funcoesftp.inc.php");
        
    //prepara os campos para inserir a comunidade
    $campos=array();
    
    if (!empty($_FILES["logotipoComunidade"]["size"])) {
      $codArquivo = ArquivoMultiNavi::insereArquivo($_FILES['logotipoComunidade']);
      move_uploaded_file($_FILES["logotipoComunidade"]["tmp_name"], $caminhoImagem.'/logotiposNiveis/'.$_FILES["logotipoComunidade"]["name"]);
      duplica($caminhoImagem.'/logotiposNiveis/'.$_FILES["logotipoComunidade"]["name"], $_FILES["logotipoComunidade"]["name"], 'logotiposNiveis/');
      $campos['codArquivoLogotipo']=$codArquivo;
    }
    $campos['descricao']=$_REQUEST['descricao'];
    if ($_REQUEST['alunoParticipa']=='on') { $campos['alunoParticipa']=1; }
    else { $campos['alunoParticipa']=0;  } 
    
    //Inserção
	 if ($nivelComunidade->insereInstancia($_REQUEST['nomeComunidade'],$campos)) {
      Comunidade::relacionaComInstancia($_SESSION['codInstanciaGlobal'],$nivelComunidade->codInstanciaNivel);
      if($_SESSION["userRole"]!=ADMINISTRADOR_GERAL){
        $pessoa->inscreverComunidade($nivelComunidade->codInstanciaNivel);
        $pessoa->liberarPessoaComunidade($nivelComunidade->codInstanciaNivel);
      }

	  $instancia = new InstanciaNivel($nivelComunidade,$nivelComunidade->codInstanciaNivel);
	  $instancia->iniciaMenu();
    echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?msg=4";</script>';	   
	  //header('Location: '.$_SERVER['PHP_SELF']."?msg=4"); //tudo ok
    } 
    else { 
      echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?msg=5";</script>';
      //header('Location: '.$_SERVER['PHP_SELF']."?msg=5");        
    }  //erro
    
    break;
}
echo "</center></body></html>";
?>
