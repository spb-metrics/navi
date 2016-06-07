<?php
/* 
{{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de dúvidas e/ou sugestões, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, portão K. Porto Alegre - RS. CEP: 90035-003

Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU conforme publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (a seu critério) qualquer versão posterior.

    Este programa é distribuído na expectativa de que seja útil, porém, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implícita de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.
    Consulte a Licença Pública Geral do GNU para mais detalhes.
    

    Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto com este programa;
    se não, escreva para a Free Software Foundation, Inc., 
    no endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} 
*/
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); ini_set("display_errors",1);
include("config.php");
include($caminhoBiblioteca.'/linkimagem.inc.php');  
//include($caminhoBiblioteca.'/nucleo.inc.php');
include($caminhoBiblioteca.'/pessoa.inc.php'); 
include($caminhoBiblioteca.'/professor.inc.php'); 
include($caminhoBiblioteca.'/aluno.inc.php');
include($caminhoBiblioteca.'/administradornivel.inc.php'); 

//inicia a sessao
session_name(SESSION_NAME); session_start(); 
if (($_SESSION['userRole'] == PUBLICO) || (empty($_SESSION['userRole']))) {$sp = 1;} else {$sp = 0;}
security($sp,1); //segundo parametro identifica o index, pois uma das verificacoes é exclusiva do iframe de recursos
/* 
 * Variaveis que devem ser setadas de acordo com o tipo de usuario
 */
$nivel="";
$instanciaNivel="";
//Flags para direitos/papeis da pessoa: aluno, prof, adm nivel, adm, publico
$direitosUsuario = array('aluno'=>0,'professor'=>0,'admNivel'=>0,'adm'=>0,'publico'=>0); 

//tambem para diminuir a possibilidade de algum hack por injection na sessao
if (!empty($_SESSION['COD_PESSOA']) && empty($_SESSION['userRole']) ) {
  header("Location: ./logoff.php"); exit;
}

/*
 * COORDENACAO DAS NAVEGACOES
 */
