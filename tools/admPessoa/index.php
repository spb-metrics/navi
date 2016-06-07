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

ini_set("display_errors",1);
error_reporting(E_ALL ^ E_NOTICE);

include_once("../../config.php");

include_once($caminhoBiblioteca."/defaultpage.inc.php");
include_once($caminhoBiblioteca."/cadastro.inc.php");
include_once($caminhoBiblioteca."/curso.inc.php");
include_once($caminhoBiblioteca."/administradornivel.inc.php");
include_once($caminhoBiblioteca."/administraPessoa.inc.php");
include_once($caminhoBiblioteca."/pessoa.inc.php");
include_once($caminhoBiblioteca."/professor.inc.php");
include_once($caminhoBiblioteca."/aluno.inc.php");
include_once($caminhoBiblioteca."/linkimagem.inc.php");
include_once($caminhoBiblioteca."/controlaNavegacao.inc.php");
include_once($caminhoBiblioteca."/nivel.inc.php");
include_once($caminhoBiblioteca."/instancianivel.inc.php");

session_name(SESSION_NAME); session_start(); security();

$nivelAtual=getNivelAtual();
$instanciaGlobal = new InstanciaGlobal($_SESSION['codInstanciaGlobal']);
$codInstanciaNivelAtual=getCodInstanciaNivelAtual();


