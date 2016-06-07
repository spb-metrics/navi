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
 * short desc: Classe Professor
 *
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/Fábio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de Administração/UFRGS 2005
 */
define('TABELAPROFESSOR','professor');
define('ALIAS_TABELA_PROFESSOR','PROF');
//nome de subpapel padrão
define('DESCRICAO_PAPEL_PROFESSOR','Professor');
define('PK_SUBPAPEL_PROFESSOR','codTipoProfessor'); //papel do professor vinculado a autoridade professor ("administrador nivel basico")
define('PK_PROFESSOR','COD_PROF');
define('TABELA_SUBPAPEL_PROFESSOR','tipo_professor'); //papel do professor vinculado a autoridade professor ("administrador nivel basico")


class Professor extends Pessoa {
  /*
   * Construtor
   */
  function Professor($codProfessor="") {
    $this->Pessoa();
    $this->tabela = $this->getTabela();
    $this->userRole = PROFESSOR;
    $this->valorChavePessoa = $codProfessor;
    /* esses atributos variam pelo nivel e nao sao mais necess rios
    $this->chavePessoa      = $this->getPKRelacionamento();
    $this->tabelaRelacionamento = $this->getTabelaRelacionamento();
    */
  }
  /*
   * Retorna a tabela fisica de instancias do professor
   */
  function getTabela() {
    return TABELAPROFESSOR;
  }
  /*
   * Retorna a tabela fisica de instancias do professor
   */
  function getPK() {
    return PK_PROFESSOR;
  }

  /*
   * Retorna a tabela fisica com os subpapéis (papeis subordinados as autoridades)
   */
  function getTabelaSubPapel() {
    return TABELA_SUBPAPEL_PROFESSOR;
  }
  /*
   * Retorna a PK fisica de relacionamento com os subpapéis
   */
  function getPKSubPapel() {
    return PK_SUBPAPEL_PROFESSOR;
  }

  /*
   * Devolve a tabela fisica de relacionamento com os professores para certo nivel.
   * 
   */
  function getTabelaRelacionamento($nivel) {
    return $nivel->nomeFisicoTabelaRelacionamentoProfessores;
  }
  /*
   * Devolve a ok fisica de relacionamento com os professores para certo nivel.   
   */
  function getPKRelacionamento($nivel) {
    return $nivel->nomeFisicoPKRelacionamentoProfessores;
  }
  /**
   *  retorna o nome pk papel exemplo:codTipoProfessor
   *  obs:essa função deve substituir a função 'getPKSubPapel'   
   */   
   function getPKPapel($nivel="") {
    if (empty($nivel)) { $nivel = $this->nivelRelacionamento;  }
    return $nivel->nomeFisicoPKPapelAdministradores;
  }
  /*
   * Define o alias da tabela
   */
  function getAlias() {
    return ALIAS_TABELA_PROFESSOR;
  }

  function iconeTipoProfessor($codTipoProfessor,$descTipoProfessor) {

    return "<img src='tipoProfessor.php?codTipoProfessor=".$codTipoProfessor."' title='".$descTipoProfessor."'>";
  }
  
  /**
  /**
   * Retorna se o aluno ja esta inscrito em determinada instancia
   */       
  function estaInscrito($codInstanciaGlobal) {
    $sql = "SELECT * FROM InscricoesProfessor WHERE ".
           "codInstanciaGlobal=".quote_smart($codInstanciaGlobal)." AND ".
           $this->getPKRelacionamento()."=".quote_smart($this->valorChavePessoa);
    $result = new RDCLQuery($sql);
    //echo $sql; echo mysql_error();
    return !empty($result->records);    
  }
  
  /**
   * Inscreve o professor, registrando em InscricoesProfessor  
   */       
  function inscrever($codInstanciaGlobal) {
    $tabInscricao = "InscricoesProfessor";      
    $sql = "INSERT INTO ".$tabInscricao." (codInstanciaGlobal,".$this->getPKRelacionamento().",confirmada,momentoInscricao) ". 
           "VALUES (".$codInstanciaGlobal.",".$this->valorChavePessoa.",0,".time().")";    
    mysql_query($sql);
    //echo $sql; echo mysql_error();              
    return (mysql_errno() == 0);
  }