//$mostraPassado=0; //default eh mostrar somente instancias atuais
//NAVEGAÇÃO INICIAL: Busca o ultimo papel exercido pelo usuario, e se era uma instancia atual ou passada (caso haja temporalidade habilitada no nivel)
if ( !empty($_REQUEST['iniciarNavegacao']) && !empty($_SESSION['COD_PESSOA']) &&
     empty($_REQUEST['codNivel']) && empty($_REQUEST['codInstanciaNivel']) 
   ) { 
  $pessoa = new Pessoa();    
  //busca instancia em que o usuario estava quando deslogou, armazenada na tabela InstanciaInicial
  $instanciaNivel   = $pessoa->getInstanciaNivelInicial($_SESSION['COD_PESSOA']); 
  if (!empty($instanciaNivel)) { //somente caso tenha armazenado acesso anterior
    $mostraPassado = $instanciaNivel->mostraPassado; 
    $_SESSION['mostraPassado'] = $mostraPassado;  //guarda em sessao a confg de tempo
    $interage  = $instanciaNivel->interage; 
    $_SESSION['interage'] = $interage;

    $nivel          = $pessoa->getNivelInicial();  //getInstanciaNivelInicial seleciona o nivel       
    $userRole    = $pessoa->getUserRoleInicial();
    $_SESSION['userRole'] = $userRole;    
  }
}
else { //DEMAIS NAVEGAÇÕES
  
  //verifica se estamos recebendo mudança de papel do usuario
  $userRole = (int)$_REQUEST['userRole'];
  //se o papel atual do usuario mudar, entao a navegacao deve ser reiniciada e devemos guardar o novo papel na sessao
  //se nao, busca da sessao qual era o papel exercido pelo usuario
  //o papel de ADMINISTRADOR GERAL SOMENTE EH SETADO PELO SISTEMA, nao por REQUEST
  if (!empty($userRole) && $userRole!=ADMINISTRADOR_GERAL) {
    if ($_SESSION['userRole']!=$userRole)  { 
      unset($_SESSION['navegacao']); 
      unset($_SESSION['userRole']); 
      $_SESSION['userRole']=$userRole; 
    }
  } 
  else {
    //busca o papel exercido até entao da sessao, ja que continua o mesmo
    $userRole = $_SESSION['userRole'];
  }
  
  //Ajusta navegacao para outro nivel/instancia. verificaDireitos() fara a consistencia dos direitos de acesso.
  if ( !empty($_REQUEST['codNivel']) && !empty($_REQUEST['codInstanciaNivel']) ) {
    $nivel = new Nivel($_REQUEST['codNivel']);
    $instanciaNivel = new InstanciaNivel($nivel,$_REQUEST['codInstanciaNivel']);
  }

  //permite alternar entre exibir somente inscricoes em instancias atuais ou tambem as passadas
  if (isset($_REQUEST['mostraPassado'])) {
    $mostraPassado = (int)$_REQUEST['mostraPassado'];
    $_SESSION['mostraPassado'] = $mostraPassado; 
    if ($userRole==ALUNO) { 
      $instanciaNivel = $_SESSION['aluno']->getInstanciaNivelInicial($_SESSION['COD_PESSOA'],$mostraPassado);      
      $nivel               = $_SESSION['aluno']->getNivelInicial();  //o método anterior seleciona o nivel      
    }
    else if ($userRole==PROFESSOR) { 
      $instanciaNivel = $_SESSION['professor']->getInstanciaNivelInicial($_SESSION['COD_PESSOA'],$mostraPassado);      
      $nivel          = $_SESSION['professor']->getNivelInicial();  //o método anterior seleciona o nivel      
    }
    //ajusta tempo atual, apos ter buscado nivel/instancia
    //$mostraPassado = $instanciaNivel->mostraPassado; 
    //$_SESSION['mostraPassado'] = $mostraPassado;  //guarda em sessao a confg de tempo
    //ajusta atributo de interacao    
    $_SESSION['interage'] = $instanciaNivel->interage;
  }
  else {
    //$mostraPassado = (int)$_SESSION['mostraPassado']; //recupera temporalidade da sessao (so afeta se habilitado no nivel)
    $mostraPassado = $_SESSION['mostraPassado']; //recupera temporalidade da sessao (so afeta se habilitado no nivel)

    //guarda a informacao se o usuario tem permissao de interacao nesta instancia 
    if (!empty($nivel) && !empty($instanciaNivel)) {

      if (isset($_REQUEST['interage'])) {
        $_SESSION['interage'] = (int)$_REQUEST['interage'];
      }
    }
    else {
      //excluiu comunidade ou pediu para sair dela. Entao, ainda nao foi selecionado nivel/instancia
      //ou usuario forcou via url.
      if ($userRole==ALUNO) { 
        $instanciaNivel = $_SESSION['aluno']->getInstanciaNivelInicial($_SESSION['COD_PESSOA'],$mostraPassado);      
        $nivel               = $_SESSION['aluno']->getNivelInicial();  //o método anterior seleciona o nivel      
      }
      else if ($userRole==PROFESSOR) { 
        $instanciaNivel = $_SESSION['professor']->getInstanciaNivelInicial($_SESSION['COD_PESSOA'],$mostraPassado);      
        $nivel               = $_SESSION['professor']->getNivelInicial();  //o método anterior seleciona o nivel      
      }
      //ajusta tempo atual, apos ter buscado nivel/instancia
      $mostraPassado = $instanciaNivel->mostraPassado; 
      $_SESSION['mostraPassado'] = $mostraPassado;  //guarda em sessao a confg de tempo
      //ajusta atributo de interacao    
      $_SESSION['interage'] = $instanciaNivel->interage;
    }
  }  
  
  $interage= (int)$_SESSION['interage'];
}

/*
 * DEFINIÇÂO DOS PAPEIS QUE O USUARIO EXERCE E RESPECTIVOS DIREITOS
 */ 
