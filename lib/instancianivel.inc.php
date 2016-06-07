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
 * 
 *
 */
class InstanciaNivel extends RDCLRow {
  //obbjeto nivel
  var $nivel;
  //1:n => valor chave é o valor na instancia na tabela de instancia
  //n:m => valor chave é o codigo da instancia na tabela de relacionamento com o nivel pai!!!
  var $codInstanciaNivel;
  //codigo global usado nas juncoes com as tabelas de menu e recursos!
  var $codInstanciaGlobal;
  //array de objetos nivel que são subniveis desta instancia
  var $subNiveis;

  function InstanciaNivel($nivel,$valorChave) {
    $this->nivel = $nivel;
    $this->codInstanciaNivel = $valorChave;
    //muda de acordo com o relacionamento com o nivel pai 
    switch($this->nivel->tipoRelacionamentoComNivelPai) {
      case 1: //1:n, basta chamar rdclrow
        $chaveBusca = $this->codInstanciaNivel;
        break;
      case 2: //n:m, entao precisamos buscar a instancia de acordo com a  PK de relacionamento. 
        $relacionamento = $this->getRelacionamentoComPai();
        $chaveFraca = $this->nivel->nomeFisicoPK;
        $chaveBusca = $relacionamento->$chaveFraca;
        break;
    }
    $this->RDCLRow($this->nivel->nomeFisicoTabela,$this->nivel->nomeFisicoPK,$chaveBusca );
    //Usa campos padrao nome e abreviatura para os objetos, 
    //independentemente do nome físico na tabela do nivel
    $campoAbreviatura = $nivel->nomeFisicoCampoAbreviatura;
    if (!empty($this->$campoAbreviatura)){ $this->abreviatura = $this->$campoAbreviatura;    }
    $campoNome = $nivel->nomeFisicoCampoNome;
    if (!empty($this->$campoNome)) {  $this->nome = $this->$campoNome;   }
    //INICIALIZA A INSTANCIA GLOBAL.
    //SEMPRE VERIFICA SE A INSTANCIA GLOBAL EXISTE. SE NAO EXISTIR, CRIA UMA
    $this->getCodInstanciaGlobal();
  }
  /*
   *
   */
  function getRelacionamentoComPai() {
    $relacionamento = new RDCLRow($this->nivel->nomeFisicoTabelaRelacionamento,$this->nivel->nomeFisicoPKRelacionamento,$this->codInstanciaNivel);
    
    return $relacionamento;
  }
  /*
   * Retorna os subniveis, verificando se há um nivel específico para a instância
   */
  /*DEPRECATED, metodo colocado na classe Nivel
  function getSubNiveis() {
    //verifica se esta instancia tem o proximo nivel configurado
    if (!empty($this->codProximoNivel)) { 
      return new Nivel($this->codProximoNivel);
    }
    //Busca os filhos
    else {
      return new RDCLQuery('Select * from nivel Where codNivelPai='.$this->nivel->codNivel);         
    }
  }
  */
  