  /**
   * Liberar a inscricao do professor
   */       
  function liberarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel) {
  
    //ativa o usuario, se nao tiver ativo ainda 
    parent::liberarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel);

    //seta confirmada = 1 em InscricoesProfessor
    $tabInscricao = "InscricoesProfessor";      
    $sql = "UPDATE ".$tabInscricao." SET confirmada=1 ".
           "WHERE codInstanciaGlobal=".$codInstanciaGlobal.
           " AND ".$this->getPKRelacionamento()."=".$this->valorChavePessoa;    
    mysql_query($sql);    

    //echo $sql; echo mysql_error();
    
    if (mysql_errno()) return 0;

    //se o nivel possuir relacionamento com alunos, entao inscreve a pessoa 
    //inserindo um registro na tabela de relacionamento
    if ($nivel->getPKRelacionamentoProfessores() != "")
      return $this->inscreverInstancia($nivel, $codInstanciaNivel);
    else
      return 1;
  }
  
  /**
   * Rejeita a inscricao  
   */       
  function rejeitarInscricao($codInstanciaGlobal,$deletar=0) {
    $tabInscricao = "InscricoesProfessor";      
    
    if ($deletar) {
      //deleta o registro da inscricao
      $sql = "DELETE FROM ".$tabInscricao.
             " WHERE codInstanciaGlobal=".$codInstanciaGlobal.
             " AND ".$this->getPKRelacionamento()."=".$this->valorChavePessoa;      
    }
    else {
      //faz update, marcando confirmada = -1
      $sql = "UPDATE ".$tabInscricao." SET confirmada=-1 ".
             "WHERE codInstanciaGlobal=".$codInstanciaGlobal.
             " AND ".$this->getPKRelacionamento()."=".$this->valorChavePessoa;
    }
    mysql_query($sql);    

    //echo $sql; echo mysql_error();
    
    if (mysql_errno()) 
      return 0;
    else
      return 1;    
  }
  
  /** essas funcoes foram passadas para a class pessoa.
   * Se o nivel possuir relacionamento com alunos, inscreve o aluno no relacionamento  
   */        
 /* function inscreverInstancia($nivel,$codInstanciaNivel,$codTipoProfessor='') {
    $sql = "INSERT INTO ".$nivel->getTabelaRelacionamentoProfessores()." (".$nivel->getPK().",".$nivel->getPKRelacionamentoProfessores().", codTipoProfessor ) ". 
           "VALUES (".$codInstanciaNivel.",".$this->valorChavePessoa.",".$codTipoProfessor.")";
    mysql_query($sql);
    //echo $sql.mysql_error();
    return (mysql_errno() == 0);
  }*/
  /**
   * retira professor da instancia Atual
   */        
 /* function retirarInstancia($nivel,$codInstanciaNivel) {
    $sql = "DELETA FROM ".$nivel->getTabelaRelacionamentoProfessores()." WHERE ".$nivel->getPK()."=".$codInstanciaNivel." AND". 							$nivel->getPKRelacionamentoProfessores()."=".$this->valorChavePessoa; 
    mysql_query($sql);
    return (!mysql_errno());
  }*/
 
  /**
   * Deleta o professor
   */     
  function deleta() {
    if (!isset($this->valorChavePessoa)) 
      return 0;

    //deleta a pessoa
    if (parent::deleta()) {
  
      //deleta o prefessor
      $sql = "DELETE FROM ".$this->getTabela(). " WHERE ".$this->getPKRelacionamento()."=".$this->valorChavePessoa;
      mysql_query($sql);
  
      //echo $sql; echo mysql_error();
      
      return (mysql_error() == 0);
    }
    else
      return 0;
  }
    
  /**
   * Cria novo registro de professor
   */     
  function criaNovoProfessor($codPessoa){
  	$sql=" INSERT INTO ".$this->getTabela()."(COD_PESSOA) VALUES (".quote_smart($codPessoa).")";
        mysql_query($sql);
  
  	  $this->valorChavePessoa=mysql_insert_id();
  
  	  return (!mysql_errno());
  }
  /**
   * listar todos os alunos de um determnado professor que estejam dentro de uma instancia turma ou outro nivel sistemico,
   * no qual o professor tambem esteja
   * (os que estão dentro de dentro da comunidade não lista) .
   */    
  function meusAlunos($nivel,$codInstanciaAtual,$mostraPassado){ 
   
    $niveis = Nivel::getNiveisRelacionamentoFormal();
    
  	$projecao = ' SELECT DISTINCT P.NOME_PESSOA,P.COD_PESSOA FROM pessoa P ';
    $projecao.= ' INNER JOIN '.TABELA_ALUNO.' AL ON ( P.'.PK_PESSOA.' = AL.'.PK_PESSOA.' )';
    
    foreach($niveis->records as $n) {
      $sql.=$projecao;
      $sql.= ' INNER JOIN '.$n->nomeFisicoTabelaRelacionamentoAlunos.' REL ';
      $sql.= ' ON (REL.'.Aluno::getPKRelacionamento($n).'=AL.'.PK_ALUNO.')';
      
      $sql.= ' WHERE REL.'.Nivel::getPK($n).' IN ';
      $sql.= ' ( SELECT '.Nivel::getPK($n).' FROM '.$n->nomeFisicoTabelaRelacionamentoProfessores.' REL ';
      $sql.= ' WHERE REL.'.Professor::getPKRelacionamento($n).'='.$this->valorChavePessoa.')';
      $sql.= ' UNION '; 
    }
    $sql.=rtrim($sql, ' UNION ');
    $sql.=' ORDER BY NOME_PESSOA';
    /*
    $PK=$nivel->nomeFisicoPK;
    $tabelaAluno=Aluno::getTabela();
  
  	$sql="SELECT DISTINCT P.NOME_PESSOA,P.COD_PESSOA FROM pessoa P".
  		 " INNER JOIN ".$tabelaAluno." A ON (P.COD_PESSOA=A.COD_PESSOA)".
   		 " INNER JOIN ".$tabelaAlunoturma." AT ON (A.".$PkAluno."=AT.".$PkAluno.")".
  		 " WHERE ";
  
  	$instancias=$this->getNiveis('',$nivel);
  	
  	foreach($instancias->records as $codInstanciaNivel) {
  	  //echo '<PRE>';   print_r($codInstanciaNivel);
  		$sql.="AT.".$PK."=". $codInstanciaNivel->$PK." OR ";


      $tabelaAlunoturma=$nivel->nomeFisicoTabelaRelacionamentoAlunos;
  	  $PkAluno=$nivel->nomeFisicoPKRelacionamentoAlunos;
    }
  	
    $sql=substr($sql,0,-3);
    */
    //die($sql);
    
    
    $obj = new RDCLQuery($sql);
    return $obj;
  }
  
  /*
   * Verifica se a flag de permissao ao professor para administrar esta ativada para este papel
   */
   function podeSerAdministrado($codPapel) {
     
     $sql='select professorPodeGerenciar from '.TABELA_SUBPAPEL_PROFESSOR.' where '.PK_SUBPAPEL_PROFESSOR.'='.$codPapel.' LIMIT 0,1';
    
     $result = mysql_query($sql);
     $papel = mysql_fetch_object($result);
     
     return $papel->professorPodeGerenciar;
   }
}

?>