//1) PAPEL ALUNO: seta adequadamente os direitos e sessao 
if (!empty($_SESSION['COD_AL']) ) {
  if (empty($_SESSION['aluno'])) {     //AJUSTA NO CASO DE LOGIN
    $aluno = new Aluno($_SESSION['COD_AL']);
    $_SESSION['aluno']  = $aluno;

    if ($userRole==PUBLICO  || empty($userRole)) { 
      $userRole=ALUNO; $_SESSION['userRole']=$userRole;
    }

    //caso instancia inicial nao tenha sido definida
    if (empty($nivel->codNivel) || empty($instanciaNivel->codInstanciaNivel) ) {
      $userRole=ALUNO; $_SESSION['userRole']=$userRole;
      $instanciaNivel = $_SESSION['aluno']->getInstanciaNivelInicial($_SESSION['COD_PESSOA'],$mostraPassado);      
      $nivel               = $_SESSION['aluno']->getNivelInicial();  //o método anterior seleciona o nivel  

      //ajusta tempo atual, apos ter buscado nivel/instancia
      $mostraPassado = $instanciaNivel->mostraPassado; 
      $_SESSION['mostraPassado'] = $mostraPassado;  //guarda em sessao a confg de tempo
          
      //ajusta direitos de interacao
      $interage = $instanciaNivel->interage;
      $_SESSION['interage'] = $interage;
    }

  }
  //seta os direitos adequadamente
  $direitosUsuario['aluno']=1;
}

//2) PAPEL PROFESSOR: seta adequadamente os direitos e sessao
if (!empty($_SESSION['COD_PROF']) ) {
  if (empty($_SESSION['professor'])) {     //AJUSTA NO CASO DE LOGIN
    $professor = new Professor($_SESSION['COD_PROF']);
    $_SESSION['professor'] = $professor;
    if ($userRole==PUBLICO || empty($userRole)) { 
      $userRole=PROFESSOR;   $_SESSION['userRole']=$userRole;
    }
    //caso instancia inicial nao tenha sido definida
    if (empty($nivel->codNivel) || empty($instanciaNivel->codInstanciaNivel) ) {
      $userRole=PROFESSOR;   $_SESSION['userRole']=$userRole;    
      $instanciaNivel = $_SESSION['professor']->getInstanciaNivelInicial($_SESSION['COD_PESSOA'],$mostraPassado);
      $nivel          = $_SESSION['professor']->getNivelInicial();  //o método anterior seleciona o nivel

      //ajusta tempo atual, apos ter buscado nivel/instancia
      $mostraPassado = $instanciaNivel->mostraPassado; 
      $_SESSION['mostraPassado'] = $mostraPassado;  //guarda em sessao a confg de tempo

      //ajusta direitos de interacao
      $interage = $instanciaNivel->interage;
      $_SESSION['interage'] = $interage;
    }              
  }

  //seta os direitos adequadamente
  $direitosUsuario['professor']=1;     
}

//3) PAPEL Aministrador GERAL ou 4) Administrador de Nivel
if (isset($_SESSION['COD_ADM']) ) {
  if (!empty($_REQUEST['iniciarNavegacao']) ) { unset($_SESSION['navegacao']);  } //destroi a navegacao atual

  //Administrador de nivel
  if ($_SESSION['NIVEL_ACESSO']==2 || $_SESSION['NIVEL_ACESSO_FUTURO']==2 ) { 
    $direitosUsuario['admNivel']=1;
    //verifica se a sessao esta ok
    if ( empty($_SESSION['admNivel']) ) {
      if ($userRole==PUBLICO || empty($userRole) ) { 
        $userRole=ADM_NIVEL; 
        $_SESSION['userRole']=$userRole; 
      }
      $admNivel = new AdministradorNivel($_SESSION['COD_ADM']);
      $_SESSION['admNivel']  = $admNivel;
    }
    else { 
      $admNivel = $_SESSION['admNivel']; 
      //caso ocorra algum problema, seta adequadamente $userRole, desde que a sessao do admNivel esteja presente
      //verificar no servidor linux
      if (!empty($admNivel) && empty($userRole)) { $userRole = ADM_NIVEL; }
    }
    
    if ($userRole==ADM_NIVEL)  {
      //Busca o nivel que este administrador gerencia e instancia-o
      if (empty($_REQUEST['codNivel']) || empty($_REQUEST['codInstanciaNivel']) ) {
        $nivel = $admNivel->getNivelInicial(); 
        $instanciaNivel = $admNivel->getInstanciaNivelInicial();
      }
      else {
        $nivel = new Nivel($_REQUEST['codNivel']);
        $instanciaNivel = new InstanciaNivel($nivel,$_REQUEST['codInstanciaNivel']);
      }
      if (empty($_SESSION['navegacao'])) {
        $_SESSION['navegacao'] = new Navegacao($nivel,$instanciaNivel);
      }
      controlaNavegacao();
    }
  }
  else { //ADMINISTRADOR GERAL
    $direitosUsuario['adm']=1;
    $userRole = ADMINISTRADOR_GERAL;
    $_SESSION['userRole']=$userRole;
    controlaNavegacao();
  }
}


