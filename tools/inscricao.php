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

//ini_set('display_erros',1); error_reporting(E_ALL);

/*** SCRIPT QUE PERMITE OS ADMINISTRADORES 
 *   ABRIR/CONFIGURAR/ENCERRAR INSCRICOES NOS CURSOS
 **/

include("../config.php");
include($caminhoBiblioteca."/inscricao.inc.php");
include($caminhoBiblioteca."/pessoa.inc.php");
include($caminhoBiblioteca."/aluno.inc.php");
include($caminhoBiblioteca."/professor.inc.php");
include($caminhoBiblioteca."/cadastro.inc.php");
include($caminhoBiblioteca."/autenticacao.inc.php");

session_name(SESSION_NAME); session_start(); security();
//

$acao = $_REQUEST['acao'];
if (empty($acao)) {
  $acao = "A_configInscricao";
}
if (!Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])) {
  die("Acesso negado");
}

?>

<html>
	<head>
		<title>Configura&ccedil;&atilde;o das Inscri&ccedil;&otilde;es</title>
		<link rel="stylesheet" href="<?php echo $urlCss; ?>/padraogeral.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?

switch ($acao) {  

  case "A_configInscricao":
    //adiciona o formulario de configuracao da inscricao
    echo '<div align="center">';
    echo '<span style="font-size: 16pt; font-weight: bold">Configurar inscri&ccedil;&otilde;es</span><br><br>';
    echo constroiFormConfigInscricao($_SESSION['codInstanciaGlobal']);
    echo '</div>';
    
    
    break;
      
  case "A_gravaConfigInscricao":  
    //grava a configuracao de inscricao 

    $inicio = mktime(0,0,0,$_REQUEST['frm_inicio_month'],$_REQUEST['frm_inicio_day'], $_REQUEST['frm_inicio_year']);  
    $fim = mktime(23,59,59,$_REQUEST['frm_fim_month'],$_REQUEST['frm_fim_day'], $_REQUEST['frm_fim_year']);      
    $inscricaoPublica = $_REQUEST['frm_inscPub'];
    $inscricaoAutomatica = $_REQUEST['frm_inscLivre'];
//=================================================================================    
    /*
    // se a inscricao for livre, verifica se o nivel atual aceita inscri�oes, usa a tabela nivel 
    if($inscricaoAutomatica) { 
      
      $nivelAtual = getNivelAtual();
         $codNivelAtual = $nivelAtual-> codNivel;
         //Print_r ($codNivelAtual);die();    
      $relacionaAlunoProfessor = relacionaAlunoProfessor($codNivelAtual);
      
      if ($relacionaAlunoProfessor = 1 ){
        $sucesso = salvaConfigInscricoes($_SESSION['codInstanciaGlobal'], $inicio, $fim, $_REQUEST['frm_maxInscritos'], $_REQUEST['frm_cabecalho'], $inscricaoPublica, $inscricaoAutomatica);
        echo '<br><br><center>';
        if ($sucesso) {
           echo '<div style="font-weight:bold; font-size: 12pt">Configura&ccedil;&atilde;o de inscri&ccedil;&otilde;es salvas com sucesso!</div>';  
           echo '<br><br><a href="recursos_fixos.php">Voltar</a></center>'; 
           }
        else {
            echo '<div style="font-weight:bold; font-size: 12pt">Esta inst&acirc;ncia n&atilde;o aceita inscrc&otilde;es livres <br /></div>' ;
            echo '<br><a href="../tools/inscricao.php">Voltar</a></center>';
            }
        }
    }*/
//===========================================================================================     
    //else {
    $sucesso = salvaConfigInscricoes($_SESSION['codInstanciaGlobal'], $inicio, $fim, $_REQUEST['frm_maxInscritos'], $_REQUEST['frm_cabecalho'], $inscricaoPublica, $inscricaoAutomatica);  
    
    echo '<br><br><center>';
    
    if ($sucesso) {
      echo '<div style="font-weight:bold; font-size: 12pt">Configura&ccedil;&atilde;o de inscri&ccedil;&otilde;es salvas com sucesso!</div>';   
    }
    else {
      echo '<div style="font-weight:bold; font-size: 12pt">Houve um erro ao salvar as inscri&ccedil;&otilde;es</div>';    
    }

    echo '<br><br><a href="recursos_fixos.php">Voltar</a></center>';
      
      
    break;
    
  case "A_inscricoesPendentes":
    //mostra as inscricoes pendentes
    $inscricoesPendentes = getInscricoesPendentes($_SESSION['codInstanciaGlobal']);
    //mostra a lista de inscricoes pendentes
    mostraListaInscricoes($inscricoesPendentes,'pendentes');
   
    break;
  
  case "A_aceitaInscricao":
    if (isset($_REQUEST['COD_AL']))
      $pessoa = new Aluno($_REQUEST['COD_AL']);
    elseif (isset($_REQUEST['COD_PROF']))
      $pessoa = new Professor($_REQUEST['COD_PROF']);
    else
      die("Parametros incorretos");
      
    
    //libera a inscricao
    $nivel = getNivelAtual();
    $codInstanciaNivel = getCodInstanciaNivelAtual();      
//  $pessoa->COD_PESSOA = $_REQUEST['COD_PESSOA'];
    $pessoa->buscaPessoa($_REQUEST['COD_PESSOA']);

    $sucesso = $pessoa->liberarInscricao($nivel,$_SESSION['codInstanciaGlobal'],$codInstanciaNivel);  
              
    if ($sucesso) {
      echo '<div align="center">Inscri&ccedil;&atilde;o liberada com sucesso!</div>';
      mandaMailInscricaoAceita($nivel,$codInstanciaNivel,$pessoa);
    }
    else {
      echo '<div align="center">Houve um erro ao liberar a inscri&ccedil;&atilde;o.</div>';    
    }
    
    echo '<br><div align="center"><a href="inscricao.php?acao=A_inscricoesPendentes">Voltar</a></div>';
  
  break;
  
  case 'A_rejeitaInscricao':
    //rejeita a inscricao e 
    //manda um email avisando a pessoa que sua inscricao nao foi aceita
    if (isset($_REQUEST['COD_AL']))
      $pessoa = new Aluno($_REQUEST['COD_AL']);
    elseif (isset($_REQUEST['COD_PROF']))
      $pessoa = new Professor($_REQUEST['COD_PROF']);
    else
      die("Parametros incorretos");
          
    $nivel = getNivelAtual();
    $codInstanciaNivel = getCodInstanciaNivelAtual();      
    $pessoa->buscaPessoa($_REQUEST['COD_PESSOA']);
    
    //rejeita a inscricao
    $sucesso = $pessoa->rejeitarInscricao($_SESSION['codInstanciaGlobal']);
    
    if ($sucesso) {
      echo '<div align="center">Inscri&ccedil;&atilde;o rejeitada com sucesso!</div>';
   
      //envia o email
      mandaMailInscricaoRejeitada($nivel,$codInstanciaNivel,$pessoa);
    }
    else {
      echo '<div align="center">Houve um erro ao rejeitar a inscri&ccedil;&atilde;o.</div>';    
    }
    
    echo '<br><div align="center"><a href="inscricao.php?acao=A_inscricoesPendentes">Voltar</a></div>';
        
  break;

  case 'A_rejeitaInscricaoExcluiUsuario':
    //rejeita a inscricao , deletando o usuario e 
    //manda um email avisando a pessoa que sua inscricao nao foi aceita
    if (isset($_REQUEST['COD_AL']))
      $pessoa = new Aluno($_REQUEST['COD_AL']);
    elseif (isset($_REQUEST['COD_PROF']))
      $pessoa = new Professor($_REQUEST['COD_PROF']);
    else
      die("Parametros incorretos");
          
    $nivel = getNivelAtual();
    $codInstanciaNivel = getCodInstanciaNivelAtual();      
    $pessoa->buscaPessoa($_REQUEST['COD_PESSOA']);
    
    if ($pessoa->ativa) {
      die("Esse procedimento apenas pode ser executado em pessoas inativas.");
    }
    
    //rejeita a inscricao (deletando o registro da inscricao)
    $sucesso = $pessoa->rejeitarInscricao($_SESSION['codInstanciaGlobal'],1);
    
    //deleta a pessoa
    $pessoa->deleta();
    
    if ($sucesso) {
      echo '<div align="center">Inscri&ccedil;&atilde;o rejeitada com sucesso!</div>';
   
      //envia o email
    //  mandaMailInscricaoRejeitada($nivel,$codInstanciaNivel,$pessoa);
    }
    else {
      echo '<div align="center">Houve um erro ao rejeitar a inscri&ccedil;&atilde;o.</div>';    
    }
    
    echo '<br><div align="center"><a href="inscricao.php?acao=A_inscricoesPendentes">Voltar</a></div>';
        
  break;

  case "A_inscricoesAceitas":
    //mostra as inscricoes aceitas
    $inscricoesAceitas = getInscricoesAceitas($_SESSION['codInstanciaGlobal']);
    //mostra a lista de inscricoes pendentes
    mostraListaInscricoes($inscricoesAceitas,'aceitas');
    
    break;
  
  case "A_relacionarTurmaListaTurmas":
    //lista as turmas para relacionar o aluno 
    
    echo '<div align="center">Lista de Turmas</div>';
        
  break;
  
  case "A_relacionarTurmaSalva":
    //salva o relacionamento do aluno/professor com as turmas


  break;
      
  case "A_carregaListaUsuarios":
    //carrega uma lista de usuarios a serem previamente inscritos
    //a partir de uma lista

    echo '<div align="center">';
    echo '<span style="font-size: 16pt; font-weight: bold">Carregar lista de usu&aacute;rios</span><br><br>';

    echo '<form action="'.$_SERVER['PHP_SELF'].'?acao=A_carregaListaUsuariosSalva" method="POST" enctype="multipart/form-data">';
    echo '<p>O arquivo que cont&eacute;m a lista de usu&aacute;rios deve ser em formato CSV.';
    echo ' Em cada linha deve conter um aluno a ser inscrito, com os campos separados por v�rgula na seguinte ordem: Nome da pessoa, Nome do Usuario, senha e email.</p>';   
    
    echo 'Arquivo CSV com a lista de usu&aacute;rios: ';
    echo '<input type="file" name="frm_arq_lista">'; 
    
    echo '<br><br>';
    echo '<input type="submit" value="Carregar" style="color:darkblue;">&nbsp;&nbsp;&nbsp;';
    echo '<input type="button" value="Cancelar" style="color:darkred;" onclick="window.location.href=\'recursos_fixos.php\';">';

    echo '</form>';  
    echo '</div>';
  
    break;    
  
  

  case "A_carregaListaUsuariosSalva":
    //salva a lista de usuarios carregada
    echo '<div align="center">';
    echo '<div style="font-size: 16pt; font-weight: bold">Carregar lista de usu&aacute;rios</div>';
    
    if (!empty($_FILES['frm_arq_lista']['tmp_name']) && $_FILES['frm_arq_lista']['error'] == 0) {
      //upload OK

      $listaUsuarios = file($_FILES['frm_arq_lista']['tmp_name']);
      
      if (count($listaUsuarios) > 0) {
        //percorre o arquivo da lista, inscrevendo os usuarios
        foreach($listaUsuarios as $k=>$linha) {
          $linha = trim($linha);
          list($nomePessoa,$nomUser,$senha,$mail) = explode(",",$linha);
          if (empty($nomUser) || empty($senha) || empty($mail)) {
            //faltaram dados para os usuarios
            echo '<div>Usu&aacute;rio da linha '.($k+1).' com dados incompletos. N&atilde;o foi salvo.</div>';
          }
          else {
            //dados completos, agora so tira as aspas, que eventualmente podem estar
            //no inicio e fim de cada campo
            $nomePessoa = trim($nomePessoa,"\"' ");
            $nomUser = trim($nomUser,"\"' ");
            $senha = trim($senha,"\"' ");
            $mail = trim($mail,"\"' ");

            if (!existeUsuario($nomUser,0)) {
              //OK: usuario nao existe            
              
              //salva a pessoa

              $sucesso = cadastroBasico($nomePessoa,$nomUser,$senha,$mail);
              

              if ($sucesso) {
                  echo "cod_pessoa";
                  
                //obtem o COD_PESSOA recem criado  
                $codPessoa = mysql_insert_id();
               echo $codPessoa;
                //agora salva o novo aluno
                $sucesso = Aluno::criaNovoAluno($codPessoa);
                 echo "B";
                if ($sucesso) {
                 echo "C";
                  //obtem o COD_AL recem criado  
                  $codAl = mysql_insert_id();
                
                  //agora salva a inscricao (pendente)
                  $aluno = new Aluno($codAl);
                  //o segundo  argumento serve para dizer que o usuario nao confirmou ainda
                  //o terceiro argumento serve para indicar que o administrador ja aceitou previamente 
                  $sucesso = $aluno->inscrever($_SESSION['codInstanciaGlobal'],0,1);
                  
                  if ($sucesso) {
                     echo "D";
                    //sucesso! usuario inscrito
                    echo '<div>Usu&aacute;rio da linha '.($k+1). '('.$nomUser.','.$senha.','.$mail.') salvo e inscrito com sucesso!</div>';  

                    //envia o email pedindo confirmacao do usuario
                    $nivel = getNivelAtual();
                    $codInstanciaNivel = getCodInstanciaNivelAtual();      
                    mandaMailConfirmarInscricao($nivel,$codInstanciaNivel,$codPessoa,$codAl,$nomUser,$senha,$mail);
                  }
                  else {

                    echo '<div>Usu&aacute;rio da linha '.($k+1). '('.$nomUser.','.$senha.','.$mail.'): erro ao fazer a inscri&ccedil;&atilde;o.</div>';       

                  }          
                }
              }
              else {

                echo '<div>Usu&aacute;rio da linha '.($k+1). '('.$nomUser.','.$senha.','.$mail.'). Erro ao salvar a pessoa.</div>';              

              }      
            }
            else {
              //erro: usuario ja existe

              echo '<div>Usu&aacute;rio da linha '.($k+1). '('.$nomUser.','.$senha.','.$mail.'). Erro: nome de usu�rio j� existe.</div>';

            }            
          }          
        }
      }
      else {

        echo '<div>Lista de usu&aacute;rios vazia.</div>';  

      }
      
      echo '<a href="recursos_fixos.php">Voltar</a>';      
    }
    else {
    	//erro no upload
      echo '<div>Houve um erro ao fazer o upload do arquivo. Por favor tente novamente.</div>';
      echo '<br><br><a href="inscricao.php?acao=A_carregaListaUsuarios">Voltar</a>';         	
    }
 
    echo '</div>';    
    
  break;
  
  case 'A_enviar_mail_pedindo_confirmacao':
    //re-envia email ao usuario pedindo sua confirmacao de inscricao
    
    //le o aluno
    $aluno = new Aluno($_REQUEST['COD_AL']);
    $aluno->buscaPessoa($_REQUEST['COD_PESSOA']);

    //envia o mail pedindo a confirmacao    
    $nivel = getNivelAtual();
    $codInstanciaNivel = getCodInstanciaNivelAtual();          
    $sucesso = mandaMailConfirmarInscricao($nivel, $codInstanciaNivel, $aluno->COD_PESSOA, $aluno->valorChavePessoa, $aluno->USER_PESSOA, $aluno->SENHA_PESSOA, $aluno->EMAIL_PESSOA);
    
    echo '<div align="center">';
    /*
    if ($sucesso) {
      echo '<span>Email enviado com sucesso.</span><br>';
    }
    else {
      echo '<span>Erro ao enviar email.</span><br>';    	
    }*/
    
    echo '<br><a href="inscricao.php?acao=A_inscricoesPendentes">Voltar</a>';    
    echo '</div>';
    
  break;
       
}

?>

</body>
</html>
