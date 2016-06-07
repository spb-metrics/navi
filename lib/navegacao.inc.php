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

/* 
 * Niveis e instancias pelos quais o usuario ja navegou
 */

class Navegacao {

  var $pilha;

  function Navegacao($nivel,$instancia) {

    $this->pilha = array(); //aquilo que o usuario navegou
    /* possivel otimizacao: guardar apenas codigo e nome, 
       pois a classe de controle � quem usa os objetos */
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
