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
 * short desc: Administrador de algum  nivel sistemico
 *             os metodos sao uma interface para pessoa
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/Fábio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de Admnistração/UFRGS 2005
 */

class AdministradorNivel extends RDCLRow {
  //FK COD_ADM
  var $codAdm;
  /*
   * Construtor
   */
  function AdministradorNivel($codAdministradorNivel='') {
    $this->codAdm = $codAdministradorNivel;
  }
  /*
   * Retorna a QUERY de todos os niveis relacionados a esta pessoa, eventualmente com seletividade
   */
  function getNiveis($where="") {
    $sql = " SELECT * FROM administradornivel Where COD_ADM=".quote_smart($this->codAdm).$where;
    $obj = new RDCLQuery($sql);
    return $obj;
  }
  /*
   *  Retorna o nivel inicial, padrao
   */
  function getNivelInicial($mode="nivel") {
    //$obj = $this->getNiveis(" AND ativo=1");
    $obj = $this->getNiveis();

    $instance = new InstanciaGlobal($obj->records[0]->codInstanciaGlobal);
    $nivel = new Nivel($instance->codNivel);

    if ($mode=="nivel") {
      return $nivel;
    }
    else if ($mode=="instancia") {
      $instanciaNivel = new InstanciaNivel($nivel,$instance->codInstanciaNivel);
      return $instanciaNivel;
    }
  }
  /*
   * Retorna a instancia inicial
   */
  function getInstanciaNivelInicial() {
    return $this->getNivelInicial("instancia");
  }
  /*
   * Retorna os objetos de todos os niveis administrados
   */
  function getTodosNiveis() {
    $retorno = array();
    //faz a consulta para buscar no banco
    $niveisAdm = $this->getNiveis();

    foreach($niveisAdm->records as $n) {
      $global = new InstanciaGlobal($n->codInstanciaGlobal);
      $nivel = new Nivel($global->codNivel);
      $instanciaAdm = new InstanciaNivel($nivel,$global->codInstanciaNivel);
      $retorno[] = $instanciaAdm;
    }
    
    return $retorno;
  }
  /*
   * Retorna as comunidadades onde o administrador está cadastrado
   */
  function getComunidades($flagPendente="") {
  
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    
    $sql = " Select ".ADM_NIVEL." AS userRole,C.* from ".$nivelComunidade->nomeFisicoTabela." C";
    $sql.= " INNER JOIN instanciaglobal IGLOBAL ON (C.".$pkComunidade."=IGLOBAL.codInstanciaNivel)";
    $sql.= " INNER JOIN administradornivel ADMNIVEL ON (ADMNIVEL.codInstanciaGlobal=IGLOBAL.codInstanciaGlobal)";
    $sql.= " Where ADMNIVEL.COD_ADM=".quote_smart($this->codAdm)."  AND IGLOBAL.codNivel=".$nivelComunidade->codNivel;
    //usado para ver as comunidades onde meu ingresso está pendente
    if ($flagPendente) {
      $sql .=" and ADMNIVEL.ativo=0";
    }

    return new RDCLQuery($sql);
  }
  /*
   * Retorna as comunidadades onde o administrador NAO está cadastrado
   */
  function getComunidadesAusente() {
  
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    
    $sql = " Select C.* from ".$nivelComunidade->nomeFisicoTabela." C";
    $sql.= " INNER JOIN instanciaglobal IGLOBAL ON (C.".$pkComunidade."=IGLOBAL.codInstanciaNivel AND IGLOBAL.codNivel=".$nivelComunidade->codNivel.")";
    $sql.= " LEFT OUTER JOIN administradornivel ADMNIVEL ON (ADMNIVEL.codInstanciaGlobal=IGLOBAL.codInstanciaGlobal AND ADMNIVEL.COD_ADM=".quote_smart($this->codAdm).")";
    $sql.= " Where ADMNIVEL.COD_ADM IS NULL"  ;

    return new RDCLQuery($sql);
  }
  /*
   * Inscreve o Administrador em uma Comunidade
   */
  function inscreverComunidade($codComunidade) {
  
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    $instanciaComunidade = new InstanciaNivel($nivelComunidade,$codComunidade);
    
    $sql = " INSERT INTO administradornivel (COD_ADM,codInstanciaGlobal) ";
    $sql.= " VALUES (".$this->COD_ADM.",".$instanciaComunidade->codInstanciaGlobal.")";

    return (!mysql_errno());
  }
  /*
   * Libera o administrador para certa comnidade pendente
   */
  function liberarPessoaComunidade($codInstanciaGlobal) {
    $sql = "UPDATE administradornivel SET ativo=1 " ;
    $sql.= " WHERE codInstanciaGlobal=".$codInstanciaGlobal." AND COD_ADM=".$this->codAdm;

    mysql_query($sql);

    return (!mysql_errno());
  }
  /*
   * Libera o administrador para certa instancia pendente
   */
  function liberarPessoaInstancia($codInstanciaGlobal) {
    return $this->liberarPessoaComunidade($codInstanciaGlobal);
  }
  