//5) ACESSO PUBLICO
if (!isset($_SESSION['USER_PESSOA']) && !isset($_SESSION['COD_AL']) && !isset($_SESSION['COD_PROF'])  && !isset($_SESSION['COD_ADM'])) {
  $direitosUsuario['publico']=1;
  $userRole = PUBLICO;
  $_SESSION['userRole']=$userRole;
  controlaNavegacao();
}

/*
 * Seta o codigo global desta instancia para ser usado pelos recursos.
 * A classe InstanciaNivel cuida de que seja criado, caso nao exista
 */
if (!empty($instanciaNivel)) {
  $_SESSION['codInstanciaGlobal'] = $instanciaNivel->codInstanciaGlobal;
}
else {
  //Se nao houver a instancia de nivel e o nivel setados adequadamente,
  //entao chamada verificaDireitos antecipadamente para o usuario nao poder ser
  //exibido
  verificaDireitos($direitosUsuario,$userRole,$nivel,$instanciaNivel,array(),$interage);
}
//por enquanto está aqui...
//session_write_close();
/* 
 * INSTANCIACAO DA CLASSE DE LAYOUT DO NUCLEO 
 * Pode ser especificada uma classe especial para o nivel todo ou apenas para certa instancia
 */
if (!empty($instanciaNivel->classePHP)) {  //Primeiro, Usa a classe definida na instancia, se houver
  $classe = $instanciaNivel->classePHP;
}
else if (!empty($nivel->classePHP)) {  //Depois, Usa a classe definida no nivel
  $classe = $nivel->classePHP;
}
//Tenta ler dinamicamente a classe ESPECIFICA 
if (!empty($classe) && !class_exists($classe) && file_exists($caminhoBiblioteca.'/'.strtolower($classe).'.inc.php') ) {
  include($caminhoBiblioteca.'/'.strtolower($classe).'.inc.php');
}

//Se nao houver nenhuma customizacao, ou se nao conseguir localizar a classe especifica acima,
//usa a classe padrao
if ( empty($classe) || !class_exists($classe) ) {  
  $classe = CLASSE_PHP_PADRAO; 
  //incluir a classe PADRAO dinamicamente
  if (!class_exists($classe) && file_exists($caminhoBiblioteca.'/'.strtolower($classe).'.inc.php') ) {
    include($caminhoBiblioteca.'/'.strtolower($classe).'.inc.php');
  }
}

//Instancia a classe do núcleo dinâmicamente!
$pagina = new $classe($nivel,$instanciaNivel,$direitosUsuario,$userRole,$_SESSION['navegacao'],'',$mostraPassado,$interage);

//////////
//por segurança, verifica se que os direitos/papel tambem estejam adequados ao nivel/instancia atuais!!!!!!
//a classe de pagina deve fornecer um metodo getAcesso()
verificaDireitos($direitosUsuario,$userRole,$nivel,$instanciaNivel,$pagina->getAcesso(),$interage);

///////////
//note($_SESSION); exit;
/* 
 * Aqui a pagina ja foi instanciada mas ainda nao foi impressa.
 * Isso siginifica que ainda podemos fazer modificacoes, caso seja necessario
 */
