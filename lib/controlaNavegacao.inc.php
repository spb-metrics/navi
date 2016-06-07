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

  /**
 * Controle da navegacao  (talvez otimizar para instanciar os objetos on the fly ao inves de guardar na sessao
 */
function controlaNavegacao(&$nivel,&$instanciaNivel,$userRole='',&$navegacao) {
  

  //Navegaao por combo unico que serve tanto para administrador geral, administrador de nivel e publico
  if ( ($_REQUEST["voltar"]) && (!empty($navegacao)) ) { //estamos voltando, e dando pop na navegacao
    $navegacao->pop();
    $nivel = $navegacao->getNivelAtual();
    $instanciaNivel = $navegacao->getInstanciaNivelAtual();
  }
  else if ($_REQUEST["seguirAdiante"] && !empty($navegacao) ){ 
    if ($navegacao->getcodNivelAtual()!=$_REQUEST["codNivel"])  { 
      $nivel = new Nivel($_REQUEST["codNivel"]); 
      $instanciaNivel = new InstanciaNivel($nivel,$_REQUEST["codInstanciaNivel"]);
      $navegacao->push($nivel,$instanciaNivel);
    }
    else { //aqui o usuario deve ter dado refresh, entao vamos manter o nivel atual
      $nivel = $navegacao->getNivelAtual();
      $instanciaNivel = $navegacao->getInstanciaNivelAtual();
    }
  }
  else if ($_REQUEST["move"]) {  //quando o usuario quiser voltar para um nivel/instancia ja navegados

  }
  else if (!empty($navegacao)) {  //o usuario deve ter dado refresh e nao estava no seguir adiante
    $nivel = $navegacao->getNivelAtual();
    $instanciaNivel = $navegacao->getInstanciaNivelAtual();
  }
  //o administrador está entrando em uma comunidade
  else if (!empty($_REQUEST["iniciarNavegacao"]) && $userRole==ADMINISTRADOR_GERAL && !empty($_REQUEST["codNivel"]) ) {  
      $nivel = new Nivel($_REQUEST["codNivel"]); 
      $instanciaNivel = new InstanciaNivel($nivel,$_REQUEST["codInstanciaNivel"]);
      $navegacao = new Navegacao($nivel,$instanciaNivel);  
  }
  else {
//estamos entrando pela primeira vez!
    $nivel = new Nivel(NIVEL_INICIAL); 
    $instanciaNivel = new InstanciaNivel($nivel,INSTANCIA_INICIAL);
    $navegacao = new Navegacao($nivel,$instanciaNivel);
   
  }
  /**
  //Guarda o logotipo, pois pode ser usado pelos subniveis
  if ($instanciaNivel->codArquivoLogotipo) { 
    $_SESSION['codArquivoLogotipo'] = $instanciaNivel->codArquivoLogotipo; 
  }
  */
}



?>