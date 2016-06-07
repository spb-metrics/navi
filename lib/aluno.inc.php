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
 * short desc: Classe Aluno
 *
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/Fßbio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de AdministraþÒo/UFRGS 2005
 */
define('TABELA_ALUNO','aluno');
define('ALIAS_TABELA_ALUNO','AL');
define('PK_ALUNO','COD_AL');
define('PK_SUBPAPEL_ALUNO','codTipoAluno'); //papel do aluno vinculado a autoridade aluno ("nao administrador")
define('TABELA_SUBPAPEL_ALUNO','tipo_aluno'); //papel do aluno vinculado a autoridade aluno ("nao administrador")

class Aluno extends Pessoa {
  /*
   * Construtor
   */
  function Aluno($codAluno='') {
    $this->Pessoa();
    $this->tabela = $this->getTabela();    
    $this->userRole = ALUNO; //definição do papel conforme config.php
    $this->campoParticipacaoPapel='alunoParticipa';    

    $this->valorChavePessoa = $codAluno;
    
    /* Estes atributos não são mais necessários, pois variam pelo nível
    $this->chavePessoa      = $this->getPKRelacionamento();
    $this->tabelaRelacionamento = $this->getTabelaRelacionamento();
    */
  }  
  /*
   * Retorna a tabela fisica de instancias do professor
   */
  function getPK() {
    return PK_ALUNO;
  }
  
  /*
   * Devolve a tabela fisica de instancias do aluno
   */
  function getTabela() {
    return TABELA_ALUNO;
  }
  /*
   * Devolve a tabela fisica de relacionamento com os alunos para certo nivel.
   * O padrao Ú O nivel de relacionamento
   */
  function getTabelaRelacionamento($nivel) {
    return $nivel->nomeFisicoTabelaRelacionamentoAlunos;
  }
  /*
   * Devolve a ok fisica de relacionamento com os aluno para certo nivel.
   * O padrao Ú O nivel de relacionamento
   */
  function getPKRelacionamento($nivel='') {
    if (empty($nivel)) { $nivel=getNivelAtual();}
    return $nivel->nomeFisicoPKRelacionamentoAlunos;
  }
  /**
   *  retorna o nome pk papel exemplo:codTipoAluno
   *  obs:essa funþÒo deve substituir a funþÒo 'getPKSubPapel', caso queira
   */
   function getPKPapel($nivel) {
    return $nivel->nomeFisicoPKPapelNaoAdministradores;
   }
  /*
   * Retorna a PK fisica de relacionamento com os subpapéis
   */
  function getPKSubPapel() {
    return PK_SUBPAPEL_ALUNO;
  }

  /*
   * Retorna a tabela fisica com os subpapéis (papeis subordinados as autoridades)
   */
  function getTabelaSubPapel() {
    return TABELA_SUBPAPEL_ALUNO;
  }
  /*
   * Define o alias da tabela
   */
  function getAlias() {
    return ALIAS_TABELA_ALUNO;
  }
  
  /**
   * Retorna se o aluno ja esta inscrito em determinada instancia
   */       
  function estaInscrito($codInstanciaGlobal) {
   	$instGlobal = new InstanciaGlobal($codInstanciaGlobal);  	
    $nivel = new Nivel($instGlobal->codNivel);

    $sql = "SELECT * FROM inscricoesaluno WHERE ".
           "codInstanciaGlobal=".quote_smart($codInstanciaGlobal)." AND ".
           $this->getPKRelacionamento($nivel)."=".quote_smart($this->valorChavePessoa);
    //die();
    $result = new RDCLQuery($sql);
    return !empty($result->records);    
  }
  
  /**
   * Inscreve o aluno, registrando em InscricoesAluno
   * usuarioConfirmou: se o usuario confirmou, possivelmente proveniente de uma lista pre-pronta
   * aceita: se o adm aceitou, possivelmente uma inscriþÒo que estß aberta para o p·blico        
   */       
  function inscrever($codInstanciaGlobal,$usuarioConfirmou=1,$aceita=0) {
    $tabInscricao = "inscricoesaluno";
   	$instGlobal = new InstanciaGlobal($codInstanciaGlobal);  	
    $nivel = new Nivel($instGlobal->codNivel);
  	
      
    $sql = "INSERT INTO ".$tabInscricao." (codInstanciaGlobal,".$this->getPKRelacionamento($nivel).",aceita,momentoInscricao,usuarioConfirmou) ". 
           "VALUES (".$codInstanciaGlobal.",".$this->valorChavePessoa.",".$aceita.",".time().",".$usuarioConfirmou.")";    
    mysql_query($sql);
    //echo "<BR>".$sql."<BR>".mysql_error();
                   
    return (mysql_errno() == 0);
  }