//para poder controlar qual recurso deve ser exibido, se necessario, no caso a ferramenta de gerenciar instancias
if ($_REQUEST['administraInstancia']) {  $pagina->setAdministraInstancia(); }  
/* 
 A PAGINA AQUI EH JOGADA PARA O NAVEGADOR 
 DEVE HAVER UM METODO $classe->imprime();
 */
$pagina->imprime();


/*
 * Controle da navegacao  (talvez otimizar para instanciar os objetos on the fly ao inves de guardar na sessao
 */
function controlaNavegacao() {
  global $nivel,$instanciaNivel,$userRole;

  //Navegaao por combo unico que serve tanto para administrador geral, administrador de nivel e publico
  if ( ($_REQUEST["voltar"]) && (!empty($_SESSION["navegacao"])) ) { //estamos voltando, e dando pop na navegacao
    $_SESSION["navegacao"]->pop();
    $nivel = $_SESSION["navegacao"]->getNivelAtual();
    $instanciaNivel = $_SESSION["navegacao"]->getInstanciaNivelAtual();
  }
  else if ($_REQUEST["seguirAdiante"] && !empty($_SESSION["navegacao"]) ){ 
    if ($_SESSION["navegacao"]->getcodNivelAtual()!=$_REQUEST["codNivel"])  { 
      $nivel = new Nivel($_REQUEST["codNivel"]); 
      $instanciaNivel = new InstanciaNivel($nivel,$_REQUEST["codInstanciaNivel"]);
      $_SESSION["navegacao"]->push($nivel,$instanciaNivel);
    }
    else { //aqui o usuario deve ter dado refresh, entao vamos manter o nivel atual
      $nivel = $_SESSION["navegacao"]->getNivelAtual();
      $instanciaNivel = $_SESSION["navegacao"]->getInstanciaNivelAtual();
    }
  }
  else if ($_REQUEST["move"]) {  //quando o usuario quiser voltar para um nivel/instancia ja navegados

  }
  else if (!empty($_SESSION["navegacao"])) {  //o usuario deve ter dado refresh e nao estava no seguir adiante
    $nivel = $_SESSION["navegacao"]->getNivelAtual();
    $instanciaNivel = $_SESSION["navegacao"]->getInstanciaNivelAtual();
  }
  //o administrador está entrando em uma comunidade
  else if (!empty($_REQUEST["iniciarNavegacao"]) && $userRole==ADMINISTRADOR_GERAL && !empty($_REQUEST["codNivel"]) ) {  
      $nivel = new Nivel($_REQUEST["codNivel"]); 
      $instanciaNivel = new InstanciaNivel($nivel,$_REQUEST["codInstanciaNivel"]);
      $_SESSION["navegacao"] = new Navegacao($nivel,$instanciaNivel);  
  }
  else {  //estamos entrando pela primeira vez!
    $nivel = new Nivel(NIVEL_INICIAL); 
    $instanciaNivel = new InstanciaNivel($nivel,INSTANCIA_INICIAL);
    $_SESSION["navegacao"] = new Navegacao($nivel,$instanciaNivel);
  }
  /*
  //Guarda o logotipo, pois pode ser usado pelos subniveis
  if ($instanciaNivel->codArquivoLogotipo) { 
    $_SESSION['codArquivoLogotipo'] = $instanciaNivel->codArquivoLogotipo; 
  }
  */
}

/*
 *  verificaDireitos
 *  por enquanto verifica apenas se o nivel/instancia nao estao vazios
 *  @param direitosUsuario
 *  @param userRole gerados no index
 *  @param acesso gerado na classe de layout
 */   
