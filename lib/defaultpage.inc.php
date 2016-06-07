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

include($caminhoBiblioteca."/arquivomultinavi.inc.php");
include($caminhoBiblioteca."/menu.inc.php");
//ini_set("display_errors",1);
//error_reporting(E_ALL ^ E_NOTICE);
/* Classe Padrao de exibição
 * Divide a pagina em : 
 * cabecalho html
 * logotipo, escolha da Instancia do subNivel
 *  menu (com iframe da ferramenta escolhida) e rodape
 *  
 *
 *
 *
 * As partes ficam dentro dos atributos, alimentados pelos métodos, 
 * e o método imprime faz a montagem final da página
 * 
 */
class DefaultPage {
  //cabecalho html
  var $cabecalho;
  //escolha do subnivel, quando aplicável
  var $escolhaSubNivel;
  //Mostra login ou nome do usuario autenticado  
  var $caixaLogin;
  //repositorio que permite ver os torpedos recebidos
  var $caixaTorpedos;
  //logotipo do navi e da instancia sistemica atualmente selecionada
  var $logotipos;
  //menu da instancia de nivel
  var $menu;
  //recursos que estao disponiveis sempre
  var $recursoFixo;
  //caixa de instancias do professor e aluno
  var $caixaInstancias;
  //Numero de instancias ao que o professor ou aluno se relaciona (por enquanto nao será mais utilizado) 
  //var $numeroInstancias;
  //widget que mostra o botao de sair, busca de pessoas e a inscrição em comunidades
  var $widgetsFinais;
  //widget que mostra as comunidades e permite seleção
  var $widgetComunidades;
  //widget onde o recurso será carregado
  var $espacoRecursos;
  //rodape, fecha o html
  var $rodape;
  //qual o recurso que abre por default!
  var $recursoPadrao;
  //nivel sistemico
  var $nivel;
  //instancia de nivel
  var $instanciaNivel;
  //Indica os acessos disponiveis a este usuario:
  var $direitosUsuario;
  //qual o papel do usuario neste exato momento (adm nivel, professor ou aluno)
  var $userRole;
  //quais as instancias permitidas a este usuario acessar
  var $acesso=array();
  //$_SESSION["navegacao"] 
  var $navegacao;
  //como utillizado na administracao de pessoas
  var $comboAdmPessoas;
  //Controle para mostrar o passado
  var $mostraPassado;
/*
   * Construtor. Executa os metodos que constroem cada parte da pagina,
   * e que serao impressos por DefaultPage->imprime()
   */
  function DefaultPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,$navegacao='',$param='',$mostraPassado=0,$interage) {
  
    if (empty($navegacao)) {
      $this->navegacao=$_SESSION["navegacao"];
    }
    else{
      $this->navegacao=$navegacao;
    }
    $this->comboAdmPessoas=$param['comboAdmPessoas'];
    
    //Inicializa atributos da classe
    $this->nivel = $nivel;
    $this->instanciaNivel = $instanciaNivel;
    $this->direitosUsuario = $direitosUsuario;  //direitos do usuario atual
    $this->userRole = $userRole; //papel atualmente exercido pelo usuario
    $this->mostraPassado =   $mostraPassado; //controle para mostrar somente instancias atuais ou tambem as passadas
    
    //Funcoes que constroem a pagina inicialmente (antes da impressao na tela propriamente dita)
    $this->initCabecalho();
    $this->initLogotipos();
    $this->initEscolhaSubNivel();
    $this->initCaixaLogin();
    if($this->userRole!=PUBLICO) { 
      $this->initCaixaInstancias();
      $this->initWidgetComunidades();      
      $this->initWidgetsFinais();
       
      $this->initCaixaTorpedos();
      //Busca configurações da instancia: ve se o recurso padrão foi setado
      $recursoPadrao = $this->instanciaNivel->getRecursoPadrao();
      if (!empty($recursoPadrao)) { 
        $this->recursoPadrao = $recursoPadrao; 
      }
      //Não foi configurado um recurso padrão para os usuários autenticados,
      //entao usamos um padrao
      else {               
        // nivel que relaciona alunos e professores: o recurso padrao é 'apresentacao'
        if ($this->instanciaNivel->relacionaPessoas()) {
          $this->recursoPadrao = 'alunos/index.php';
        }
        else {
          $this->recursoPadrao = "noticias/noticias.php";
        } 
      }
    }
    //usuário NAO-AUTENTICADO: noticias é o padrao
    else {
      $this->recursoPadrao = "noticias/noticias.php";    
    }
    //recursos que aparecem em qualquer nivel, nao dependem necessariamente
    //do relacionamento com a instancia
    $this->initRecursoFixo(); 
    //menu de recursos
    $this->initMenu();
    //constroi iframe para onde serao carregados os recursos.
    $this->initEspacoRecursos();  
    
    $this->initRodape();
  }
  /*
   * Inicializacoes
   */
  function initCabecalho() {
    global $urlJs;
    
    $this->cabecalho = '<html>';
    $this->cabecalho.= '<head>';
    $this->cabecalho.= '<link rel="stylesheet" href="./css/'.$this->getCss().'" type="text/css">';
    $this->cabecalho.= '<title>NAVi - Rede Interativa de Aprendizagem.</title>';
    //$this->cabecalho.= '<link rel="shortcut icon" href="imagens/logo.ico">';
    $this->cabecalho.= '<script language="JavaScript" src="'.$urlJs.'/nucleo.js"></script>';    
    $this->cabecalho.= '</head>';
    $this->cabecalho.= '<body onResize="alturaRecurso();">';
  }
  /*
   * escolha do subnivel, quando aplicável
   */
  function initEscolhaSubNivel() {
    global $url,$urlImagem;
    
    //Busca nivel/instancia anteriores na navegacao; se nao der busca no nivel atual    
    if (!empty($this->navegacao)) { $nivelAnt = $this->navegacao->getNivelAnterior(); }
    
    if (empty($nivelAnt) && !$this->nivel->isFirst) { 
      $this->nivel->isFirst=1; //neste contexto, este nivel eh o primeiro, pois o adm nivel nao pode 'subir'
      $nivelAnt = $this->nivel->getNivelAnterior();
    }
    // Mostra toda a hierarquia navegada          
    $this->escolhaSubNivel .= "<div class='nomeNivel'>";
    $this->escolhaSubNivel .= $this->imprimeNavegacaoSimples();
    $this->escolhaSubNivel .= "</div>";
    
    if ($this->userRole==PUBLICO || Pessoa::podeAdministrar($this->userRole,$this->nivel,$interage)) {
      //BUSCA  os subniveis e mostra o combo se houver mais de um subnivel
      $this->escolhaSubNivel .= '<div class="nomeSubNivel">';    
      $subNiveis=$this->nivel->getSubNiveis();
      $subNivelSelecionado = 0; $codSubNivelSelecionado = (int)$_REQUEST['codSubNivel'];            
      if (!empty($codSubNivelSelecionado) && empty($subNivelSelecionado)) {
        $subNivelSelecionado=new Nivel($codSubNivelSelecionado); $msgEscolha = 'Selecione ';
      }
      else { //Se nao houver o subnivel entao eh o ultimo
        $this->nivel->isLast=1; $msgEscolha = 'Escolha o pr&oacute;ximo n&iacute;vel ';
      }   
      if (count($subNiveis->records)>1) {
        $obj = new StdClass(); $obj->nome='Nenhum selecionado'; 
        $subNiveis->records=array_merge(array('-1'=>$obj),$subNiveis->records); 
        $param['name'] = 'codSubNivel';    $param['id'] = 'subNiveis';
        $param['optionName'] = 'nome';    $param['optionValue'] = 'codNivel'; 
        $param['optionSelected'] = $subNivelSelecionado->codNivel;
        $param['onChange'] = 'document.formSubNivel.submit();';
        $this->escolhaSubNivel .='<form name="formSubNivel" method="GET" action="'.$_SERVER['PHP_SELF'].'">';
        $this->escolhaSubNivel .=$msgEscolha.$subNiveis->combo($param);
        $this->escolhaSubNivel .='</form>';
      }
      elseif (count($subNiveis->records)==1)   { //INSTANCIA DIRETAMENTE, TEM APENAS 1 SUBNIVEL
        $subNivelSelecionado = new Nivel(); 
        foreach($subNiveis->records[0] as $campo=>$valor) {$subNivelSelecionado->$campo=$valor;}
        $this->escolhaSubNivel .= "Selecione <b>".$subNivelSelecionado->nome."</b>";          
      }
      else {
        $this->escolhaSubNivel .= "<br>"; 
      } //linha em branco para ajustar altura                
      $this->escolhaSubNivel .= "</div>";
    }
    
    //Formulario para escolher o nivel    
    if($this->comboAdmPessoas) {
      $this->escolhaSubNivel  .= "<form name='formNivel' method='GET' action='".$url."/tools/admPessoa/index.php'>";
    }
    else {
      $this->escolhaSubNivel  .= "<form name='formNivel' method='GET' action='".$url."/index.php'>";
    }
    
    if (!empty($subNivelSelecionado)) {
      $this->escolhaSubNivel .= '<input type="hidden" name="codNivel" value="'.$subNivelSelecionado->codNivel.'">';
      $this->escolhaSubNivel .= '<input type="hidden" name="seguirAdiante" value="1">';      
    }    
    $comboInstanciasSubNivel=$this->comboInstanciasSubNivel($subNivelSelecionado);
             
    //Constroi objetos de ir e voltar
    //Link Voltar
    $voltar = new Voltar($_SERVER['PHP_SELF'].'?voltar=1');
    $voltar->quebraLinhaTexto='';  
    if ($this->nivel->isFirst) { $voltar->link='';  $voltar->imagem='voltarBloqueado.gif';  }      
    //Link de prosseguir
    $param['link']='';      $param['imagem']='ir.gif';
    $ir = new LinkImagem($param); $ir->addProp('onClick','document.formNivel.submit();');
    //if ($this->nivel->isLast || $this->nivel->isEmpty || empty($this->instanciaNivel->subNivel) ) {  
    //if ($this->nivel->isEmpty && empty($subNivelSelecionado) ) {  
    if ($this->nivel->isEmpty ) {  //basta combo de instancias estar vazio para nao permitir ir adiante  
      $ir->prop['onClick']='';
      $ir->imagem="irBloqueado.gif"; 
    }

    $this->escolhaSubNivel .= "<div class='escolhaInstanciaSubNivel'>".$voltar->imprime(); //botao de voltar
    $this->escolhaSubNivel .= $comboInstanciasSubNivel;   //combo com as instancias do sub nivel
    $this->escolhaSubNivel .= $ir->imprime()."</div></form>";  //botao de ir, finaliza div e finaliza form   

    /*
     * Mostra as instancias para navegacao direta  para adm de nivel 
     */         
    if ($this->direitosUsuario['admNivel']) {
    
      $todosNiveis = $_SESSION['admNivel']->getTodosNiveis();
      
      $this->escolhaSubNivel .= '<table class="instanciasAdmNivel" cellspacing="0" cellpadding="0">';
      $this->escolhaSubNivel .= '<tr><td class="tituloAdmNivel">Administrar &nbsp;&nbsp;</td>';
      $this->escolhaSubNivel .= '<td><table cellspacing="0" cellpadding="0">';
      while (list(,$inst) = each($todosNiveis) ) {
         
         if ($inst->nivel->codNivel==$this->nivel->codNivel && $inst->codInstanciaNivel==$this->instanciaNivel->codInstanciaNivel) {
           $classeAdm='instanciaAtual';
         }
         else { $classeAdm='nivelAdm'; }
         $this->escolhaSubNivel .='<td class="'.$classeAdm.'">';
         $this->escolhaSubNivel .='<a href="'.$_SERVER['PHP_SELF'].'?iniciarNavegacao=1&codNivel='.$inst->nivel->codNivel.'&codInstanciaNivel='.$inst->codInstanciaNivel.'&userRole='.ADM_NIVEL.'">';
         if ($inst->nivel->mostraNomeNivelPai) {           
           $pai = $inst->getPai(); 
           $this->escolhaSubNivel .=$pai->getAbreviaturaOuNome().SEPARADOR;
         }
         $this->escolhaSubNivel .= $inst->getAbreviaturaOuNome();
         $this->escolhaSubNivel .= "</a></td>";
         //direitos de acesso para esta instancia         
         $this->acesso['direitosAdmNivel'][$inst->nivel->codNivel][$inst->codInstanciaNivel]=1;
      }
      $this->escolhaSubNivel .= '</tr></table>';
      $this->escolhaSubNivel .= '</td></tr></table>';      
    }
  }
  /*
   * Caixa com as instancias sistemicas, distribuidas nos respectivos
   * ramos de hierarquia, para o usuario selecionar.   
   *
   */      
  function initCaixaInstancias() {
    global $urlImagem;
    
    $this->caixaInstancias='';
    /*
     *  A MONTAGEM DA ARVORE SISTEMICA ABAIXO, BEM COMO OS DIREITOS, 
     *  PODERIA SER COLOCADA EM SESSAO PARA EVITAR NOVAS CONSULTAS; 
     *  PODERIA-SE FAZER UM BENCHMARK PARA VERIFICAR 
     */          
    /*
     * Mostra as instancias para navegacao direta para professor
     */
    
    if ($this->direitosUsuario['professor']) {
      //este link vai permitir ao usuario fechar, expandir e diminuir a caixa de instancias
      $linkControlaCaixa="<span style='position:relative; cursor:pointer; float:right;'>";
      if ($this->direitosUsuario['aluno']) {
        //$linkControlaCaixa="<span style='position:relative; cursor:pointer; float:right;'>";
        $linkControlaCaixa .="<span onClick=\"trocaExibicao('caixaInstanciasProfessor','tituloArvoreInstanciasProfessor','caixaInstanciasAluno','tituloArvoreInstanciasAluno');\" style='color:red;'>&nbsp;A&nbsp;</span>";
      }
      //else  {        $linkControlaCaixa="<span style='position:relative; left:20; cursor:pointer;'>"; }
      $linkControlaCaixa.= '<span onClick="aumenta(\'caixaInstanciasProfessor\');" ><img src="'.$urlImagem.'/aumenta.gif"></span><span onClick="diminui(\'caixaInstanciasProfessor\');">&nbsp;&nbsp;<img src="'.$urlImagem.'/diminui.gif"></span><span onClick="fecha(\'caixaInstanciasProfessor\');">&nbsp;&nbsp;<img src="'.$urlImagem.'/fecha.png"></span>';
      $linkControlaCaixa.='</span>';
      //se neste momento o usuario não for professor, entao este widget deve ficar invisivel
      if ($this->userRole==ALUNO) { $visibilidadeProfessor = ' display:none; ';  }
      else  { $visibilidadeProfessor='';} 
      //$this->numeroInstancias=0;
      $arvoreInstanciasRelacionamento = $_SESSION['professor']->getInstanciasRelacionamento($this->mostraPassado);
      
      //Link que alterna atividades entre atuais (periodo atual) e todas (inclui antes do periodo atual, definido em cada instancia do nivel)
      $linkTempo='';
      if (Nivel::existeNivelTemporal()) {
        $linkTempo='<span style="position:relative; cursor:pointer; float:left;">';
        if ($this->mostraPassado) { //envia userRole para interface ficar consistente, pois a caixa de instancias é trocada via JS para aluno/professor                      
          if ($_SESSION['professor']->possuiRelacionamentoNoTempo(0)){ //somente mostra o link para o outro tempo se houver instancias 
            $linkTempo.= '<a href=index.php?mostraPassado=0&userRole='.PROFESSOR.'> Atuais </a>|';
          }
        }
        else {
          $linkTempo.= '<span style="background-color:#EFEFEF;cursor:default;"> Atuais </span>|';
        }
        
        if (!$this->mostraPassado) {                     
          if ($_SESSION['professor']->possuiRelacionamentoNoTempo(1)){ //somente mostra o link para o outro tempo se houver instancias 
            $linkTempo.='<a href=index.php?mostraPassado=1&userRole='.PROFESSOR.'> Passadas </a>';
          }
        }
        else {
          $linkTempo.= '<span style="background-color:#EFEFEF;cursor:default;">Passadas</span>';
        }
        $linkTempo.= '</span>';
      } 
      
      $this->caixaInstancias .= "<div id='tituloArvoreInstanciasProfessor' class='tituloArvoreInstanciasProfessor'  style='".$visibilidadeProfessor."'><span style='position:relative;float:left;'>Atividades Professor:&nbsp;</span>".$linkTempo.$linkControlaCaixa."</div>";
      $this->caixaInstancias .= "<div id='caixaInstanciasProfessor' class='arvoreInstanciasProfessor'  style='".$visibilidadeProfessor."' title='Todos os n&iacute;veis onde o professor est&aacute; relacionado, e a hierarquia superior, em cada linha.'>";
      $this->caixaInstancias .= "<table cellspacing='0' cellpadding='0' class='tabelaArvoreInstancias'>";
      
      if (!empty($arvoreInstanciasRelacionamento)) {
        for($i=0;$i<count($arvoreInstanciasRelacionamento);$i++) {
          $hierarquiaNiveis=$arvoreInstanciasRelacionamento[$i][0];

          while (list(,$linha) = each($arvoreInstanciasRelacionamento[$i][1]) ) {            
            $novaLinha=''; $classeLinha='linhaHierarquia';
            //Percorre a hierarquia e vai buscando nome e codigo de cada instancia de nivel

            for ($j=(count($hierarquiaNiveis)-1);$j>=0;$j--) { 
              $nivelHierarquia = $hierarquiaNiveis[$j];                           
              //list(,$nome) = each($linha); 
              //list(,$codInstanciaNivel) = each($linha);
              $attribNome='nome'.$j;  $attribPK='pk'.$j;
              $nome = $linha->$attribNome; 
              $codInstanciaNivel = $linha->$attribPK;
                         
              //verifica o direito mais alto que o professor possui nesta instancia
              if ($this->userRole==ADMINISTRADOR_GERAL) { $userRoleProfessor=ADMINISTRADOR_GERAL; }
              elseif ($this->acesso['direitosAdmNivel'][$nivelHierarquia->codNivel][$codInstanciaNivel]) {
                $userRoleProfessor=ADM_NIVEL.'&iniciarNavegacao=1';
              }
              else { 
                $userRoleProfessor=PROFESSOR;
                //.'&iniciarNavegacao=1'; 
              }

              //Destaca a instancia atual ou se for administrada pelo usuario
              if ($codInstanciaNivel==$this->instanciaNivel->codInstanciaNivel && $nivelHierarquia->codNivel==$this->nivel->codNivel) {
                $classeCelula='instanciaAtual'; $classeLinha='hierarquiaInstanciaAtual';
                $title=$nivelHierarquia->nome.' (voc&ecirc; est&aacute; aqui!)';
              }
              elseif ($userRoleProfessor!=PROFESSOR) {           
                $classeCelula='instanciaAdministrada'; $title=$nivelHierarquia->nome.' (voc&ecirc; &eacute; administrador aqui)';
              }
              else { $classeCelula='celulaInstancia'; $title=$nivelHierarquia->nome;}


              $novaLinha.='<td class="'.$classeCelula.'" title="'.$title.'"><a href="'.$_SERVER['PHP_SELF'].'?codNivel='.$nivelHierarquia->codNivel."&codInstanciaNivel=".$codInstanciaNivel.'&userRole='.$userRoleProfessor.'&interage='.$linha->interage.'">'.$nome.'</a></td>';
              //configura os direitos de acesso!!
              $this->acesso['direitosProfessor'][$nivelHierarquia->codNivel][$codInstanciaNivel]['acesso']=1;
              if (!isset($this->acesso['direitosProfessor'][$nivelHierarquia->codNivel][$codInstanciaNivel]['interage'])) { 
                $this->acesso['direitosProfessor'][$nivelHierarquia->codNivel][$codInstanciaNivel]['interage']=$linha->interage;
              }               
            }
            //$this->numeroInstancias++; //conta o numero de instancias deste usuario
            $this->caixaInstancias .= '<tr class="'.$classeLinha.'">';
            $this->caixaInstancias .= $novaLinha;
            $this->caixaInstancias .= "</tr>";
          }
        }
      }
      $this->caixaInstancias .= "</table>";
      $this->caixaInstancias .= "</div>";
    }
    
    /* É o mesmo codigo acima, porém com as características de aluno.
     * Como são vários atributos, pelo menos por hora está replicado, ao inves de colocado em pessoa
     *
     * Mostra as instancias para navegacao direta para aluno
     */
    if ($this->direitosUsuario['aluno']) {       
      //este link vai permitir ao usuario fechar, expandir e diminuir a caixa de instancias
      $linkControlaCaixa="<span style='position:relative; cursor:pointer; float:right;'>";
      if ($this->direitosUsuario['professor']) {
        //$linkControlaCaixa="<span style='position:relative; left:28; cursor:pointer;'>";
        $linkControlaCaixa .="<span onClick=\"trocaExibicao('caixaInstanciasAluno','tituloArvoreInstanciasAluno','caixaInstanciasProfessor','tituloArvoreInstanciasProfessor');\"  style='color:red;'>&nbsp;P&nbsp;</span>";
      }
      //else  {        $linkControlaCaixa="<span style='position:relative; left:43; cursor:pointer;'>"; }
      $linkControlaCaixa .= "<span onClick=\"aumenta('caixaInstanciasAluno');\"  style='cursor:pointer;' title='Ajusta a janela de acordo com a necessidade.'><img src='".$urlImagem."/aumenta.gif' ></span><span onClick=\"diminui('caixaInstanciasAluno');\" title='Deixa a janela no tamanho padrão.'>&nbsp;&nbsp;<img src='".$urlImagem."/diminui.gif'></span><span onClick=\"fecha('caixaInstanciasAluno');\" title='Fecha a janela.'>&nbsp;&nbsp;<img src='".$urlImagem."/fecha.png'></span>";
      $linkControlaCaixa.="</span>";
      //se neste momento o usuario for professor, ou se a caixa de professor 
      //estiver visível, entao este widget deve ficar invisivel
      if ($this->userRole==PROFESSOR || (empty($visibilidadeProfessor) && $this->direitosUsuario['professor'])) { 
        $visibilidadeAluno = " display:none; ";  
      } 
      else  { 
        $visibilidadeAluno='';
      }
      //$this->numeroInstancias=0;
      $arvoreInstanciasRelacionamento = $_SESSION['aluno']->getInstanciasRelacionamento($this->mostraPassado);

      //Link que alterna atividades entre atuais (periodo atual) e todas (inclui antes do periodo atual, definido em cada instancia do nivel)
      $linkTempo='';
      if (Nivel::existeNivelTemporal()) {
        $linkTempo='<span style="position:relative; cursor:pointer; float:left;">';
        if ($this->mostraPassado) { //envia userRole para interface ficar consistente, pois a caixa de instancias é trocada via JS para aluno/professor                      
          if ($_SESSION['aluno']->possuiRelacionamentoNoTempo(0)){ //somente mostra o link para o outro tempo se houver instancias 
            $linkTempo.= '<a href=index.php?mostraPassado=0&userRole='.ALUNO.'> Atuais </a>|';
          }
        }
        else {
          $linkTempo.= '<span style="background-color:#EFEFEF;cursor:default;"> Atuais </span>|';
        }
        
        if (!$this->mostraPassado) {                     
          if ($_SESSION['aluno']->possuiRelacionamentoNoTempo(1)){ //somente mostra o link para o outro tempo se houver instancias 
            $linkTempo.='<a href=index.php?mostraPassado=1&userRole='.ALUNO.'> Passadas </a>';
          }
        }
        else {
          $linkTempo.= '<span style="background-color:#EFEFEF;cursor:default;">Passadas</span>';
        }
        $linkTempo.= '</span>';
      }
      
      //Desenha a caixa de instancias
      $this->caixaInstancias .= "<div id='tituloArvoreInstanciasAluno' class='tituloArvoreInstanciasAluno' style='".$visibilidadeAluno."'><span style='position:relative; float:left;'>Atividades Aluno:&nbsp;</span>".$linkTempo.$linkControlaCaixa."</div>";      
      $this->caixaInstancias .= "<div id='caixaInstanciasAluno' class='arvoreInstanciasAluno'  style='".$visibilidadeAluno."' title='Todos os n&iacute;veis  onde o aluno est&aacute; relacionado, e a hierarquia superior, em cada linha.'>";
      $this->caixaInstancias .= "<table cellspacing='0' cellpadding='0' class='tabelaArvoreInstancias'>";

      if (!empty($arvoreInstanciasRelacionamento)) {
        for($i=0;$i<count($arvoreInstanciasRelacionamento);$i++) {
          $hierarquiaNiveis=$arvoreInstanciasRelacionamento[$i][0];

          while (list(,$linha) = each($arvoreInstanciasRelacionamento[$i][1]) ) {            
            $novaLinha=''; $classeLinha='linhaHierarquia';
            //Percorre a hierarquia e vai buscando nome e codigo de cada instancia de nivel

            for ($j=(count($hierarquiaNiveis)-1);$j>=0;$j--) { 
              $nivelHierarquia = $hierarquiaNiveis[$j];                           
              //list(,$nome) = each($linha); 
              //list(,$codInstanciaNivel) = each($linha);
              $attribNome='nome'.$j;  $attribPK='pk'.$j;
              $nome = $linha->$attribNome; 
              $codInstanciaNivel = $linha->$attribPK;
              
              //Verifica o direito mais alto que o aluno possui nesta instancia
              if ($this->userRole==ADMINISTRADOR_GERAL) { $userRoleAluno=ADMINISTRADOR_GERAL.'&iniciarNavegacao=1'; }
              elseif ($this->acesso['direitosAdmNivel'][$nivelHierarquia->codNivel][$codInstanciaNivel]) {
                $userRoleAluno=ADM_NIVEL.'&iniciarNavegacao=1';
              }
              else { $userRoleAluno=ALUNO; }

              //Destaca a instancia atual ou se for administrada pelo usuario
              if ($codInstanciaNivel==$this->instanciaNivel->codInstanciaNivel && $nivelHierarquia->codNivel==$this->nivel->codNivel) {
                $classeCelula='instanciaAtual'; $classeLinha='hierarquiaInstanciaAtual';
                $title=$nivelHierarquia->nome.' (você está aqui!)';
              }
              elseif ($userRoleAluno!=ALUNO) {           
                $classeCelula='instanciaAdministrada'; $title=$nivelHierarquia->nome. ' (você é administrador aqui)';
              }
              else { 
                $classeCelula='celulaInstancia'; $title=$nivelHierarquia->nome;
              }
              
              $novaLinha.='<td class="'.$classeCelula.'" title="'.$title.'"><a href="'.$_SERVER['PHP_SELF'].'?&codNivel='.$nivelHierarquia->codNivel.'&codInstanciaNivel='.$codInstanciaNivel.'&userRole='.$userRoleAluno.'&interage='.$linha->interage.'">'.$nome.'</a></td>';
              //configura os direitos de acesso!!
              $this->acesso['direitosAluno'][$nivelHierarquia->codNivel][$codInstanciaNivel]['acesso']=1; 
              if (!isset($this->acesso['direitosAluno'][$nivelHierarquia->codNivel][$codInstanciaNivel]['interage'])) { 
                $this->acesso['direitosAluno'][$nivelHierarquia->codNivel][$codInstanciaNivel]['interage']=$linha->interage;  
              }             
            }
            //$this->numeroInstancias++; //conta o numero de instancias deste usuario
            $this->caixaInstancias .= '<tr class="'.$classeLinha.'">';
            $this->caixaInstancias .= $novaLinha;
            $this->caixaInstancias .= '</tr>';
          }
        }
      }

      $this->caixaInstancias .= '</table>';
      $this->caixaInstancias .= '</div>';
    }
  }
  /*
   * Trata o espaço de torpedos
   * Desenha os icones de torpedo novos; clicando-se nele pode-se ver os torpedos  
   */
  function initCaixaTorpedos() {
    global $urlImagem;
    if (!empty($_SESSION['COD_PESSOA'])) {
      $this->caixaTorpedos = "<span id='torpedo'><div align='right'><img src='".$urlImagem."/fecha.png' onClick='showTorpedos();' style='cursor:pointer;'></div>Sem torpedos novos</span>";
    }
  }
  /*
   * Trata o espaço de login.
   * Desenha a caixa de login para usuários não-logados, e para logados 
   * exibe o nome e o link de encerrar sessao.   
   */
  function initCaixaLogin() {
    global $urlImagem;
    define(TAMANHO_NOME,25);
    //$diasSemana = array("Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado");
    
    $this->caixaLogin = "";
		if ( $_SESSION["NOME_PESSOA"] != "" ) {
      $this->caixaLogin .= "<span title='".$_SESSION["NOME_PESSOA"].". Último acesso em ".formataUltimoAcesso($_SESSION['ultimoAcesso'])."'>";
      $this->caixaLogin .= "<span id='iconeTorpedo'     style='cursor:pointer;' onClick='showTorpedos();'><img src='".$urlImagem."/torpedooffline.png' border='0' title='Sem torpedos novos'></span>";
      $this->caixaLogin .= "<span id='iconeTorpedoNovo' style='cursor:pointer;' onClick='showTorpedos();'><img src='".$urlImagem."/torpedo.png'  border='0' title='Torpedos novos!'></span>";
      if (strlen($_SESSION["NOME_PESSOA"])>TAMANHO_NOME) {
        $this->caixaLogin .=substr($_SESSION["NOME_PESSOA"],0,TAMANHO_NOME)."...";
      }
      else { $this->caixaLogin .=$_SESSION["NOME_PESSOA"]; }
      $this->caixaLogin .="</span>";
      
      if ($this->nivel->isNivelComunidade() && $this->userRole==ADMINISTRADOR_GERAL) {
			  $this->caixaLogin .= "<small>[<a href='./index.php?iniciarNavegacao=1'>Navegar na hierarquia</a>]</small>";
      }
    }
		else 	{
      $this->caixaLogin .= "<form name='form1' method='POST' action='./logon.php'>";
      $this->caixaLogin .= "Usu&aacute;rio:<input type='text' id='USER_PESSOA' name='USER_PESSOA' size='12' maxlength='50'	onKeyPress='entraSenha(event);'>";
      $this->caixaLogin .= "Senha: <input type='password' name='SENHA_PESSOA' size='12' maxlength='50' onKeyPress='entraSenha(event);'>";
      $this->caixaLogin .= "<input type='submit' value='&nbsp;OK&nbsp;'>";
      $this->caixaLogin .= "  <a href='./esqueci_senha/index.php' ><b>Esqueceu a Senha?</b></a>";
      $this->caixaLogin .= "</form>";
    }
  }
  /*
   * Monta o topo. 
   * usa os atributos "escolhaSubNivel", que eh o widget de escolher o subNivel,
   * e "caixaLogin", o widget de autenticacao na plataforma.   
   *       
   */
  function initLogotipos() {
    global $urlImagem;
    
    //Mostra a imagem própria da instancia sistemica, se houver. 
    //Se nao usa uma padrão
    if (!empty($this->instanciaNivel->codArquivoLogotipo)) {
      $codArquivoLogotipo=$this->instanciaNivel->codArquivoLogotipo;
    }
    else {
      $codArquivoLogotipo = LOGOTIPO_PADRAO; 
    }
    $arq = new ArquivoMultiNavi($codArquivoLogotipo);
    $this->logotipos.="<img src='".$urlImagem."/".PASTA_LOGOTIPOS.
                      "/".$arq->caminhoFisico."'  height='46px'>";
    //Logotipo do NAVi
    $this->logotipos.="<a href='http://navi.ea.ufrgs.br' target='_blank'><img src='".$urlImagem."/logo.gif' border='0' height='35px' width='75px'></a>";                           
    //nao utilizado
    //$this->logotipos.="<span class='navegacao'>".$this->imprimeNavegacao()."</span>"; 
  }
  /*
   *Para usuarios logados: Busca de Usuarios, Ferramentas de Gerência, Botão de Sair,
   *                       inscrição em Níveis   
   */                       
  function initWidgetsFinais() {
    global $urlImagem;
    //Buscar pessoas
    $this->widgetsFinais='';
    $this->widgetsFinais="<table cellspacing='0' cellpadding='0'><tr><td class='buscar'>";
    $this->widgetsFinais.="<form action= 'consultar.php' name='form_consulta' method='get' target='_blank'  onSubmit=\"if (document.form_consulta.BUSCA_PESSOA.value=='') { alert('Informe o nome da pessoa procurada!'); document.form_consulta.BUSCA_PESSOA.focus(); return false; } else { return true; }\">"; 
    $this->widgetsFinais.="Procurar Pessoa<br>";
    $this->widgetsFinais.="<input type='text'  name='BUSCA_PESSOA' id='BUSCA_PESSOA' size='12' title='Informe o nome ou o código da pessoa. Tecle Enter ou clique em ok para fazer a busca.' maxlength='50'>";
    $this->widgetsFinais.="<input type='submit'  name='ok' id='okBusca' size='3' value='ok' title='Clique para fazer a busca.' >";
    $this->widgetsFinais.="</form>";
    $this->widgetsFinais.="</td>";
    //Botão de Sair    
    $this->widgetsFinais.="<td class='botaoSair'>";
    $this->widgetsFinais.="<a href='./logoff.php'><img src='".$urlImagem."/sair.gif' title='Sair do NAVi' border='0' align='right'></a>";
    $this->widgetsFinais.="</td></tr></table>";    
  }    
  /* 
   * Mostra as comunidades
   */
  function initWidgetComunidades() {     
    global $urlImagem;
    
    $nivelCom = Nivel::getNivelComunidade(); //Nivel que implementa comunidade
    $pkCom = $nivelCom->getPK();  //pk da tabela que implementa comunidade
    $codNivel = $nivelCom->codNivel; //codNivel do nivel que implementa a comunidade
    //Se for administrador geral entao pega todas as comunidades
    //se nao, verifica em cada um dos papeis quais as comunidades que a pessoa esta cadastrada
    if ($this->direitosUsuario['adm']) {
      $comunidades = Nivel::getTodasComunidades();  
      $comunidadesArray = $comunidades->records;
    }
    else {
      if (!empty($_SESSION['professor'])) { $comunidadesProfessor = $_SESSION['professor']->getComunidades();    }
      if (!empty($_SESSION['aluno'])) { $comunidadesAluno = $_SESSION['aluno']->getComunidades(); }
      if (!empty($_SESSION['admNivel'])) { $comunidadesAdmNivel = $_SESSION['admNivel']->getComunidades(); }
      //array simples: utilizado para a exibição da DIV
      //echo 'ARRAY:'; note($comunidadesProfessor->records);
      if (empty($comunidadesProfessor->records)) { $comunidadesProfessor->records=array(); }
      if (empty($comunidadesAluno)) { $comunidadesAluno->records=array(); }
      if (empty($comunidadesAdmNivel)) { $comunidadesAdmNivel->records=array(); }

      $comunidadesArray = array_merge($comunidadesProfessor->records,$comunidadesAluno->records,$comunidadesAdmNivel->records);
    }
        
    //DIV DAS COMUNIDADES
    $this->widgetComunidades = "<div class='comunidades' title='Todos os n&iacute;veis (".$nivelCom->nome.") onde o usu&aacute;rio est&aacute; relacionado.'>";
    
    $this->widgetComunidades .="<div class='tituloComunidades'><b>Minhas Comunidades</b>";
    $this->widgetComunidades .="<img src='".$urlImagem."/aumenta.gif' onClick=\"el=document.getElementById('escolheComunidade'); largura=330; el.style.position='absolute'; el.style.width=largura;  if (ie) { el.style.left=screen.width-largura-1; } else { el.style.left=(screen.width-largura); }\" style='cursor:pointer;'>&nbsp;<img src='".$urlImagem."/diminui.gif' onClick=\"el=document.getElementById('escolheComunidade'); el.style.position='relative'; el.style.width=163; el.style.left=0;\"  style='cursor:pointer;'>";
    $this->widgetComunidades .="</div>";
    /* EXIBIÇÃO DAS COMUNIDADES EM UMA SEQUENCIA DE DIV´s
    if (!empty($comunidadesArray)) {
      while (list(,$linha) = each($comunidadesArray) ) {
        $this->widgetComunidades .= "<a href='".$_SERVER['PHP_SELF']."?&codNivel=".$codNivel."&codInstanciaNivel=".$linha->$pkCom."&userRole=".$linha->userRole."&iniciarNavegacao=1'>";
        $this->widgetComunidades .= "<div class='escolheComunidade'>".InstanciaNivel::getAbreviaturaOuNome($linha)."</div></a>";
        $acesso['direitosComunidade'][$codNivel][$linha->$pkCom]=1;
      }
    }
    */
    //EXIBIÇÃO DAS COMUNIDADES NAS QUAIS A PESSOA ESTÁ INSCRITA EM UM COMBO
    $this->widgetComunidades .= "<select name='escolheComunidade' id='escolheComunidade' ";
    if (!empty($comunidadesArray)) {
      //a funcao javascript recebera o codNivel de comunidade e fará o parse do codigo da instancia bem como do papel do usuario.
      //a variavel largura é a soma da largura da caixa de instancias com a coluna de comunidades, mais pixels de ajuste devido a espaço e bordas
      $this->widgetComunidades .= " onChange=\"carregaComunidade(this,".$codNivel.",'".$_SERVER['PHP_SELF']."');\"  >";       
      $this->widgetComunidades .= "<option value=0>Selecione Comunidade...</option>";
      while (list(,$linha) = each($comunidadesArray) ) {
        $this->widgetComunidades .= "<option value='".$linha->$pkCom."|".$linha->userRole."'>".InstanciaNivel::getAbreviaturaOuNome($linha)."</option>";
        $this->acesso['direitosComunidade'][$codNivel][$linha->$pkCom]['acesso']=1;
        $this->acesso['direitosComunidade'][$codNivel][$linha->$pkCom]['interage']=$linha->interage;
      }
    }
    else {
      $this->widgetComunidades .= ">"; //FECHA O SELECT 
      $this->widgetComunidades .= "<option value=0>Inscreva-se.</option>";        
    }
    $this->widgetComunidades.="</select>";
    //Solicitar inscrição em uma comunidade
    $this->widgetComunidades .="<div class='inscricao' onClick=\"changeIframeSrc('recurso','cadastro/inscricaoComunidade.php');\" border='0'  title='Clique aqui para inscrever-se em Comunidades'>";
    $this->widgetComunidades .="<img src='".$urlImagem."/inscricao.png' border='0'><b>Solicitar inscri&ccedil;&atilde;o</b></div>";
    $this->widgetComunidades .="</div>";
  }     
  /*
   * Mostra menu de recursos ativados para esta instancia sistemica
   * Utiliza $this->recursoFixo      
   */
  function initMenu() {
    global $urlImagem;
    
    $saltoMenu=20;
    //$separador="&nbsp;&nbsp;";
    $consulta = $this->instanciaNivel->getMenu();
    $consultaParticulares = $this->instanciaNivel->getMenuParticulares();
    $menuSemRecursive = $consulta->records;
    if (count($consultaParticulares->records)) {
      $menuSemRecursive = array_merge($menuSemRecursive,$consultaParticulares->records);
    }
    $this->menu = "<table class='menu' title='Menu de recursos disponíveis para este n&iacute;vel.' cellpadding='0' cellspacing='0'>";
    $this->menu.= "<tr><td class='menuAtivado'>";
    $numItens=count($menuSemRecursive);
    for($i=0;$i<$numItens;$i++) { 
      $itemMenu = &$menuSemRecursive[$i]; 
      $this->menu .="<span style='cursor:pointer;' onclick=\"changeIframeSrc('recurso','".$itemMenu->urlMenu."'); recursoAtivado(this);\">";
      if (!empty($itemMenu->imagem)) { 
        $this->menu .= "<img src='".$urlImagem."/".$itemMenu->imagem."' title='".$itemMenu->descricaoMenu."' border='no' hspace='2' vspace='1'>"; 
      }
      else {  $this->menu .= $itemMenu->nomeMenu; }
      $this->menu .="</span>";
      $numOrdem = $i+1;
      if (!($numOrdem % $saltoMenu)) { $this->menu .= "<br>"; }       
    }
    //coloca os recursos fixos dentro da barra de menu
    $this->menu .= "</td><td class='recursoFixo'>".$this->recursoFixo."</td></tr>"; 
    $this->menu .="</table>"; 
  }
  /*
   * Link de recurso (menu) que são fixos, isto é, nao sao configuraveis, 
   * aparecem sempre. Utilizado por initMenu
   */
  function initRecursoFixo() {
    global $urlImagem;
    
   $recurso=getMenuInicial($_SESSION["codInstanciaGlobal"]);
   
   $rsCoN = mysql_num_rows($recurso); 
   $recursos = mysql_fetch_array($recurso);
   $this->recursoFixo = "";
   
    //Correio é mostrado apenas para usuários logados, NAO para o publico
    if ($this->userRole!=PUBLICO) {
    if(empty($rsCoN) || !empty($recursos["correio"])){
      $this->recursoFixo .=  "<span style='cursor:pointer' onclick=\"changeIframeSrc('recurso','interacao/correio/index.php'); recursoAtivado(this);\">";
      $this->recursoFixo .=  "<img src='".$urlImagem."/correio.gif' title='Correio:\n\rComunicação via mensagens. Pode copiar as mensagens para seu correio internet.' border='0'></span>";
    }
      //Mostra indicadores do aluno de prof ou adm, nao mostra para aluno nem para publico
      if ($this->userRole!=ALUNO ) {
       if(empty($rsCoN) || !empty($recursos["indicadores"])){
        $this->recursoFixo .= "<span style='cursor:pointer' onclick=\"changeIframeSrc('recurso','indicadoresaluno/index.php'); recursoAtivado(this);\" >";
        $this->recursoFixo .= "<img src=".$urlImagem."/indicadores.gif title='Indicadores:\n\rIndicadores da utilização dos recursos da plataforma.' border='0'></span>";
       }
			}
      //Ferramentas de Gerência: o aluno pode ver o portfólio
     
      $this->recursoFixo.="<span class='ferramentasGerencia' style='cursor:pointer' onclick=\"changeIframeSrc('recurso','tools/index.php'); recursoAtivado(this);\"><img src='".$urlImagem."/ferramentasgerencia.gif' border='0' title='Ferramentas de Gerência:\n\rGerenciar recursos ativados no menu'></span>";      
       //if(Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage']))
      $this->recursoFixo.="<span class='ferramentasGerencia' style='cursor:pointer' onclick=\"changeIframeSrc('recurso','tools/recursos_fixos.php'); recursoAtivado(this);\"><img src='".$urlImagem."/configuracaoGeral.gif' border='0' title='Painel de Controle:\n\rPersonalização do funcionamento do NAVi'></span>";
      //Suporte técnico: abrir chamado e ter acompanhamento das solicitações 
     if(empty($rsCoN) || !empty($recursos["suporteTecnico"]))
      $this->recursoFixo.="<span class='suporteTecnico'  style='cursor:pointer' onclick=\"changeIframeSrc('recurso','suporte_tecnico/index.php'); recursoAtivado(this);\"><img src='".$urlImagem."/suportetecnico.gif' border='0' title='Suporte Técnico:\n\rAbrir chamado para a equipe técnica resolver dúvidas ou problemas'></span>";      
    }
  
  }
  /*
   * Rodape
   *    
   * O javascript é adicionado no fim da página   
   */
  function initRodape() {
    global $urlImagem;
    
    $this->rodape ="";
    $this->rodape.= "<script> alturaRecurso();";
    //Serve como 'alive' da pessoa e busca torpedos
    if (!empty($_SESSION['COD_PESSOA'])) { 
      $this->rodape.= "alive(); ";  
    }
    else {  //usuarios nao-logados
      $this->rodape.= " document.getElementById('USER_PESSOA').focus(); ";    
    }
    $this->rodape.=  "</script>";
    $this->rodape.=  "</body></html>";
  }
  /*
   *
   */
  function comboInstanciasSubNivel($subNivelSelecionado) {
    $param['name'] = 'codInstanciaNivel';    $param['id'] = 'instanciasSubNivel';
    $param['optionName'] = 'nome';    $param['optionValue'] = 'chave';
    if ( ($this->userRole==PUBLICO) || 
         ($this->userRole==ADMINISTRADOR_GERAL) ||
         ($this->userRole==ADM_NIVEL) ||  
         ( ($this->userRole==PROFESSOR || $this->userRole==ALUNO)  
              && $this->instanciaNivel->relacionaPessoas() )  
       ) {
      if (!empty($subNivelSelecionado)) {
        //old-style
        //$consulta = $subNivelSelecionado->getInstancias($this->instanciaNivel->codInstanciaNivel,$this->instanciaNivel->nivel->getPk());
        $consulta = $subNivelSelecionado->getInstancias($this->instanciaNivel);
      }
      if (!empty($consulta)) {
        $retorno = $consulta->combo($param);        
      }
      else {
        $param['static']=1;
        $retorno = RDCLQuery::combo($param);       
      }
    }
    else {
      //nao mostra o combo, está vazio!
      //$param['static']=1;
      //$retorno = RDCLQuery::combo($param); 
    }
    //marca o atributo isEmpty do nivel atual dinamicamente, pois nao há instancias no subnivel,
    //ou entao o usuario nao tem direitos
    if (!count($consulta->records)) {    $this->nivel->isEmpty=1;   } else {$this->nivel->isEmpty=0; }
    
    //retorna o combo desta consulta
    return $retorno;
  }
  /*
   * Versao simplificada de imprimeNavegacao, mostrando a abreviatura sempre q possivel
   */   
  function imprimeNavegacaoSimples() {
    //professor ou aluno
    if ($this->userRole==PROFESSOR || $this->userRole==ALUNO) {
      $pai = $this->instanciaNivel->getPai(); 
      if (!empty($pai)) { $ret=$pai->nome.SEPARADOR;  } else { $ret=''; }
      $ret.=$this->instanciaNivel->nome;
      return $ret;
    }
    //administrador, administrador de nivel ou publico    
    if (empty($this->navegacao)) { return ""; }
    $ret="";
    $i=0;
    while(!empty($this->navegacao->pilha[$i])) {
      $nav   = $this->navegacao->pilha[$i];
      if ($nav["nivel"]->mostraAbreviaturaNavegacao) {
        $ret.=$nav["instancia"]->getAbreviaturaOuNome();
      }
      else {
        $ret.=$nav["instancia"]->nome;      
      }
      $ret.=" | ";
     $i++;
    }
    $ret = rtrim($ret,"| ");
    
    return $ret;
  }
  /*
   *  imprime a pilha completa da navegacao
   */
  function imprimeNavegacao() {
    
    /* Imprime em formato de tabela, 
     * em principio ficou muita coisa para ser mostrado *
    $salto = 3;
    if (empty($_SESSION["navegacao"])) { return ""; }
    $ret="<table class='navegacao' cellspacing='0' cellpadding='1'><tr>";
    $i=0;
    while(!empty($_SESSION["navegacao"]->pilha[$i])) {
      $nav   = $_SESSION["navegacao"]->pilha[$i];
      $ret.="<td><div class='nivelNavegacao'>".$nav["nivel"]->nome."</div><div class='instanciaNivelNavegacao'>".$nav["instancia"]->nome."</div></td>";
      if (($i % $salto)==0 && $i>0) { $ret.="</tr><tr>"; }
      $i++;
    }
    if (($i % $salto)!=0) { $ret.="</tr>"; }
    $ret.="</table>";
    */
    /*
     * Imprime em formato de combo
     */
    // POR ENQUANTO DESABILITADO, em teste
    /*
    $ret="";
    $ret.="<select name='nivelNavegado'>";
    $i=0;
    while(!empty($_SESSION["navegacao"]->pilha[$i])) {
      $nav   = $_SESSION["navegacao"]->pilha[$i];
      //colocar depois em value o numero de pops que devem ser feitos
      $ret.="<option value=''>".$nav["nivel"]->nome."/".$nav["instancia"]->nome."</option>";
      $i++;
    }
    $ret.="</select>";
    */
    return $ret;
  }
  /*
   * Pega o css
   */
  function getCss() {
    
    if (!empty($this->instanciaNivel->css)) {  //Usa primeiro a classe definida na instancia, se houver
      $css = $this->instanciaNivel->css;
    }
    else if (!empty($this->nivel->css)) {  //Depois, Usa a classe definida no nivel
      $css = $this->nivel->css;
    }
    //Verifica se existe o arquivo css
    if (empty($css) || !file_exists("./css/".strtolower($css) ) ) {
      $css = CSS_PADRAO;
    }
    return $css;
  }
  /*
   * Iframe que exibe o recurso requerido pelo usuario
   */
  function initEspacoRecursos() {
    if (!empty($this->recursoPadrao)) { 
      $page = $this->recursoPadrao; 
    } 
    else { //se nao for setado o padrão é noticias
      $page="./noticias/noticias.php"; 
      //$page = "./inscricao/index.php";
    }
    $this->espacoRecursos = "";
    //$this->espacoRecursos.= "<div class='espacoRecursos'>";    
    $this->espacoRecursos.= "<iframe id='recurso' src='".$page."' frameborder='0' border='0' allowtransparency='true'></iframe>";
    //$this->espacoRecursos.= "</div>";
  }

  /*
   * Seta administracao de instancias como recurso padrao
   */
  function setAdministraInstancia() {
    $this->recursoPadrao = './tools/instanciasNiveis.php';
    $this->initEspacoRecursos();
  }
  /*
   * devolve a matriz de instancias para as quais este usuario tem acesso,
   * construida na exibição das arvores de niveis sistemicos    
   */
  function getAcesso() {
    return $this->acesso;
  }
  /*
   * Formata as subpartes e joga a pagina para o navegador 
   */
  function imprime() {
    
    echo $this->cabecalho;
    //Div geral
    //echo "<div class='geral' id='idGeral'>";
    //tabela que contem os widgets acima do menu
    echo "<table class='topo' cellpadding='1' cellspacing='0'><tr>";
    echo "<td class='colunaImagens'>".$this->logotipos."</td>";
    echo "<td class='colunaSubNivelCaixaLogin'>".
         "<span class='escolhaSubNivel'>".$this->escolhaSubNivel."</span>".
         "<span class='caixaLogin'>".$this->caixaLogin."</span></td>";  
    echo "<td class='caixaInstancias'>".$this->caixaInstancias."</td>";
    echo "<td class='widgetsFinais'>".$this->widgetsFinais.
         $this->widgetComunidades."</td>";    
    echo "</tr></table>";
    echo $this->menu;           //Menu de recursos ativados e tambem os fixos
    echo $this->espacoRecursos; //espaço de ativação do recurso
    
    //echo "</div>";
    
    echo $this->caixaTorpedos;
    echo $this->rodape;
    //guarda instancia global em uma div, para impedir usuario
    //de abrir duas instancias em uma mesma sessao da plataforma
    echo '<div style="display:none" id="instanciaGlobal">'.(int)$_SESSION['codInstanciaGlobal'].'</div>';
  }
  
 
}
?>
