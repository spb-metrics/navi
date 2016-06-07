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

/** SCRIPT PARA AS PESSOAS SE INSCREVEREM NO NAVI **/
include("../config.php");
include($caminhoBiblioteca.'/inscricao.inc.php');
include($caminhoBiblioteca.'/pessoa.inc.php');
include($caminhoBiblioteca.'/aluno.inc.php');
include($caminhoBiblioteca.'/professor.inc.php');
include($caminhoBiblioteca.'/cadastro.inc.php');
include($caminhoBiblioteca.'/autenticacao.inc.php');
session_name(SESSION_NAME); session_start(); 
//security();

$acao = $_REQUEST['acao'];

//imprime o cabecalho HTML
function printHTMLHeader() {
  ?>
  
  <html>
  	<head>
  		<title>Inscri&ccedil;&otilde;es</title>
  		<link rel="stylesheet" href="../cursos.css" type="text/css">
  		<link rel="stylesheet" href="../css/inscricao.css" type="text/css">
  	</head>
  
  <body bgcolor="#FFFFFF" text="#000000" class="bodybg">
  
<?php
}

//aqui divimos o arquivo de acordo com a variavel acao, passada por GET
switch ($acao) {  
  
  case "":
   
 	
  case "A_inscricoesAbertas":
    //lista as instancias com inscricoes abertas
    
    printHTMLHeader();
         
    echo '<span class="tituloInscricoes">Inscri&ccedil;&otilde;es abertas</span><br><br>';
       
    $inscricoes = getInscricoesAbertas(time(),$_REQUEST['codInstanciaGlobal']);
    
    if (!empty($inscricoes->records)) {
      
      //javascript para mostrar/esconder o texto da inscricao(cabecalho)
      echo "<script type=\"text/javascript\">\n".
           "function toggleDiv(id) {\n".
           " if (document.getElementById(id).style.display == 'none')\n".
           "    document.getElementById(id).style.display = 'block';\n".
           " else\n".
           "    document.getElementById(id).style.display = 'none';\n".            
           "}\n".
           "</script>";
     
      //exibe cada instituicao com inscricao aberta
      foreach($inscricoes->records as $inscricao) {  
        $inicio = date("d/m/Y",$inscricao->inicio);
        $fim = date("d/m/Y",$inscricao->fim);
        
        echo '<br><div class="divInscricaoAberta">';
        echo '<div style="margin-left: 20px; margin-right: 20px">';
        echo '<span class="tituloInstanciaInscAberta">'.$inscricao->descInstancia.'</span>';
        echo '<span style="float:right"><a href="#" onclick="toggleDiv(\'textoInscricao'.$inscricao->codInstanciaGlobal.'\')">+/-</a></span>';
        echo '<br><span>Per&iacute;odo de Inscri&ccedil;&otilde;es: de '.$inicio.' at&eacute; '.$fim.'</span>';
        echo '<br><span>N&uacute;mero m&aacute;ximo de inscritos: '.$inscricao->maximoInscritos.'</span>';
        echo '<br><br><div id="textoInscricao'.$inscricao->codInstanciaGlobal.'" class="textoInscricao">';
        echo nl2br($inscricao->cabecalho);
        echo '<br><br><center>';
        if (empty($_SESSION['COD_PESSOA'])) { //somente para usuarios nao logados
          $url = 'index.php?acao=A_inscricaoNovoUsuario&frm_inscricaoCodInstanciaGlobal='.$inscricao->codInstanciaGlobal.'&frm_inscricaoNome='.urlencode($inscricao->descInstancia);
          echo '<input type="button" value="Inscrever (Novo usu&aacute;rio)" onclick="window.location.href=\''.$url.'\'">&nbsp;&nbsp;';
        }
        $url = 'index.php?acao=A_inscricaoUsuarioExistente&frm_inscricaoCodInstanciaGlobal='.$inscricao->codInstanciaGlobal.'&frm_inscricaoNome='.urlencode($inscricao->descInstancia);
        echo '<input type="button" value="Inscrever (Usu&aacute;rio j&aacute; cadastrado)" onclick="window.location.href=\''.$url.'\'"><br><br></center>';
      
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
    }
    else {
    	echo '<div align="center">N&atilde;o existem inscri&ccedil;&otilde;es abertas</div>';
    }
    break;


  case "A_inscricaoNovoUsuario":
    //inscricao para novo usuario
    //mostra o cadastro de inscricao
    if (empty($_SESSION['inscricaoCodInstanciaGlobal'])) {
      $_SESSION['inscricaoCodInstanciaGlobal'] = $_REQUEST['frm_inscricaoCodInstanciaGlobal'];
    }
    if (empty($_SESSION['inscricaoNome'])) {
      $_SESSION['inscricaoNome'] = $_REQUEST['frm_inscricaoNome'];
    }

    printHTMLHeader();

    echo '<div align="center"><span class="tituloInscAbertas">Inscrever-se em '.$_SESSION['inscricaoNome'].'</span><br><br>';
    echo '<span class="tituloInscAbertas">Cadastro de novo usu&aacute;rio</span><br><br>';
    
    formCadastro('index.php?acao=A_salvaCadastroNovoUsuario');
    break;

    
  case "A_salvaCadastroNovoUsuario":
    //salva o cadastro do novo usuario
    //e efetua a inscricao
    
    if (existeUsuario($_REQUEST['USER_PESSOA'],0)) {
      //o nome de usuario escolhido ja existe
      //volta ao formulario, avisando o erro
      
      //passa na URL os valores preenchidos pelo usuario
      $url = "index.php?acao=A_inscricaoNovoUsuario&erro=1";
      $campos = array("USER_PESSOA","NOME_PESSOA","DATA_NASC_PESSOA","COD_SEXO",
                      "DOC_ID_PESSOA","EMAIL_PESSOA","CPF_PESSOA",
                      "FRASE_SENHA_PESSOA","DESC_END","BAIRRO_END","CIDADE_END",
                      "UF_END","PAIS_END","CEP_END","COD_INTERNAC_FONE","COD_AREA_FONE",
                      "NRO_FONE","RAMAL_FONE");
      
      foreach($campos as $campo) {
        $url.= "&".$campo."=".urlencode($_REQUEST[$campo]);
      }
      
      //redireciona
      echo '<script>window.location.href="'.$url.'";</script>';
      //header("Location: ".$url);      
      exit;
    }
    //  inscreve o aluno sem  passar pela lista de pendentes
    $inscricao=leConfigInscricoes($_SESSION['inscricaoCodInstanciaGlobal']); //primeiro lÛ os arquivos
    
    printHTMLHeader();    

    echo '<div align="center"><span class="tituloInscAbertas">Inscrever-se em '.$_SESSION['inscricaoNome'].'</span><br><br>';

    list($d,$m,$a) = explode("/",$_REQUEST['DATA_NASC_PESSOA']);

    //se a inscricao for automatica, ativa a pessoa
    if ($inscricao->inscricaoAutomatica) { $ativa=1;} else { $ativa=0; }  
    //salva o cadastro do novo usuario
  	$ok = cadastro($_REQUEST["USER_PESSOA"], $_REQUEST["NOME_PESSOA"], $a.'-'.$m.'-'.$d , $_REQUEST["COD_SEXO"], $_REQUEST["DOC_ID_PESSOA"], $_REQUEST["EMAIL_PESSOA"], $_REQUEST["CPF_PESSOA"], $_REQUEST["SENHA_PESSOA"], $_REQUEST["FRASE_SENHA_PESSOA"], $_REQUEST["DESC_END"], $_REQUEST["BAIRRO_END"], $_REQUEST["CIDADE_END"], $_REQUEST["UF_END"], $_REQUEST["PAIS_END"], $_REQUEST["CEP_END"], $_REQUEST["COD_INTERNAC_FONE"], $_REQUEST["COD_AREA_FONE"], $_REQUEST["NRO_FONE"], $_REQUEST["RAMAL_FONE"],$ativa);

    if ($ok) {
      //le a pessoa recem-criada
      $pessoa = existeUsuario($_REQUEST["USER_PESSOA"],0);
      //agora salva o novo aluno
      //agora salva a inscricao (pendente)
      $aluno = new Aluno();
      $sucesso = $aluno->criaNovoAluno($pessoa['COD_PESSOA']);
    
      if ($sucesso) {
        //obtem o COD_AL recem criado  
        $codAl = mysql_insert_id();
        //inscreve o aluno automaticamente, sem  passar pela lista de pendentes
        if ($inscricao->inscricaoAutomatica)     {
          //inscreve o aluno, enviando os parametros de usuario tendo confirmado
          //e tendo aceitado  
          $sucesso = $aluno->inscrever($_SESSION['inscricaoCodInstanciaGlobal'],1,1);          
          //coloca a pessoa diretamente na instancia, se o nivel relacionar pessoas 
          //colocar teste se o nivel relaciona pessoas
          $instanciaGlobal = new InstanciaGlobal($_SESSION['inscricaoCodInstanciaGlobal']);            
          $nivel= new Nivel($instanciaGlobal->codNivel);
          
          if ($nivel->relacionaAlunosProfessores) {
            $aluno->inscreverInstancia($nivel,$instanciaGlobal->codInstanciaNivel);
          }          
        }              
        //===================================================================================================   
        else {
          $sucesso = $aluno->inscrever($_SESSION['inscricaoCodInstanciaGlobal']);
        }
             
        if ($sucesso) {
          echo "<div style='color:red;'>Inscri&ccedil;&atilde;o realizada com sucesso!</div>";
          echo "<div style='color:red;'>Aguarde a confirma&ccedil;&atilde;o pelo endere&ccedil;o de correio eletr&ocirc;nico informado.</div>";
          echo "<div style='color:red;'>Caso n&atilde;o receba nenhuma mensagem, tente acessar com o usuario/senha informados na inscri&ccedil;&atilde;o.</div>";
          if ($inscricao->usarBoletoBanrisul) { linkBoletoBanrisul($_SESSION['COD_PESSOA'],$_SESSION['inscricaoCodInstanciaGlobal']); }  
          $_SESSION['inscricaoCodInstanciaGlobal'] = $_SESSION['inscricaoNome'] = "";    
        }
        else {
          echo "<div><center><span  style='color:red;'>Erro ao fazer a inscri&ccedil;&atilde;o</span>";        
          echo '<div><br><a href="javascript:history.back()">Voltar</a></center></div>';                
        }      
      }
    }
    else {
    	echo "<span style='color:red;'>Erro ao cadastrar novo usu&aacute;rio</span>";
    }
    
    //$_SESSION['inscricaoCodInstanciaGlobal'] = $_SESSION['inscricaoNome'] = "";    
    echo '</div>';
    break;  

  case "A_inscricaoUsuarioExistente":
    //inscricao para usuario existente
    //verifica se o usuario ja esta logado
    //se nao ta logado, apresenta um form de login
    //se esta logado, redireciona para a acao A_autenticaInscreveUsuarioExistente,
    //para fazer a inscricao

    if (isset($_REQUEST['frm_inscricaoCodInstanciaGlobal']) ) {
      $_SESSION['inscricaoCodInstanciaGlobal'] = $_REQUEST['frm_inscricaoCodInstanciaGlobal'];
//    $_SESSION['inscricaoCodNivel'] = $_REQUEST['frm_inscricaoCodNivel'];
      $_SESSION['inscricaoNome'] = $_REQUEST['frm_inscricaoNome'];
    }

    if (empty($_SESSION['COD_PESSOA'])) {
      //usuario nao ta logado, pede para ele logar
      //monta o formulario de login
      //cujo action eh ./index.php?acao=A_autenticaInscricaoUsuarioExistente    
      //onde eh feita a autenticacao e inscricao
  
      printHTMLHeader();
      echo '<div align="center"><span class="tituloInscAbertas">Inscrever-se em '.$_SESSION['inscricaoNome'].'</span><br><br>';
      
      echo '<div align="center">Para efetuar a inscri&ccedil;&atilde;o, por favor logue-se no sistema, utilizando os campos abaixo. </div>';
      echo '<br><br>';
      echo "<form name='form1' method='POST' action='index.php?acao=A_autenticaInscreveUsuarioExistente'>";
      echo '<input type="hidden" name="PROX_PAG" value="inscricao/index.php?acao=A_inscricaoUsuarioExistente">';
      echo "Usu&aacute;rio:<input type='text' id='USER_PESSOA' name='USER_PESSOA' size='12' maxlength='50'	onKeyPress='entraSenha(event);'  value=\"".$_REQUEST['USER_PESSOA']."\">";
      echo "Senha: <input type='password' name='SENHA_PESSOA' size='12' maxlength='50' onKeyPress='entraSenha(event);'>";
      echo "<br><br><input type='submit' value='&nbsp;Inscrever&nbsp;'>";
      echo "&nbsp;&nbsp;<input type='submit' value='&nbsp;Cancelar&nbsp;'>";
      echo "</form>";
      echo "</div>";

      if ($_REQUEST['erro']) {
        echo "<script type=\"text/javascript\"> alert('Usuario ou senha invalidos.'); document.form1.USER_PESSOA.focus();</script>";                        
      }     
    }
    else {
      //usuario ja esta logado
      //redireciona direto para a acao de inscricao
      session_write_close();
      echo '<script>window.location.href="index.php?acao=A_autenticaInscreveUsuarioExistente";</script>';
      //header("Location: index.php?acao=A_autenticaInscreveUsuarioExistente");
      exit;
    }
    break;
  
  case 'A_autenticaInscreveUsuarioExistente':
    //faz a autenticacao do usuario existente que esta solicitando inscricao (se nao tiver logado)
    //e faz a inscricao do usuario    
    
    if (empty($_SESSION['COD_PESSOA'])) {
      //usuario nao esta autenticado ainda, fazer autenticacao
      $sucesso = autenticaUsuario($_REQUEST['USER_PESSOA'], $_REQUEST['SENHA_PESSOA'], 0);
      if ($sucesso) {
        //verifica se eh aluno
        //para pegar o codigo do aluno (colocado em $_SESSION['COD_AL'])
        verificaAluno();
        
        if (empty($_SESSION['COD_AL'])) {        
          //verifica se eh professor (coloca o codigo em $_SESSION['COD_PROF'])
          verificaProf();        
          if (empty($_SESSION['COD_PROF']))
            die("Erro inesperado: usuario inexistente como aluno ou professor");
        }
      }
      else {
        //autenticacao falhou
        echo '<script>window.location.href="index.php?acao=A_inscricaoUsuarioExistente&erro=1&USER_PESSOA='.$_REQUEST['USER_PESSOA'].'";</script>';
        //header('Location: index.php?acao=A_inscricaoUsuarioExistente&erro=1&USER_PESSOA='.$_REQUEST['USER_PESSOA']);
        exit;
      }
      
      $jaEstavaLogado = 0;
    }
    else {
    	$jaEstavaLogado = 1;
    }

    //efetua a inscricao do usuario (marcada como pendente) 
    //caso seja insrcaoAutomatica efetua inscricao sem passar pela lista de pendentes 

    printHTMLHeader();
    echo '<div align="center"><span class="tituloInscAbertas">Inscrever-se em '.$_SESSION['inscricaoNome'].'</span><br><br>';

    if (!empty($_SESSION['COD_AL']))
      $pessoa = new Aluno($_SESSION['COD_AL']);      
    elseif (!empty($_SESSION['COD_PROF']))    
      $pessoa = new Professor($_SESSION['COD_PROF']);
    else
      die("Erro inesperado 2");  
//=============================================================================================
    //inscreve o aluno sem  passar pela lista de pendentes
    $inscricao=leConfigInscricoes($_SESSION['inscricaoCodInstanciaGlobal']);   
   
    if ($inscricao->inscricaoAutomatica) { $ativa=1;} else { $ativa=0; } 
    
    if ($inscricao->inscricaoAutomatica) {
      //inscreve a pessoa na turma 
      $inscLivreSemAprovacao = $pessoa ->inscrever($_SESSION['inscricaoCodInstanciaGlobal'],1,1);//$usuarioConfirmou=;$aceita=0
  
      //coloca a pessoa diretamente na instancia, se o nivel relacionar pessoas
      //colocar teste se o nivel relaciona pessoas
      $instanciaGlobal = new InstanciaGlobal($_SESSION['inscricaoCodInstanciaGlobal']);            
      $nivel= new Nivel($instanciaGlobal->codNivel);
    
      if ($nivel->relacionaAlunosProfessores) { // ver se não é relacionaAlunosProfessores
        $pessoa->inscreverInstancia($nivel,$instanciaGlobal->codInstanciaNivel);
      }
     
     if($inscLivreSemAprovacao){
        echo "<div style='color:red;'>Voc&ecirc; foi inscrito com sucesso</div>";
        echo "<div style='color:red;'>Aguarde a confirma&ccedil;&atilde;o pelo endere&ccedil;o de correio eletr&ocirc;nico informado.</div>";
        echo "<div style='color:red;'>Caso n&atilde;o receba nenhuma mensagem, tente acessar com o usuario/senha informados na inscri&ccedil;&atilde;o.</div>";
        if ($inscricao->usarBoletoBanrisul) { linkBoletoBanrisul($_SESSION['COD_PESSOA'],$codInstanciaGlobal); }      
      }
    else {
        echo "<span style='color:red;'>Erro ao fazer a inscri&ccedil;&atilde;o</span>";        
        echo '<br><br><a href="javascript:history.back()">Voltar</a>';
      }
    
    }
 //=====================================================================================   
    else {
      //verifica se a pessoa ja esta inscrita
      if (!$pessoa->estaInscrito($_SESSION['inscricaoCodInstanciaGlobal'])) {      
        //inscreve a pessoa e marca a inscricao como estando pendente para aprovacao por um administrador
        $sucesso = $pessoa->inscrever($_SESSION['inscricaoCodInstanciaGlobal']);
        
        if ($sucesso) {
          echo "<div style='color:red;'>Inscri&ccedil;&atilde;o realizada com sucesso!</div>";
          echo "<div style='color:red;'>Aguarde a confirma&ccedil;&atilde;o pelo endere&ccedil;o de correio eletr&ocirc;nico informado.</div>";
          echo "<div style='color:red;'>Caso n&atilde;o receba nenhuma mensagem, tente acessar com o usuario/senha informados na inscri&ccedil;&atilde;o.</div>";
          if ($inscricao->usarBoletoBanrisul) { linkBoletoBanrisul($_SESSION['COD_PESSOA'],$codInstanciaGlobal); } 
        }
        else {
          echo "<span style='color:red;'>Erro ao fazer a inscri&ccedil;&atilde;o</span>";        
        }
      }    
      else {      
        echo "<div style='color:red;'>Voc&ecirc; j&aacute; est&aacute; inscrito</div>";
        echo "<div style='color:red;'>Aguarde a confirma&ccedil;&atilde;o pelo endere&ccedil;o de correio eletr&ocirc;nico informado.</div>";
        echo "<div style='color:red;'>Caso n&atilde;o receba nenhuma mensagem, tente acessar com o usuario/senha informados   na inscri&ccedil;&atilde;o.</div>";
        if ($inscricao->usarBoletoBanrisul) { linkBoletoBanrisul($_SESSION['COD_PESSOA'],$codInstanciaGlobal); } else {  }   
      }
    }   
    echo "<a href='javascript:history.back()'>Voltar</a>";
    
    $_SESSION['inscricaoCodInstanciaGlobal'] = $_SESSION['inscricaoCodNivel'] = "";
    
    if (!$jaEstavaLogado) {
      //se nao estava logado antes, entao un-seta as variaveis de sessao setadas pela autenticacao
      unset($_SESSION['COD_PESSOA']);
      unset($_SESSION['COD_AL']);
      unset($_SESSION['usuarioAtivo']);
      unset($_SESSION['COD_PROF']);
    }
    
    break;
  
  case 'A_confirmar_inscricao':  
    //acao para qdo, um usuario, q foi incluido/inscrito atraves de uma lista
    //de inscricoes, quer confirmar a sua inscricao
    
    //primeiro confere se o hash esta correto
    $hashCalc = md5(INSCRICOES_USUARIO_CONFIRMAR_HASH.$_REQUEST['COD_PESSOA'].$_REQUEST['USER_PESSOA'].$_REQUEST['codInstanciaGlobal']);    
     
    
    if ($hashCalc == $_REQUEST['hash']) {
      //ok, o hash eh o correto
      
      //agora ativa o usuario e libera suas inscricoes
      $aluno = new Aluno($_REQUEST['COD_AL']);
      $aluno->buscaPessoa($_REQUEST['COD_PESSOA']);
      
      if ($aluno->SENHA_PESSOA != $_REQUEST['senha']) {
        die("Acesso invalido");
      }
      
      //le as inscricoes pendentes do aluno
      //$inscricoesPendentesAluno = $aluno->getInscricoesPendentes();
      
      $instGlobal = new InstanciaGlobal((int)$_REQUEST['codInstanciaGlobal']);
      $nivel = new Nivel((int)$_REQUEST['codNivel']);        
      //libera a inscricao
      $sucesso = $aluno->confirmarInscricao($nivel,$instGlobal->codInstanciaGlobal,$instGlobal->codInstanciaNivel);
      if (!$sucesso) {
        die("Erro ao confirmar inscricao");
      }
      
      echo "Inscricao confirmada com sucesso!";      
    }
    else {
      //erro, o hash nao conferiu
      die("Acesso invalido");    
    }
    
    break;	
  
  
  break;
}
?>
</body>
</html>