function  verificaDireitos($direitosUsuario,$userRole,$nivel,$instanciaNivel,$acesso,$interage) {
  global $mostraPassado; //variavel setada no script de acordo com a navegacao do usuario por instancias atuais/passadas
  
  $breakRules=0;

  //verifica se o nivel/instancia nao estao vazios
  //se nao for publico e estiver vazio, entao há algum problema... 
  //o usuario deve ser relacionada a algum nivel
  if ( $userRole!=PUBLICO && empty($instanciaNivel->codInstanciaGlobal) ) {  $breakRules=1;  }
  //usuario público nao pode ter sessão de usuario ativada
  if ( $userRole==PUBLICO && isset($_SESSION['USER_PESSOA']) ) { $breakRules=1;  }
  //usuario aluno tem de ter  sessão de aluno ativada
  if ( $userRole==ALUNO && !isset($_SESSION['aluno']) ) { $breakRules=1;  }
  //usuario professor tem de ter  sessão de professor ativada  
  if ( $userRole==PROFESSOR && !isset($_SESSION['professor']) ) { $breakRules=1;  } 
  //usuario administrador  de nivel tem de ter  sessão de administrador de nivel ativada  
  if ( $userRole==ADM_NIVEL && !isset($_SESSION['admNivel']) ) { $breakRules=1;  }
  
  //Verifica se o aluno está com acesso correto
  if ($userRole==ALUNO ) {
    if (!$direitosUsuario['aluno']) { $breakRules=1;} //sem direito de aluno
    
    //precisa ter acesso ao nivel/instancia 
    if (!$acesso['direitosAluno'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['acesso']
    && !$acesso['direitosComunidade'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['acesso']) {
      $breakRules=1; 
    }
    //se a interacao for permitida precisa ser igual ao acesso (buscada do banco e armazenada em memoria)
    if ($interage) {
      if (    !$acesso['direitosAluno'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['interage']
        && !$acesso['direitosComunidade'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['interage']) {
      $breakRules=1; 
      }
    }
  }
  //Verifica se o professor está com acesso correto
  if ($userRole==PROFESSOR ) {
    if (!$direitosUsuario['professor']) { $breakRules=1;} //sem direito de professor
    
    //precisa ter acesso ao nivel/instancia 
    if (!$acesso['direitosProfessor'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['acesso']
    && !$acesso['direitosComunidade'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['acesso']) {    
      $breakRules=1; 
    }
    //se a interacao for permitida precisa ser igual ao acesso (buscada do banco e armazenada em memoria)
    if ($interage) {
      if (   !$acesso['direitosProfessor'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['interage']
       && !$acesso['direitosComunidade'][$nivel->codNivel][$instanciaNivel->codInstanciaNivel]['interage']) {
      $breakRules=1;  
      }
    }
  }
//   note($acesso); note($instanciaNivel);
  
  
  //debug de acesso
  /*
  echo "<!-- ";
  echo "user Role em sessao".$_SESSION['userRole'];
  echo " | userROle: ".$userRole;
  echo ' | Nivel: '.$nivel->codNivel;
  echo ' | Instancia Nivel: '.$instanciaNivel->codInstanciaNivel;
  print_r($instanciaNivel);
  echo ' | MOSTRA PASSADO: '.$mostraPassado;
  echo ' | INTERAGE: '.$interage;

  echo " -->"; 
  */
  if ($breakRules) {  
    if ($instanciaNivel->instanciaInicialTableUsed) { //caso a instancia inicial nao esteja correta, exclui ela e reexecuta o index.php
      Pessoa::deleteInstanciaNivelInicial($_SESSION['COD_PESSOA'],$nivel,$instanciaNivel);
      echo "<script>location.href='./index.php';</script>";
      //echo 'instancia inicial excluida';      
      exit; die;
    }
    session_unset();
    echo "<script>alert('Seu usuario foi criado no NAVi, mas voce ainda nao tem inscricao em nenhuma Atividade. Inscreva-se e/ou peca ao professor!'); location.href='./index.php';</script>";
    exit; die;
  }
  else {
    if ($userRole==ALUNO ) {
      $_SESSION['aluno']->setInstanciaNivelInicial($_SESSION['COD_PESSOA'],$nivel,$instanciaNivel,(int)$mostraPassado, (int)$interage);    
    }
    else if ($userRole==PROFESSOR ) {
      $_SESSION['professor']->setInstanciaNivelInicial($_SESSION['COD_PESSOA'],$nivel,$instanciaNivel,(int)$mostraPassado,(int)$interage);
    }    
  }
}
?>