  //==========================================================================================================
  /*
   * insere registro de adm
   */
  function criaAdmGeral($codPessoa, $codAcesso){
  $sql=" INSERT INTO administrador (COD_PESSOA, COD_NIVEL_ACESSO) Values(".$codPessoa.",".$codAcesso.")";
  mysql_query($sql);
  $this->codAdm=mysql_insert_id();
  return (!mysql_errno());
  }
  /**
  *  essa função lista todos os administraores Gerais
  */ 
  function listaAdmGeral(){
  $sql="SELECT * FROM pessoa P".
        " INNER JOIN administrador A ON (P.COD_PESSOA=A.COD_PESSOA)".
        "WHERE A.COD_NIVEL_ACESSO=1";
  
  return mysql_query($sql);
  }
  /** essa função verifica se ha registro de administrador e retorna o codAdm*/
  function isAdm($codPessoa){
    $sql= " SELECT COD_ADM FROM administrador WHERE COD_PESSOA=".$codPessoa;
    $rsCon=mysql_query($sql);
    
    if($linha = mysql_fetch_array($rsCon))
      if(!empty($linha["COD_ADM"]))
        $this->codAdm=$linha["COD_ADM"];
      
      return $linha["COD_ADM"];
    
  }
  /**
  * lista todos os administradores do nivel solicitado.
  */   
  function listaAdmNivel($codInstanciaGlobal){
  $sql= "SELECT * FROM pessoa P".
        " INNER JOIN administrador A ON (P.COD_PESSOA=A.COD_PESSOA)".
        " INNER JOIN administradornivel AN ON (A.COD_ADM=AN.COD_ADM)".
        " WHERE codInstanciaGlobal=".$codInstanciaGlobal;
  
  return mysql_query($sql);
  }
  /*
   * coloca adm como adm nivel
   */
  function criaAdmNivel($codInstanciaGlobal){
  $sql=" INSERT INTO administradornivel (COD_ADM , codInstanciaGlobal) VALUES (".$this->codAdm .",".$codInstanciaGlobal.")";
  
  mysql_query($sql);
  return (!mysql_errno());
  }
  /*
   * retira status de administrador deste nível/instancia
   */
  function deletaAdmNivel($codInstanciaGlobal){
  $sql="DELETE FROM administradornivel WHERE COD_ADM=".$this->codAdm." AND codInstanciaGlobal=".$codInstanciaGlobal;
  mysql_query($sql);
  return (!mysql_errno());
  }
  
  /*
   * retira registro de administrador
   */
  function deletaAdmGeral($codPessoa){
  $sql="DELETE FROM administrador WHERE COD_PESSOA=".$codPessoa." AND COD_NIVEL_ACESSO=1";
  mysql_query($sql);
  return (!mysql_errno());
  }



}

?>