//verificacao basica de seguranca pra administracao
if (!Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage']) ) { die;}  

//possiveis filtros de busca de pessoas
$exibicaoFiltro['buscaPessoasDesvinculadas'] = 'sem nenhuma inscri&ccedil;&atilde;o';
$exibicaoFiltro['formNovaPessoa'] = 'novo usu&aacute;rio';
$exibicaoFiltro['buscarPessoa'] = 'procura por '.$_REQUEST['pessoa'];
$exibicaoFiltro['meusAlunos'] = 'Meus alunos';

$direitosUsuario = array('aluno'=>0,'professor'=>0,'admNivel'=>0,'adm'=>0,'publico'=>0);

$label['AG']='Administrador Geral';
$label['ANB']='Administrador Nivel B&aacute;sico';
$label['AN']='Administrador N&iacute;vel';
$label['NA']='N&atilde;o Administrador';




switch ($_REQUEST["acao"]) {  


  case ""://layout	da página
   
    echo "<html>".
            "<head>".
            "<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>".
            "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
            //"<link rel=\"stylesheet\" href=\"".$url."/cursos.css\" type=\"text/css\">".
            "<link rel=\"stylesheet\" href=\"".$url."/css/comunidade.css\" type=\"text/css\">".
            "<link rel=\"stylesheet\" href=\"".$url."/css/cssnavi.css\" type=\"text/css\">".
            "<link rel=\"stylesheet\" href=\"".$url."/css/configuracao.css\" type=\"text/css\">".
            "<link rel=\"stylesheet\" href=\"".$url."/css/padraogeral.css\" type=\"text/css\">".
            "<script language=\"JavaScript\" src=\"".$url."/js/funcoes.js\"></script>".
            "<script language=\"JavaScript\" src=\"".$url."/js/lista.js\"></script>".
            "<script language=\"JavaScript\" src=\"".$url."/js/admPessoa.js\"></script>".
            "</head>".
            "<body class='bodybg'>";
    echo "<table  valign=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" >".
    "<tr><td align=\"left\" valign=\"top\">".
    //"<table  align=\"left\" cellspacing=\"0\" style=\"background-color:#000000\" ><tr><td>".	  
    "<table  valign=\"top\" border=\"1\" cellspacing=\"0\" cellpadding=\"6\" >";
    echo '<tr><td style=" background-color: #C5D3F8;" colspan="6">';
    echo '<div style="display: block;"><big><b>Selecione as pessoas para incluir. Use uma das formas abaixo</b></big></div>';            
    echo '</td></tr>';
    
    echo "<tr><td style=\"background-color:#FFFFFF\">";
    //Se for administrador geral ou de nivel, mostra navegacao
    switch ($_SESSION['userRole']) {

      case PROFESSOR:
        echo "<form name=\"form1\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";  			
        //$instancias = $_SESSION['professor']->getNiveis('',$nivelAtual);
          
        echo "<select  name=\"codInstanciaNivel\" id=\"codInstanciaNivel\" onChange=\"selecionaCodNivelCodInstanciaNivel(this,'".$_SERVER['PHP_SELF']."')\">";
        echo "<option value=\"meusAlunos\" >Selecione:</option>";
        echo "<option value=\"meusAlunos\" >--Somente Meus Alunos--</option>";
        //Busca todas as turmas em que o professor atua, em todos os niveis
        $niveisFormais = Nivel::getNiveisRelacionamentoFormal();
        
        if (!empty($niveisFormais->records)) {
          foreach($niveisFormais->records as $n) {
            $obj = $_SESSION['professor']->getNiveis('',$n,0); //somente turmas no presente
            if (!empty($obj->records)) {
              $nivel = new Nivel();
  			      foreach($n as $campo => $valor) { $nivel->$campo = $valor; }
              $nivel->records = ''; 
              $campoPK = $nivel->getPK();
              $campoNome = $nivel->nomeFisicoCampoNome;
              foreach ($obj->records as $codInstancias) {
                if ($_REQUEST['codInstanciaNivel']==$codInstancias->$campoPK && $codInstancias->codNivel) {   $selected=' selected '; $classeListaPessoas=''; }
                else { $selected=''; }
                echo '<option value="'.$codInstancias->$campoPK.'|'.$codInstancias->codNivel.'" '.$selected.'>'.$codInstancias->$campoNome.'</option>';
              }
            }
          }
        }

        echo "</select>";
        echo "</form>";                		  
        break;
      
      case ADMINISTRADOR_GERAL:
        if (!empty($_REQUEST['iniciarNavegacao']) ) { unset( $_SESSION['navegacaoComboAdmPessoa']);  }
          $direitosUsuario['adm']=1;
      
        if ( !empty($_REQUEST['codNivel']) && !empty($_REQUEST['codInstanciaNivel']) ) {      			      			             
          $nivelNavegacao = new Nivel($_REQUEST['codNivel']);
          $instanciaNivel = new InstanciaNivel($nivelNavegacao,$_REQUEST['codInstanciaNivel']);
        }
      
        controlaNavegacao($nivelNavegacao,$instanciaNivel,$_SESSION['userRole'],$_SESSION['navegacaoComboAdmPessoa']);
      
        $param['comboAdmPessoas']=1;
        $instancias = new DefaultPage($nivelNavegacao,$instanciaNivel,$direitosUsuario,$_SESSION['userRole'],$_SESSION['navegacaoComboAdmPessoa'],$param,$_SESSION['mostraPassado'],$_SESSION['interage']);
           
        print($instancias->escolhaSubNivel);
        
        break;
      
      case ADM_NIVEL:
      
        if (!empty($_REQUEST['iniciarNavegacao']) ) { unset( $_SESSION['navegacaoComboAdmPessoa']);  }
      
        $direitosUsuario['admNivel']=1;
        if (empty($_REQUEST['codNivel']) || empty($_REQUEST['codInstanciaNivel']) ) {
          $nivelNavegacao=$_SESSION['admNivel']->getNivelInicial();
          $instanciaNivel=$_SESSION['admNivel']->getInstanciaNivelInicial();
        }
        else{
          $nivelNavegacao = new Nivel($_REQUEST['codNivel']);
          $instanciaNivel = new InstanciaNivel($nivelNavegacao,$_REQUEST['codInstanciaNivel']);
        }
    
        if(empty ($_SESSION['navegacaoComboAdmPessoa'])){
         $_SESSION['navegacaoComboAdmPessoa']= new Navegacao($nivelNavegacao,$instanciaNivel);
        }
                  
        controlaNavegacao($nivelNavegacao,$instanciaNivel,$_SESSION['userRole'],$_SESSION['navegacaoComboAdmPessoa']);
    
        $param['comboAdmPessoas']=1;
        $instancias = new DefaultPage($nivelNavegacao,$instanciaNivel,$direitosUsuario,$_SESSION['userRole'],$_SESSION['navegacaoComboAdmPessoa'],$param,$_SESSION['mostraPassado'],$_SESSION['interage']);
        
        print($instancias->escolhaSubNivel);          
        
        break;      
    }
    
    //coluna de comunidades
    echo "<td style=\"background-color:#FFFFFF\">";

    //seleciona as comunidades as quais o usuario esta inscrito
    if ($_SESSION['userRole'] == ADMINISTRADOR_GERAL) {
      $comunidades = Nivel::getTodasComunidades();   //adm geral ve todas as comunidades
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

    $nivelCom = Nivel::getNivelComunidade(); //Nivel que implementa comunidade
    $pkCom = $nivelCom->getPK();  //pk da tabela que implementa comunidade
    $codNivel = $nivelCom->codNivel; //codNivel do nivel que implementa a comunidade
    $nomeComunidade=$nivelCom->nomeFisicoCampoNome;
    
    
    if (!empty($comunidadesArray)) {
      //echo"<td style=\"background-color:#FFFFFF\">|</td>";
      echo "<form name=\"selectComunidade\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";      
      echo "<select  name=\"codInstanciaNivel\" class='tituloComunidades' onChange=\"selecionaCodNivelCodInstanciaNivel(this,'".$_SERVER['PHP_SELF']."')\">";   
      echo "<option value=\"meusAlunos\" >Selecione a comunidade:</option>";
      foreach($comunidadesArray as $comunidadeAdm){
        //mantem a comunidade, caso selecionada           
        if ($_REQUEST['codInstanciaNivel']==$comunidadeAdm->$pkCom && $codNivel==$_REQUEST['codNivel']) {   $selected=' selected '; $classeListaPessoas='pessoasComunidade'; }
        else { $selected=''; }
        echo '<option value="'.$comunidadeAdm->$pkCom."|".$codNivel.'" '.$selected.'><b>'.$comunidadeAdm->$nomeComunidade.'</b></option>';            
      } 
      echo '</select>';
      echo '</form>';
    }
    else {
      echo 'Sem Comunidades para exibir';
    }
    echo "</td>";
    
    //echo "<td style=\"background-color:#FFFFFF\">|</td>";
    echo "<td style=\"background-color:#FFFFFF\"><a href='".$_SERVER['PHP_SELF']."?filtro=buscaPessoasDesvinculadas'><b>Mostrar Pessoas sem nenhuma Inscri&ccedil;&atilde;o</b></a></td>".
         "<td style=\"background-color:#FFFFFF\">";
    if (Pessoa::isAdm($_SESSION['userRole'])) {
      echo "<a href='".$_SERVER['PHP_SELF']."?filtro=formNovaPessoa'><b>Criar Novo usu&aacute;rio</b></a>";
    }
    else {
      echo '&nbsp;';
    }
    echo "</td>";
    
    echo "<td style=\"background-color:#FFFFFF\">".
    "<form name=\"buscaPessoa\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."?filtro=buscarPessoa\"> ".
    "<div>Procurar Pessoa</div><input type=\"text\" size=\"20\" maxlength='30' name=\"pessoa\"> ".
    "<input type=\"submit\" value=\"Ok\">".
    "</form>".
    "</td>".
    "</tr>";
    /**echo 	"<tr>".
    "<td>@ Trocar papel de participante da instancia Atual<link></td>".
    "</tr>".*/
    echo  "</table>".
    //"</td></tr></table>".
    "</td>".
    "</tr>".
    "<tr>".
    "<td align=\"center\" valign=\"center\">".
    "<table valign=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style='width:100% '>".	 
    "<form name=\"form\" method=\"post\" action=\"\">".
    "<tr>";
    //ajusta variavel de filtro
    if ($_REQUEST["codInstanciaNivel"]=="meusAlunos") {       $filtro="meusAlunos";     }
    else {  $filtro=$_REQUEST["filtro"];     }
    
    //Ajusta objetos nivel/instancia para exibição
    if (empty($filtro) && !empty($_REQUEST['codNivel']) && !empty($_REQUEST['codInstanciaNivel'])) {
      $nivelFiltro = New Nivel($_REQUEST['codNivel']);
      $instanciaNivel = New InstanciaNivel($nivelFiltro,$_REQUEST['codInstanciaNivel']);
    }
    else if (!empty($nivelNavegacao->codNivel) && !empty($instanciaNivel->codInstanciaNivel) ) {
      $nivelFiltro = $nivelNavegacao;
      $instanciaNivel = New InstanciaNivel($nivelFiltro,$instanciaNivel->codInstanciaNivel);
    }
    //descricao condizente com a exibição das pessoas solcitadas 
    if (empty($filtro) && !empty($nivelFiltro)) {
      $descricaoFiltroAtual = ' Integrantes de '.$nivelFiltro->nome.': '.$instanciaNivel->getAbreviaturaOuNome();
    }
    else {
      $descricaoFiltroAtual = 'Pessoas: '.$exibicaoFiltro[$filtro];    
    }
    echo "<td width='65%' align=\"center\"><b>".$descricaoFiltroAtual."</b></td>";
    
    echo "<td width='15%' align=\"center\"><b>Autoridades e Pap&eacute;is</b><br>";
    if ($_SESSION['userRole']==ADMINISTRADOR_GERAL) {
      echo "<a href=\"#\" onClick=\"window.open('".$_SERVER['PHP_SELF']."?acao=formAdicionaPapel','Wlocal','top=406,left=85,width=300px,height=300px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes')\">Incluir</a> | ";
      echo "<a href=\"#\" onClick=\"if (document.form.papel.selectedIndex>=2) { window.open('".$_SERVER['PHP_SELF']."?acao=formAdicionaPapel&auth='+document.form.papel.options[document.form.papel.selectedIndex].value,'Wlocal','top=406,left=85,width=300px,height=300px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes'); } else if (document.form.papel.selectedIndex>=0) { alert('Administrador Geral e de Nivel nao permitem alteracao!'); } else { alert('Selecione um papel para ser alterado!'); } \">Alterar</a> | ";
      echo "<a href=\"#\" onClick=\"if (document.form.papel.selectedIndex>=2) { if (confirm('Confirma exclusao do papel?')) { window.open('".$_SERVER['PHP_SELF']."?acao=excluiPapel&auth='+document.form.papel.options[document.form.papel.selectedIndex].value,'Wlocal','top=406,left=85,width=300px,height=300px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes'); } } else { alert('Selecione um papel para excluir!'); } \">Excluir</a>";
    }
    
    echo "</td><td width='5%' align=\"center\">&nbsp;</td>";
    
    $instanciaNivelAtual = new InstanciaNivel($nivelAtual,$codInstanciaNivelAtual);    
    $descricaoNivel = $nivelAtual->nome.': '.$instanciaNivelAtual->getAbreviaturaOuNome(); 
    if (!$nivelAtual->relacionaPessoas()) {   $descricaoNivel.= '<div style="color:darkred; font-weight:bold;">N&Atilde;O RELACIONA PESSOAS</div>';   }    
    echo "<td width='15%' align=\"center\"><b>".$descricaoNivel.'</b></td></tr>';
    echo  "<tr><td valign=\"top\" align=\"center\">";
    
    
    switch ($filtro) {
      /**
      *          padrão combo que mostra as instancias dos professores ou admgeral ou admnivel
      */					 
      case "":          
        //if (!empty($_REQUEST['codInstanciaNivel']) && !empty($_REQUEST['codNivel'])) {
        if (!empty($instanciaNivel->codInstanciaNivel) && !empty($nivelFiltro->codNivel)) {
          //lista as pessoas conforme nivel e instancia passados;
          $rsConP = listaTodosIntegrantes(new Professor(),'','','','',$instanciaNivel->codInstanciaNivel,$nivelFiltro);//todos os integrantes do nivel escolhido e subniveis
          $rsConA = listaTodosIntegrantes(new Aluno(),'','','','',$instanciaNivel->codInstanciaNivel,$nivelFiltro);//todos os integrantes do nivel escolhido e subniveis

          /**
           neste select vai ser listado as pessoas dos niveis para 
           serem colocados na instancia atual 
          */

          //echo        "<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='18'  style=\"width: 400px; \" onclick=\"document.form.retirarDaInstancia.disabled=true;document.form.inserirNaInstancia.disabled=false;document.form.excluirRegistro.disabled=false;\">";
          echo        "<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='20'  style=\"width: 400px; \" class='".$classeListaPessoas."' onclick=''>";
          while($pessoasInativas= mysql_fetch_array($rsConP))
          echo					   "<option value=\"" . $pessoasInativas["COD_PESSOA"] . "\">".$pessoasInativas["NOME_PESSOA"]."</option>";

          while($pessoasInativas2= mysql_fetch_array($rsConA))
          echo					   "<option value=\"" . $pessoasInativas2["COD_PESSOA"] . "\">".$pessoasInativas2["NOME_PESSOA"]."</option>";

          echo        "</select>";

          $acao="A_incluiPessoaNivel";
        }
        break;
  					 
      /** 
       *          busca todas as pessoas que estão na tabela pessoa 
       *          mas que não estão vinculadas a instancia que tenha relacionamento aluno-professsor 
       */                      
      case "buscaPessoasDesvinculadas":
        
        $buscaPessoasDesvinculadas=Pessoa::buscaPessoasDesvinculadas();

        //echo        "<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='18'  style=\"width: 400px; \" onclick=\"document.form.retirarDaInstancia.disabled=true;document.form.inserirNaInstancia.disabled=false;document.form.excluirRegistro.disabled=false;\">";
        echo        "<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='20'  style=\"width: 400px; \" onclick=''>";
        foreach($buscaPessoasDesvinculadas->records as $pessoasDesvinculadas ){
          echo					   "<option value=\"" . $pessoasDesvinculadas->COD_PESSOA . "\">".$pessoasDesvinculadas->NOME_PESSOA."</option>";
        }
        echo        "</select>";
        $acao="A_incluiPessoaNivel";
        break;
  					
      //form para preencimento do cadastro da nova pessoa
      case "formNovaPessoa":
        if (file_exists('cadastrocustomizado.php')) {
          include('cadastrocustomizado.php');
        }
        else {
          echo        "Nome da Pessoa:  <br><input type=\"text\"      style=\"width: 200px; \" name=\"nomePessoa\"><br>";
          echo        "Usu&aacute;rio:         <br><input type=\"text\"      style=\"width: 200px; \" name=\"usuarioPessoa\"><br>"; 		
          echo        "Senha:           <br><input type=\"password\"  style=\"width: 200px; \" name=\"senhaPessoa\"><br>";
          echo        "Confirme senha:  <br><input type=\"password\"  style=\"width: 200px; \" name=\"senhaPessoa2\"><br>";
          echo        "Email:           <br><input type=\"text\"      style=\"width: 200px; \" name=\"emailPessoa\">";		
        }
        //action padrao
        $acao="A_criaNovaPessoa";
        break;
  					
      /**todos os alunos que estão vinculados a qq instancia que tem relacionamento com o professor, ex:turma, comunidade*/
      case "meusAlunos":
  					
        $AlunosProf= new Professor($_SESSION["COD_PROF"]);
        $objAlunos=$AlunosProf->meusAlunos($nivelAtual,$instanciaGlobal->codInstanciaNivel,$_SESSION['mostraPassado']);//meus alunos que não estão na instancia atual
        //echo"<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='18'  style=\"width: 400px; \" onclick=\"document.form.retirarDaInstancia.disabled=true;document.form.inserirNaInstancia.disabled=false;document.form.excluirRegistro.disabled=false;\">";
        echo"<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='20'  style=\"width: 400px; \" onclick=''>";
        foreach($objAlunos->records as $alunos) {
          echo "<option value=\"".$alunos->COD_PESSOA."\">".$alunos->NOME_PESSOA."</option>";
        }
        echo"</select>"; 

        $acao="A_incluiPessoaNivel";	
        break;
  					
      /**
       *           busca uma determinada pessoa ou lista de pessoas 
       */					
  		case "buscarPessoa":
  				
        $pessoa= new Pessoa();
        $buscaPessoa=$pessoa->buscaPessoa($_REQUEST["pessoa"]);
        
        //echo  			"<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='18'  style=\"width: 400px; \" onclick=\"document.form.retirarDaInstancia.disabled=true;document.form.inserirNaInstancia.disabled=false;document.form.excluirRegistro.disabled=false;\">";
        echo  			"<select multiple id=\"pessoaNivelEscolhido[]\" name=\"pessoaNivelEscolhido[]\" size='20'  style=\"width: 400px; \" onclick=''>";
        if($buscaPessoa===1){// reutilizando função já existente , por isto tem o if;
          echo            "<option value=\"" .$pessoa->COD_PESSOA. "\">".$pessoa->NOME_PESSOA."</option>";
        }
        else {
          foreach($buscaPessoa->records as $p) {
            echo            "<option value=\"" . $p->COD_PESSOA . "\">".$p->NOME_PESSOA."</option>";		
          }
        }			
        echo        "</select>";
        $acao="A_incluiPessoaNivel";
        break;
              
      /**
       *          trocar o papel de uma pessoa da instacia sem precisar retirar ela da turma
       */					 
      case"trocaPapel":
        $trocaLayout=1;//deixar por ultimo,não sei se vai ser preciso
        break;
    }
    echo		'</td><td align="center" valign="top">';

    /**lista as autoridades/papeis disponiveis na plataforma.*/
    $autoridades=listaPapel($_SESSION['codInstanciaGlobal']);
    if (Pessoa::isAdm($_SESSION['userRole']) ) { $isAdm='1'; } else { $isAdm='0'; }

    echo		'<SELECT size="18" name="papel" style="width: 400px;" onChange="ajustaInserirPessoa('.$isAdm.');" onClick="ajustaInserirPessoa('.$isAdm.');">';
    //$label= contem  a descrição do papel
    //$siglaPapel= contem a abreviatura do papel
    //$atributo =contem o cod_tipo_aluno ou cod_tipo_professor (cod do sub papel)  e atributos de visi  
    //$item atributo é a descrição dp subpapel
    
    foreach($autoridades as $siglaPapel=>$papeis) {
      if(($_SESSION['userRole']==PROFESSOR && $siglaPapel!='AN' &&  $siglaPapel!='AG') || ($_SESSION['userRole']==ADMINISTRADOR_GERAL) || ($_SESSION['userRole']==ADM_NIVEL && $siglaPapel!='AN' &&  $siglaPapel!='AG')){
        echo	"<OPTGROUP label=\"".$label[$siglaPapel]."\">";
        foreach($papeis as $codTipoPapel=>$p) {          
          //so permite ao professor incluir pessoas nos papeis permitidos
          if ($p['visivel']) { $visibilidade=' vis&iacute;vel'; } elseif (isset($p['visivel'])) { $visibilidade=' invis&iacute;vel'; }
          if ($p['interage']) { $interacao=' interage'; } elseif (isset($p['interage'])) { $interacao=' n&atilde;o interage'; }
          if ($p['professorPodeGerenciar']) { $professorPodeGerenciar='adm b&aacute;sico pode gerenciar';  } 
          else { $professorPodeGerenciar=' adm geral/n&iacute;vel gerencia'; }              

          if ($siglaPapel!='AN' &&  $siglaPapel!='AG') { $descricaoAtributos = ' ('.$visibilidade.','.$interacao.','.$professorPodeGerenciar.')'; } else { $descricaoAtributos = ''; }
          echo	'<OPTION   value="'.$siglaPapel.'-'.$codTipoPapel.'-'.$p['descricao'].'-'.$p['visivel'].'-'.$p['interage'].'-'.$p['professorPodeGerenciar'].'">'.$p['descricao'].$descricaoAtributos.'</OPTION>';//value=<papel>-<codSubpapel>
        }
        echo "</OPTGROUP>";	
      }
    }

    echo		"</SELECT>";
    echo		"</td>";
    echo		"<td align=\"center\">";
    //			 aqui estão os submits do form (as ações), o controle do          form
    echo 		 "<input type=\"button\"  name=\"inserirNaInstancia\"   style=\"width: 120px; \" value=\">>  Inserir >>\" onclick=\"document.form.action='".$_SERVER['PHP_SELF']."?acao=".$acao."';validaInsereInstancia('".$acao."');\" class='okButton' ><br><br>";
    echo 		 "<input type=\"button\"  name=\"retirarDaInstancia\"  style=\"width: 120px; \" value=\"<<  Retirar <<\" onclick=\"document.form.action='".$_SERVER['PHP_SELF']."?acao=A_excluiPessoaNivel';validaRetiraInstancia();\" class='okButton' ><br><br>";
    if (Pessoa::isAdm($_SESSION['userRole'])) {
      echo 		 "<input type=\"button\"  name=\"excluirRegistro\"     style=\"width: 120px; \" value=\"X Excluir Pessoa\"  onclick=\"document.form.action='".$_SERVER['PHP_SELF']."?acao=A_excluiCadastroPessoa';validaExcluiRegistro();\" class='cancelButton'>";
    }
    
    /**mostra as pessoas que estão cadastradas neste nível*/
    echo		'</td><td align="center" valign="top">';          
    echo			"<select size='18'  multiple id=\"pessoaNivelAtual[]\" name=\"pessoaNivelAtual[]\" style=\"width: 300px; \" onClick='ajustaRetirarPessoa(".$isAdm.", this);' onChange='ajustaRetirarPessoa(".$isAdm.", this);'>";
    if ($_SESSION['userRole']==ADMINISTRADOR_GERAL) {
      $AdmGeral=AdministradorNivel::listaAdmGeral();
      echo        "<OPTGROUP label=\"Administrador Geral\">";
      while($linha = mysql_fetch_array($AdmGeral)) {
        echo					 "<option  value=\"" . $linha["COD_PESSOA"] . "-AG-".$linha["COD_PESSOA"]."\">".$linha["NOME_PESSOA"]."</option>";
      }
      echo        "</OPTGROUP>";
      $AdmNivel=AdministradorNivel::listaAdmNivel($_SESSION['codInstanciaGlobal']);
      
      echo        "<OPTGROUP label=\"Administrador N&iacute;vel\">";
      while($linha = mysql_fetch_array($AdmNivel)) {
        echo            "<option  value=\"" . $linha["COD_ADM"] . "-AN-".$linha["COD_PESSOA"]."\">".$linha["NOME_PESSOA"]."</option>";
      }
      echo        "</OPTGROUP>";
    }

    //LISTA DE PROFESSORES
    $professorAtual=listaProfessores($membrosAtivos,0,0,0,1);//tentar depois fazer uma maneira de listar dinamicamente pelo banco de dados de acordo com cada papel da plataforma
    $pkProf =   $nivelAtual->nomeFisicoPKRelacionamentoProfessores;       
    echo        '<OPTGROUP label="Administrador N&iacute;vel B&aacute;sico">';
    while($l = mysql_fetch_array($professorAtual)) {
      echo            '<option  value="' . $l[$pkProf ] . '-ANB-'.$l['COD_PESSOA'].'-'.$l['professorPodeGerenciar'].'" title="'.$l['descTipoProfessor'].'" >'.$l['NOME_PESSOA'].' ('.$l['descTipoProfessor'].')</option>';
    }
    echo        "</OPTGROUP>";
    //LISTA DE ALUNOS
    $alunoAtual=listaAlunos('',$nivelAtual->nivelComunidade,0,0,0,1);	       
    echo        '<OPTGROUP label="N&atilde;o Administrador">';
    $pkAluno = $nivelAtual->nomeFisicoPKRelacionamentoAlunos;        
    while ($l = mysql_fetch_array($alunoAtual) ) {
      echo            '<option value="' . $l[$pkAluno ] . '-NA-'.$l['COD_PESSOA'].'-'.$l['professorPodeGerenciar'].'" title="'.$l['descTipoAluno'].'"  >'.$l['NOME_PESSOA'].' ('.$l['descTipoAluno'].')</option>';
    }
    echo        '</OPTGROUP>';
    echo			'</select>';
    
    echo		'</td>';

    echo     "</tr>".
           "</form>";
    echo  "</table>".
      "</td>".
      "</tr>".
      "</table>".
     "</body>".
    "</html>";

    break;
  /**
   *  retira a pessoa da base de dados
   */   
  case "A_excluiCadastroPessoa":// 
    if (!Pessoa::isAdm($_SESSION['userRole'])) { die; }  //somente adm podem deletar pessoas

    foreach($_REQUEST['pessoaNivelEscolhido'] as $codPessoa){  
      $pessoa= new Pessoa();
      $pessoa->COD_PESSOA=$codPessoa;	
      $instanciaNivel= $pessoa->getInstanciaNivelInicial($codPessoa);
      $pessoa->deleteInstanciaNivelInicial($codPessoa,$nivelAtual,$instanciaNivel);
      $pessoa->deleta();
    }
    echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
    break;
  /**
   *  cria nova pessoa na base de dados 
   */      
  case "A_criaNovaPessoa" :  
      if (!Pessoa::isAdm($_SESSION['userRole'])) { die; }  //somente adm podem criar pessoas
  
      if (file_exists('cadastrocustomizado_acao.php')) {
        include('cadastrocustomizado_acao.php');
      }
      else {
        $pessoaNivelEscolhido[] = cadastroBasico($_REQUEST['nomePessoa'],$_REQUEST['usuarioPessoa'],$_REQUEST['senhaPessoa'],$_REQUEST['emailPessoa'],1);  
        if (!$pessoaNivelEscolhido[0]) {   
          echo '<script>alert("Erro na inclusao de usuario. Use um nome de usuario que ainda nao tenha sido utilizado");';
          echo 'window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
        } 
        $titulo='Cadastro na plataforma NAVi';
        $mensagem='Seu cadastro foi efetuado com sucesso!\n Para acessar a plataforma '.$url.' e utilize login e senha abaixo:\n';
        sendMail($_REQUEST['emailPessoa'], $_REQUEST['nomePessoa'],$_REQUEST['usuarioPessoa'], $_REQUEST['senhaPessoa'], $mensagem,$titulo);
      }
      
      //se a pessoa foi incluida corretamente, o proximo case coloca-a no nivel/instancia
      $novoUsuario=1; //esta variavel seta a inclusao de novo usuario
    
      /*  
    	if (!cadastroBasico($_REQUEST['nomePessoa'],$_REQUEST['usuarioPessoa'],$_REQUEST['senhaPessoa'],$_REQUEST['emailPessoa'],1)) {
         header("Location:".$_SERVER['PHP_SELF']."?erro=1");
      }else{
       	header("Location:".$_SERVER['PHP_SELF']."?acao=A_incluiPessoaNivel&papel=".$_REQUEST['papel']."&pessoaNivelEscolhido[]=".$lastId);
      } 
      break;*/
  
    /** no A_incluiPessoaNivel é passado o codigo pessoa, e por este pesquisado se 
     *  essa pessoa já tem o tipo de autoriadde que é solicitaodo, se sim apenas
     *  incluimos na instancia se não incluimos naquela autoridade e depois colocamos na instancia 
     */ 
  case "A_incluiPessoaNivel"://inclui  pessoa,já existente, no nivel/instancia atual
   
   
    if (!empty($_REQUEST['pessoaNivelEscolhido'])) {
        $pessoaNivelEscolhido=$_REQUEST['pessoaNivelEscolhido'];
    }
    foreach($pessoaNivelEscolhido as $codPessoa){
      list($papel,$codSubPapel)=explode('-',$_REQUEST["papel"]);
      switch ($papel) {
  	    case "AG":
          if (!$_SESSION['userRole']!=ADMINISTRADOR_GERAL) { die; } //apenas adm geral deve incluir outro adm geral
          $admGeral=new AdministradorNivel();//a classe AdministradorNivel também trata a questão do administrador Geral
          $admGeral->criaAdmGeral($codPessoa,1);
  		    break;
  		  
  		  case "AN":
          if (!Pessoa::isAdm($_SESSION['userRole'])) { die; } //apenas um adm deve incluir adm nivel
          $admNivel=new AdministradorNivel();
          $isAdm=$admNivel->isAdm($codPessoa); 
          //note($isAdm);          echo(mysql_error());

          if(empty($isAdm)){//se ainda não tiver registro de ADM então cria e depois insere como administrador desta isntacia
            $admNivel->criaAdmGeral($codPessoa,2);
          }
          //echo(mysql_error());
          $admNivel->criaAdmNivel($_SESSION["codInstanciaGlobal"]);
          //die(mysql_error());
          break;
  		  
  		  case "ANB":
          //se nao for administrador, garante que o professor tenha direito de gerenciar o papel
          if (!Pessoa::isAdm($_SESSION['userRole'])) {
            if ($_SESSION['userRole']==PROFESSOR && !Professor::podeSerAdministrado($codSubPapel)) {  die;  }
          } 
                 
  		    $professor= new Professor();
          $professor->COD_PESSOA=$codPessoa;
          $professor->valorChavePessoa=$professor->isProfessor();
      
          if(empty($professor->valorChavePessoa)){//caso não seja professor
  					 $professor->criaNovoProfessor($codPessoa);
          }
          
  				$professor->inscreverInstancia($nivelAtual,$instanciaGlobal->codInstanciaNivel,$codSubPapel);
  		    break;
  		  
  		  case "NA":
          //se nao for administrador, garante que o professor tenha direito de gerenciar o papel
          if (!Pessoa::isAdm($_SESSION['userRole'])) {
            if ($_SESSION['userRole']==PROFESSOR && !Aluno::podeSerAdministrado($codSubPapel)) {  die;  }
          } 

  		    $aluno= new Aluno();
  		    $aluno->COD_PESSOA=$codPessoa;
  		    $aluno->valorChavePessoa=$aluno->isAluno();
  			  
          if(empty($aluno->valorChavePessoa)){//caso já tenha registro de aluno
  				  $aluno->criaNovoAluno($codPessoa);
  				}
  			    $aluno->inscreverInstancia($nivelAtual,$instanciaGlobal->codInstanciaNivel,$codSubPapel);
  		    
        break;
  	  }
  	}  
    echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
    break;
    /** no A_excluiPessoaNivel, o parametro passado é o
     *   cod da autoridade que a pessoa está exercendo no momento nesta instancia por ex codAluno.
     *   e por este código é retirado da instancia
     *   no caso do ADM GERAL , que não está vinculado a nenhuma instancia é passado somente o codpessoa, e ele é retirado da tabela administrador.
     *      */
  case "A_excluiPessoaNivel":// retira pessoa do nivel/instancia atual (mantem a pessoa)
     
    foreach($_REQUEST['pessoaNivelAtual'] as $codPessoa){
      list($cod,$papel,$codPessoa)=explode('-',$codPessoa);
      switch ($papel) { //tentar construir os cases depois pegando os papeis da plataforma dinamicamente
        case "AG":
          AdministradorNivel::deletaAdmGeral($cod);//codpessoa
          break;
        
        case "AN":
          $admNivel= new AdministradorNivel($cod);//codadmivel
          $admNivel->deletaAdmNivel($_SESSION['codInstanciaGlobal']);
          break;
        
        case "ANB":
          $professor= new Professor($cod); //codProf
          $professor->retirarInstancia($nivelAtual,$codInstanciaNivelAtual);
         
          $instanciaInicial=$professor->getInstanciaNivelInicial($professor->getCodPessoa());
          
          if($instanciaInicial->codInstanciaNivel==$codInstanciaNivelAtual){
            $professor->deleteInstanciaNivelInicial($professor->getCodPessoa(),$nivelAtual,$instanciaInicial);
          }
          break;
        
        case "NA":
          $aluno= new Aluno($cod);//codAluno
          $aluno->retirarInstancia($nivelAtual,$codInstanciaNivelAtual);
          
          $instanciaInicial=$aluno->getInstanciaNivelInicial($aluno->getCodPessoa());
          
          if($instanciaInicial->codInstanciaNivel==$codInstanciaNivelAtual){
            $aluno->deleteInstanciaNivelInicial($aluno->getCodPessoa(),$nivelAtual,$instanciaInicial);
          }
          break;
      }
    }
    echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
    break;
 
  case "formAdicionaPapel":
    if ($_SESSION['userRole'] != ADMINISTRADOR_GERAL) { die; }  //somente adm geral pode gerenciar papeis
      echo"<html>".
       "<head>".
        "<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>".
        "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
        "<link rel=\"stylesheet\" href=\"".$url."/cursos.css\" type=\"text/css\">".
        "<link rel=\"stylesheet\" href=\"".$url."/comunidade.css\" type=\"text/css\">".
        "<link rel=\"stylesheet\" href=\"".$url."/css/cssnavi.css\" type=\"text/css\">".
         "<script language=\"JavaScript\" src=\"".$url."/js/funcoes.js\"></script>".
         "<script language=\"JavaScript\" src=\"".$url."/js/lista.js\"></script>".
         "<script language=\"JavaScript\" src=\"".$url."/js/admPessoa.js\"></script>".
         "</head>".
      "<body>";
     //se for passado autoridade-codigo do papel, entao o formulario alterara o respectivo papel
     if (empty($_REQUEST['auth'])) {     
       $acao = 'adicionaPapel';   $atributos='';   $alteraAutoridade=1;
       //padrao eh permitir visibilidade e interacao
       $selVisivel = 'selected';       $selInterage = 'selected'; 
     } 
     else {       
       $atributos = explode('-',$_REQUEST['auth']); // autoridade, codPapel, descricao, visibilidade, interacao, prof pode gerenciar      
       $acao='alteraPapel&codPapel='.$atributos[1].'&selectAutoridade='.$atributos[0];   $alteraAutoridade=0;//acao do form e nao permite alterar autoridade
       //mostra status atual de visibilidade e interacao nos combos
       if ($atributos[3]) { $selVisivel = 'selected'; }     else {  $selInvisivel = 'selected';}
       if ($atributos[4]) { $selInterage = 'selected'; } else {  $selNaoInterage = 'selected'; }
       if ($atributos[5]) { $professorGerencia = 'selected'; } else {  $professorNaoGerencia = 'selected'; }
     }
    
     echo '<table valign="center"><tr><td align="center">';
     echo   '<form name="adicionaPapel" method="post" action="'.$_SERVER['PHP_SELF'].'?acao='.$acao.'">';
     echo     'Descri&ccedil;&atilde;o do Papel da pessoa:<br><input type="text" name="novoPapel" value="'.$atributos[2].'" size="40"><br><br>';
     // echo     "Insira uma imagem:<br><input type=\"file\" name=\"imagem\" value=\"\" size=\"33\"><br>";       
     echo     'Vincular com a autoridade:<br>';
     if ($alteraAutoridade) {
       $papel=listaPapel($_SESSION['codInstanciaGlobal']);    
       echo      '<select name="selectAutoridade" id="selectAutoridade" style="width: 250px;" '.$alteraAutoridade.'>';
       foreach ($papel as $siglaPapel=>$nAtributo) {
         if($siglaPapel!="AG" && $siglaPapel!="AN") {
           if ($siglaPapel==$atributos[0]) { $selected='selected'; } else { $selected=''; }
           echo      '<option value="'.$siglaPapel.'" '.$selected.'>'.$label[$siglaPapel].'</option>';
         }
       }
     }
     else {
       echo '<b>'.$label[$atributos[0]].'</b>';
     }
     echo     "</select><br><br>";
     echo     " Visibilidade para outros usu&aacute;rios<br>";
     echo     " <select name=\"selectVisibilidade\" id=\"selectVisibilidade\" style=\"width: 250px; \">";
     echo     "  <option value=\"1\" ".$selVisivel.">Vis&iacute;vel</option>";
     echo     "  <option value=\"0\" ".$selInvisivel.">Invis&iacute;vel</option>";        
     echo     " </select><br><br>";
     echo     " Possibilidade de Interagir<br>";
     echo     " <select name=\"selectDireitosAcesso\" id=\"selectDireitosAcesso\" style=\"width: 250px; \">";
     echo     "  <option value=\"1\" ".$selInterage.">Visualiza e interage</option>";
     echo     "  <option value=\"0\" ".$selNaoInterage.">Apenas visualiza, n&atilde;o interage</option>";        
     echo     " </select><br><br>";

     echo     " Permiss&atilde;o adm n&iacute;vel b&aacute;sico (usualmente professor)<br>";
     echo     " <select name=\"selectProfessorGerencia\" id=\"selectProfessorGerencia\" style=\"width: 250px; \">";
     echo     "  <option value=\"0\" ".$professorNaoGerencia.">N&Atilde;O pode gerenciar</option>";        
     echo     "  <option value=\"1\" ".$professorGerencia.">Pode gerenciar</option>";
     echo     " </select><br><br>";

     echo     "<input type=\"button\" value=\"Enviar\" onClick=\"validaFormAdicionaPapel();\">";
     echo   "</form>";
     echo "</td></tr></table>";
     echo "</html>";
     echo "</body>";
     break;  
  
  case "adicionaPapel":  
    if ($_SESSION['userRole'] != ADMINISTRADOR_GERAL) { die; }  //somente adm geral pode gerenciar papeis
    simpleHeader();
    
    $ok=adicionaPapel($_REQUEST['novoPapel'], $_REQUEST['selectAutoridade'],$_REQUEST['selectDireitosAcesso'],$_REQUEST['selectVisibilidade'],$_REQUEST['selectProfessorGerencia']);
    if($ok){   msg('Papel adicionado com Sucesso!');   }
    echo '<div style="text-align:center; padding-top:50px;"><a href="#" onClick="window.opener.location.href=\''.$_SERVER['PHP_SELF'].'\'; window.close();">Fechar Janela</a></div>';   
    break;
     
  /**
   *  Atualiza os atributos do papel
   */
  case "alteraPapel":
    if ($_SESSION['userRole'] != ADMINISTRADOR_GERAL) { die; }  //somente adm geral pode gerenciar papeis
    simpleHeader();
    
    $okPapel = alteraPapel($_REQUEST['codPapel'],$_REQUEST['novoPapel'], $_REQUEST['selectAutoridade'],$_REQUEST['selectDireitosAcesso'],$_REQUEST['selectVisibilidade'],$_REQUEST['selectProfessorGerencia']);
    $okAjusteInstancias = atualizaInstanciaInicial($_REQUEST['codPapel'], $_REQUEST['selectAutoridade']);
    //echo mysql_error();
    if ($okPapel && $okAjusteInstancias){   msg('Papel alterado com Sucesso!');   } else { echo 'Erro! Contate o suporte.'; }
    echo '<div style="text-align:center; padding-top:50px;"><a href="#" onClick="window.opener.location.href=\''.$_SERVER['PHP_SELF'].'\'; window.close();">Fechar Janela</a></div>';   
    break;

  /**
   *  excluir
   */
  case "excluiPapel":
    if ($_SESSION['userRole'] != ADMINISTRADOR_GERAL) { die; }  //somente adm geral pode gerenciar papeis
    simpleHeader();
    $atributos = explode('-',$_REQUEST['auth']); // autoridade, codPapel, descricao, visibilidade, interacao, prof pode gerenciar      
    
    $temUtilizacao = isPapelUtilizado($atributos[1],$atributos[0]);
    if (!$temUtilizacao) {    
      $ok=excluiPapel($atributos[1],$atributos[0]);
      //echo mysql_error();
      if ($ok){   msg('Papel excluido com Sucesso!');   } else { echo 'Erro! Contate o suporte.'; }
    }
    else {
      msg('Esse papel est&aacute; atribu&iacute;do a pessoas. <br><br>Retire todos as ocorr&ecirc;ncias e depois exclua o papel.'); 
    }
    echo '<div style="text-align:center; padding-top:50px;"><a href="#" onClick="window.opener.location.href=\''.$_SERVER['PHP_SELF'].'\'; window.close();">Fechar Janela</a></div>';   
    
    break;

  /* EM PRINCIPIO NAO UTILIZADO
  case "A_mudaPapelPessoa"://muda papel da pessoa que já está dentro do nivel/instancia
    /// aqui vai ir o código que permite trocar o papel da pessoa dentro da instancia
    header("Location:".$_SERVER['PHP_SELF']);
    break;
  */
}

function simpleHeader() {
  global $url;
  
  echo "<html>".
        "<head>".
        "<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>".
        "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
        "<link rel=\"stylesheet\" href=\"".$url."/css/padraogeral.css\" type=\"text/css\">".
        "</head>".
        "<body class='bodybg' style='padding-top:50px;'>";

}
?>