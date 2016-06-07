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
 * Niveis e instancias pelos quais o usuario ja navegou
 */

class Navegacao {

  var $pilha;

  function Navegacao($nivel,$instancia) {

    $this->pilha = array(); //aquilo que o usuario navegou
    /* possivel otimizacao: guardar apenas codigo e nome, 
       pois a classe de controle é quem usa os objetos */
    $this->push($nivel,$instancia);

  }

  function push($nivel,$instancia) {
    $this->pilha[] = array( "nivel" => $nivel, "instancia" => $instancia);
  }

  function pop() {
    $ret = $this->pilha[$this->getNumTopo()];
    if (count($this->pilha) >1 )  { 
      array_pop($this->pilha); 
    }
    return $ret;
  }

  function getTopo() {
    return $this->pilha[$this->getNumTopo()];
  }

  function getNumTopo() {
    return count($this->pilha)-1;
  }

  function getcodNivelAtual() {
    return $this->pilha[$this->getNumTopo()]["nivel"]->codNivel;
  }

  function getNivelAnterior() {
    return $this->pilha[$this->getNumTopo()-1]["nivel"];
  }
  function getInstanciaNivelAnterior() {
    return $this->pilha[$this->getNumTopo()-1]["instancia"];
  }

  function getNivelAtual() {
    return $this->pilha[$this->getNumTopo()]["nivel"];
  }
  function getInstanciaNivelAtual() {
    return $this->pilha[$this->getNumTopo()]["instancia"];
  }
  /*
   * primeiro nivel na navegacao
   */
  function getNivelInicial() {
    return $this->pilha[0]['nivel'];
  }

}

?>