  /*
   * Retorna a instancia global. cria, se nao existir.
   */
  function getCodInstanciaGlobal() {
    $obj = new InstanciaGlobal();
	
    $obj->getByInstanciaNivel($this->nivel->codNivel,$this->codInstanciaNivel);
    //ok, já tem instancia global
    if (!empty($obj->codInstanciaGlobal)) {
      $this->codInstanciaGlobal = $obj->codInstanciaGlobal;
    }
    //ainda nao tem, vamos criar
    else {
      if ($this->createInstanciaGlobal()) {
        $this->getCodInstanciaGlobal(); //Chama recursivamente para buscar o codigo gerado
      }
    }

  }
  /*
   * Retorna a tabela que contem a pk de fato, se eh a tabela que implementa o nivel (1:N)
   * ou entao a chave de seu relacionamento com o pai (N:M)
   */
  function getTablePK($obj="") {
    if (empty($obj)) { $obj = $this->nivel;} //para usar chamada estatica
    //o nome da tabela do nivel como é o relacionamento dele com o pai (avo do subnivel)
    switch($obj->tipoRelacionamentoComNivelPai) {
      case 1:  $tabPK= $obj->nomeFisicoTabela; break;//O relacionamento com o nivel pai deste era 1:n
      case 2:  $tabPK= $obj->nomeFisicoTabelaRelacionamento; break;//O relacionamento com o nivel pai deste era n:m
      default: $tabPK= $obj->nomeFisicoTabela; //padrao é ser a propria tabela, como no caso 1:N
    }
    return $tabPK;
  }
  /*
   *  //itens de Menu disponíveis para esta instância
   */
  function getMenu() {
    if (!empty($this->codInstanciaGlobal)) {
      $sql  = " SELECT nomeMenu,urlMenu,descricaoMenu,imagem ";
                //,M.tipoAcesso 
               $sql .= " FROM menuinstancia MI INNER JOIN menu M ON (MI.codMenu=M.codMenu)";
      $sql .= " WHERE MI.codInstanciaGlobal=".$this->codInstanciaGlobal;
      //para usarios nao logados, apenas recursos que permitem acesso publico
      if (empty($_SESSION["COD_PESSOA"])) $sql.= " AND M.tipoAcesso='publico'";
      $sql .= " ORDER BY ordemInstancia,ordem";
      return new RDCLQuery($sql);
    }
  }
 /**
  *  Intens de Menus Particulares disponiveis para esta instância
  */     
  function getMenuParticulares(){

   if (!empty($this->codInstanciaGlobal)) {
      $sql  = " SELECT nomeMenu,urlMenu,descricaoMenu,imagem FROM menuparticular M ";
      $sql .= " WHERE M.codInstanciaGlobal=".$this->codInstanciaGlobal." AND ativo='1'";
      if (empty($_SESSION["COD_PESSOA"])) { $sql.= " AND M.tipoAcesso='publico'"; }
      $sql .= " ORDER BY ordem";
      
      return new RDCLQuery($sql);
    }
  }
  /*
   * Instancia de nivel pai
   */
  function getPai() {
    $nivelPai = $this->nivel->getNivelAnterior(); 
    if (empty($nivelPai->codNivel)) { return 0;  }
    
    $chavePai = $nivelPai->getPK();
    //echo 'chave pai'.$chavePai;
    switch($this->nivel->tipoRelacionamentoComNivelPai) {
      //1:n, entao essa instancia já tem o chave do pai como FK
      case 1: $obj= $this;  break;
      //n:m, busca a chave do pai na tabela de relacionamento
      case 2: $obj= $this->getRelacionamentoComPai(); break;
    }
    
    $instanciaPai = new InstanciaNivel($nivelPai,$obj->$chavePai);
    
    return $instanciaPai;
  }
  /*
   * Busca os instancias superiores na hierarquia
   */
  function getInstanciasHierarquiaPai() {
    //$niveis = $this->nivel->getHierarquiaPai();
    //note($niveis); die();
  }
  /*
   * Se houver abreviatura mostra, se nao mostra nome completo
   */
  function getAbreviaturaOuNome($obj="") {
    if (empty($obj)) { $obj = $this; }
    if (!empty($obj->abreviatura)) { 
      return $obj->abreviatura;  
	    //return $obj->nome;  //rever melhor maneira de fazer,ao invés de lista abreviatura listar nome
    }
    else {  
      return $obj->nome; 
    }
  }
  /*
   * Se for o caso, mostra o nome do pai tambem, é isto que a diferencia de getAbreviaturaOuNome
   * sobre a instancia, se houver abreviatura mostra, se nao mostra nome completo, assim como getAbreviaturaOuNome
   */
  function getAbreviaturaOuNomeComPai($separador=SEPARADOR,$obj="") {
    if (empty($obj)) { $obj = $this; } 
    $ret = "";
    if ($obj->nivel->mostraNomeNivelPai) {
      $pai = $obj->getPai(); 
      $ret .= $pai->getAbreviaturaOuNome().$separador;
    }
    if (!empty($obj->abreviatura)) { 
      $ret .= $obj->abreviatura;  
    }
    else {  
      $ret .= $obj->nome; 
    }
    return $ret;
  }
  /*
   * Cria uma instancia global para esta instancia
   */
  function createInstanciaGlobal() {
    //Primeiro verifica se esta instancia já nao possui instancia global
    if (!empty($this->codInstanciaGlobal)) { return ''; }
    $sql = "INSERT INTO instanciaglobal (codNivel,codInstanciaNivel) VALUES ";
    $sql.= "(".$this->nivel->codNivel.",".$this->codInstanciaNivel.");";
    mysql_query($sql);
    return (!mysql_errno());
  }
  

  /*
   *  Devolve a lista de parâmetros específicos desta instancia, que permitem personalizar a plataforma
   */
  function getConfiguracoesGerais() {
    return new RDCLQuery("SELECT * from configuracaogeralinstancia Where codInstanciaGlobal=".quote_smart($this->codInstanciaGlobal));  
  }
  /*
   *  Recurso que será carregado inicialmente
   */
  function getRecursoPadrao() {
    $sql = " SELECT urlMenu FROM menu M INNER JOIN configuracaogeralinstancia CGI ON (M.codMenu=CGI.codMenuInicial)". 
           " WHERE CGI.codInstanciaGlobal=".quote_smart($this->codInstanciaGlobal);
    //echo $sql;
    $result = mysql_query($sql);
    //echo mysql_error();
    $linha = mysql_fetch_assoc($result);
    return $linha['urlMenu'];
  }
  /*
   *  parâmetros específicos desta instancia, que permitem personalizar a plataforma
   */  
  function iniciaMenu() {
    $sql = "INSERT INTO menuinstancia (codMenu,codInstanciaGlobal) ".
         " SELECT M.codMenu, ".$this->codInstanciaGlobal. 
         " FROM menu M";
 
    mysql_query($sql);
    return (!mysql_errno());
  }

  /*
   *  Verifica se a instancia relaciona alunos e professores formalmente ou informalmente
   *  Em principio essa funcionalidade nao esta sendo usada pelo nucleo, mas bastaria colocar a flag e testar
   *  (o metodo em si ja ficou implementado)      
   */  
  function relacionaPessoas() {
    /* o nivel relaciona pessoas */  
    if ($this->nivel->tipoNivel==NIVEL_RELACIONA)  {
      return true;
    }
    /* Este nivel é um subgrupo, relaciona pessoas de outros niveis*/
    else if ($this->nivel->tipoNivel==NIVEL_SUBGRUPO) {
      return true;
    }    
    /* Neste nivel a instancia pode eventualmente relacionar pessoas */
    else if ($this->nivel->tipoNivel==NIVEL_AGRUPA_RELACIONA && $this->relacionaPessoasFormalmente) {
      return true;
    }
    /* relacionamento informal */
    else if ( $this->nivel->tipoNivel==NIVEL_COMUNIDADE) {
      return true;
    }
    else {
      return false;
    }
  }            
}
?>