  /**
   * O aluno estß confirmando sua inscriþÒo
   */       
  function confirmarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel) {
  
    //ativa o usuario, se nao tiver ativo ainda 
    parent::liberarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel);

    //seta usuarioConfirmou = 1 em InscricoesAluno
    $tabInscricao = "inscricoesaluno";      
    $sql = "UPDATE ".$tabInscricao." SET usuarioConfirmou=1 ".
           "WHERE codInstanciaGlobal=".$codInstanciaGlobal.
           " AND ".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa;    
    mysql_query($sql);    

    //echo $sql; echo mysql_error();
    
    if (mysql_errno()) return 0;

    //se o nivel possuir relacionamento com alunos, entao inscreve a pessoa 
    //inserindo um registro na tabela de relacionamento
    if ($nivel->getPKRelacionamentoAlunos() != "")
      return $this->inscreverInstancia($nivel, $codInstanciaNivel);
    else
      return 1;
  }

  /**
   * Liberar a inscricao do aluno
   */       
  function liberarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel) {
  
    //ativa o usuario, se nao tiver ativo ainda 
    parent::liberarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel);

    //seta aceita = 1 em InscricoesAluno
    $tabInscricao = "inscricoesaluno";      
    $sql = "UPDATE ".$tabInscricao." SET aceita=1 ".
           "WHERE codInstanciaGlobal=".$codInstanciaGlobal.
           " AND ".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa;    
    //echo $sql;   echo mysql_error(); die;
    mysql_query($sql);    


    
    if (mysql_errno()) return 0;
    
    //se o nivel possuir relacionamento com alunos, entao inscreve a pessoa 
    //inserindo um registro na tabela de relacionamento
    $instancia = new InstanciaNivel($nivel,$codInstanciaNivel);
    if ($instancia->relacionaPessoas()) {
      $ret = $this->inscreverInstancia($nivel, $codInstanciaNivel);
      return $ret;
    }
    else {
      return 1;
    }
  }
  
  /**
   * Rejeita a inscricao
   * Ou sera que era melhor deletar o registro da inscricao?   
   */       
  function rejeitarInscricao($codInstanciaGlobal,$deletar=0) {
    $tabInscricao = "inscricoesaluno";      
   	$instGlobal = new InstanciaGlobal($codInstanciaGlobal);  	
    $nivel = new Nivel($instGlobal->codNivel);
    
    if ($deletar) {
      //deleta o registro da inscricao
      $sql = "DELETE FROM ".$tabInscricao.
             " WHERE codInstanciaGlobal=".$codInstanciaGlobal.
             " AND ".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa;      
    }
    else {
      //faz update, marcando aceita = -1
      $sql = "UPDATE ".$tabInscricao." SET aceita=-1 ".
             "WHERE codInstanciaGlobal=".$codInstanciaGlobal.
             " AND ".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa;
    }
    mysql_query($sql);    

    //echo $sql; echo mysql_error();
    
    if (mysql_errno()) 
      return 0;
    else
      return 1;    
  }
  
  /**
   * Se o nivel possuir relacionamento com alunos, inscreve o aluno no relacionamento  
   */        
  /*function inscreverInstancia($nivel,$codInstanciaNivel) {
    
    $chaveRelacionamento = InstanciaNivel::getPK($nivel); 
    $tabela = $nivel->nomeFisicoTabelaRelacionamentoAlunos;
    $pkRelacionamento = $nivel->nomeFisicoPKRelacionamentoAlunos;
    $sql = "INSERT INTO ".$tabela." (".$chaveRelacionamento.",".$pkRelacionamento; 
    if ($nivel->nivelComunidade) { $sql.=",ativo"; }
    $sql.=") ";
    $sql.="VALUES (".$codInstanciaNivel.",".$this->valorChavePessoa;
    if ($nivel->nivelComunidade) { $sql.=",1"; }
    $sql.=")";
    
    //echo $sql;        
    mysql_query($sql);
    
    //echo mysql_error();   die;
    
    return !mysql_errno();
  }*/
  /**
   * retira o aluno de uma determinada instancia  
   */        
/* function retirarInstancia($nivel,$codInstanciaNivel) {
    $sql = "DELETE FROM ".$nivel->nomeFisicoTabelaRelacionamentoAlunos." WHERE ".$nivel->getPK()."=".$this->valorChavePessoa." AND".
           $nivel->nomeFisicoPKRelacionamentoAlunos."=".$codInstanciaNivel;
    mysql_query($sql);
    return !mysql_errno();
  }*/
  
  /**
   * Retorna as inscricoes pendentes deste aluno
   */       
  function getInscricoesPendentes() {
    $sql = "SELECT * FROM inscricoesaluno WHERE ".$this->getPKRelacionamento()."=".$this->valorChavePessoa;
    return new RDCLQuery($sql);
  }

  /**
   * Cria um novo aluno, colocando o registro na tabela do aluno
   */     
  function criaNovoAluno($codPessoa) {
    $sql = "INSERT INTO ".Aluno::getTabela()." (COD_PESSOA) VALUES (".quote_smart($codPessoa).")";
    mysql_query($sql);
	  $this->valorChavePessoa=mysql_insert_id();
    return (mysql_errno() == 0);    
  }
  
  /**
   * Deleta o aluno
   */     
  function deleta() {
    if (!isset($this->valorChavePessoa)) 
      return 0;

    //deleta a pessoa
    if (parent::deleta()) {
  
      //deleta o aluno
      $sql = "DELETE FROM ".$this->getTabela(). " WHERE ".$this->getPKRelacionamento()."=".$this->valorChavePessoa;
      mysql_query($sql);
  
      //echo $sql; echo mysql_error();
      
      return (mysql_error() == 0);
    }
    else
      return 0;
  }

  /*
   * Verifica se a flag de permissao ao professor para administrar esta ativada para este papel
   */
   function podeSerAdministrado($codPapel) {
     
     $sql='select professorPodeGerenciar from '.TABELA_SUBPAPEL_ALUNO.' where '.PK_SUBPAPEL_ALUNO.'='.$codPapel.' LIMIT 0,1';
    
     $result = mysql_query($sql);
     $papel = mysql_fetch_object($result);
     
     return $papel->professorPodeGerenciar;
   }    
}

?>
