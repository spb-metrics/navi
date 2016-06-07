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
 * short desc: Classe Pessoa, para abstrair similaridades entre prof e aluno
 *
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/FÃ¡bio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de AdministraÃ§Ã£o/UFRGS 2005
 */

define('TABELA_PESSOA','pessoa');
define('PK_PESSOA','COD_PESSOA');

class Pessoa extends RDCLRow {
  //PK do tipo de pessoa (ALUNO, PROFESSOR)
  //var $chavePessoa;
  //obsoleto, chave pode variar pelo nivel 
  
  //Valor da PK do tipo de pessoa (ALUNO, PROFESSOR)
  var $valorChavePessoa;
  //tabela fisica que possui as instancias
  var $tabela;
  //tabela que relaciona o tipo de pessoa as instancias, do tipo M:N
  var $tabelaRelacionamento;
  //nao Ã© mais utilizado, utilizar mÃ©todo $nivel->getNiveisRelacionamentoFormal()
  //pois pode haver mais de um nÃ­vel relacionando
  //nivel que relaciona inicialmente as pessoas a plataforma
  //var $nivelRelacionamento;

  //identifica o papel exercido pela pessoa na plataforma na instancia sistemica atual
  var $userRole;
  //nivel carregado inicialmente no login do usuario
  var $nivelInicial;
  //identifica o papel exercido pela pessoa na plataforma quando faz-se o login
  var $userRoleInicial;
  //identifica o status de interacao do papel exercido pela pessoa na plataforma quando faz-se o login
  var $interageInicial;
  /*
   * Construtor
   */
  function Pessoa() {
    //$this->nivelRelacionamento = Nivel::getNivelRelacionamentoAlunosProfessores();
    //por padrao Ã© o nivel de relacionamento
    //$this->nivelInicial = $this->nivelRelacionamento;
  }

  /*
   * Embora pessoa descnda de RDCLRow nÃ£o estÃ¡ instanciando a pessoa, como em buscaPessoa 
   */     
  function setAtributos($atributos) {
    $this->records[0]=$atributos;
    RDCLQuery::createSimpleObject($this);
    /*
    for($i=0;$i<count($atributos);$i++) {
      list($nome,$valor) = each($atributos);
      $this->$nome = $valor;
    }*/
  }

  /*
   * Retorna instancias onde a pessoa se relaciona, dentro do nivel solicitado
   */
  function getNiveis($where="",$nivel,$mostraPassado=0) {   
    //este Ã© o campo, para este papel (aluno/professor) que informa a tabela de relacionamento <nivel> 1 --- * pessoa    
    $tabelaRelacionamento = $this->getTabelaRelacionamento($nivel);
    $chavePessoa = $this->getPKRelacionamento($nivel);
    

    $sql = ' SELECT *, '.$nivel->codNivel.' as codNivel,  TIPO.interage';
    $sql.= ' FROM '.$tabelaRelacionamento.' TR, '.$nivel->nomeFisicoTabela.' T, '.$this->getTabelaSubPapel().' TIPO';
    //por default, busca apenas os relacionamentos com instancias do periodo atual
    if ($nivel->temporal) {
      $sql.= ', '.TABELA_TEMPO.' TP ';
    }
    $sql.= ' Where '.$chavePessoa.'='.quote_smart($this->valorChavePessoa);
    $sql.= ' AND TR.'.$nivel->nomeFisicoPK.'=T.'.$nivel->nomeFisicoPK;
    $sql.= ' AND TR.'.$this->getPKSubPapel().'=TIPO.'.$this->getPKSubPapel();
    
    if ($nivel->temporal) {
      //por default, busca apenas os relacionamentos com instancias do periodo atual
      if (!$mostraPassado) { $operacao='>='; } else { $operacao='<';}    
      $sql.=' AND TP.tipoPeriodo=T.tipoPeriodo AND T.anoPeriodo'.$operacao.'TP.anoPeriodo ';
    }
    $sql.=$where; //where que tenha sido informado na chamada da funcao
    
    //ORDENACAO 
    $sql.=' ORDER BY ';
    //POR TEMPO 
    if ($nivel->temporal) {
      $sql.= ' T.anoPeriodo DESC, ';
    }
    //ALFABETICA pela abreviatura ou nome, pois é assim que aparece na caixa.
    //chama estaticamente pois o objeto passado pode ser stdClass direto do banco
    $sql.=Nivel::getCampoAbreviaturaOuNome($nivel).' ASC'; 

    //if (empty($where)) { echo '<br>'.$sql; die; }   
    //echo "<!-- ".$sql." -->";
	  $obj = new RDCLQuery($sql);
    return $obj;
  }
  /*
   *  Retorna o nivel inicial, padrao
   */
  function getNivelInicial() {
    return $this->nivelInicial;
  }
  /*
   *  Retorna o papel inicial, padrao
   */
  function getUserRoleInicial() {
    return $this->userRoleInicial;
  }

  
  /*
   * Retorna a instancia inicial
   */
  function getInstanciaNivelInicial($codPessoa,$mostraPassado='SEARCH') {
    $instanciaInicialTableUsed=0;

    //$sql = "Select userRole, mostraPassado,codNivel,codInstanciaNivel from instanciainicial Where codPessoa=".quote_smart($codPessoa);        
    $sql = "Select  * from instanciainicial Where codPessoa=".quote_smart($codPessoa);    
    $obj = new RDCLQuery($sql);
    
    // Busca a ultima instancia.
    // se a instancia inicial for de periodo diferente (atual ou passado), 
    // em relacao a opcao atual do usuario de atual/passado, 
    // entao desconsidera - provavelmente o usuario esta alterando entre atual/passado
    // e por isso a instancia inicial nao esta na lista de direitos
    if ( (!empty($obj->records))  &&  
         ($obj->records[0]->mostraPassado===$mostraPassado || $mostraPassado==='SEARCH') ) {      
      $instanciaInicialTableUsed=1;
      $nivel = new Nivel($obj->records[0]->codNivel);
      $k="codInstanciaNivel";
      $this->userRoleInicial = $obj->records[0]->userRole;      
      $mostraPassado=$obj->records[0]->mostraPassado; //atualiza no caso de recuperar primeiro acesso
    }    
    // busca aqui  somente se for chamado pelas subclasses (as quais setam userRole)  
    else if (!empty($this->userRole)) {                      
      /* Busca uma instancia padrÃ£o dentro de algum nivel de relacionamento 
      * se nao for setado (de maneira forÃ§ada, ou no primeiro acesso)
      * 
      * Primeiro busca no tempo selecionado; se nao informado, presente por default.
      * Se nao encontrar, busca no outro tempo                          
      */      
      
      //tenta buscar uma instancia dentro do nivel de relacionamento
      //que esteja marcada como padrao
      if ($mostraPassado=='SEARCH') { $mostraPassado=0; } //default eh mostrar o presente

      $niveisFormais = Nivel::getNiveisRelacionamentoFormal();
      
      //Busca se houver uma instancia padrao selecionada
      if (!empty($niveisFormais->records)) {
        foreach($niveisFormais->records as $n) {
          $obj = $this->getNiveis(" AND PADRAO=1",$n,$mostraPassado);
          if (!empty($obj->records)) {
            $nivel = new Nivel();
			      foreach($n as $campo => $valor) { $nivel->$campo = $valor; }
            $nivel->records = ''; 
            break; //qdo acha um primeiro relacionamento sai do laÃ§o
          }
        }
      }
      //Se nao houver uma instancia padrao selecionada, 
      //entao busca uma outra qualquer
      if (empty($obj->records) && !empty($niveisFormais->records)) {
        foreach($niveisFormais->records as $n) {
          $obj = $this->getNiveis('',$n,$mostraPassado);
          if (!empty($obj->records)) {
            $nivel = new Nivel();
			      foreach($n as $campo => $valor) { $nivel->$campo = $valor; }
            $nivel->records = ''; 
            break; //qdo acha um primeiro relacionamento sai do laÃ§o
          }
        }
      } 

      /*
       * Nao encontrou relacionamentos no tempo selecionado;
       * busca no outro tempo, repetindo as duas buscas acima       
       */      
      //Busca se houver uma instancia padrao selecionada  (no outro tempo)
      if (empty($obj->records) && !empty($niveisFormais->records)) {
        if ($mostraPassado) { $mostraPassado=0;} else { $mostraPassado=1;}

        foreach($niveisFormais->records as $n) {
          $obj = $this->getNiveis(" AND PADRAO=1",$n,$mostraPassado);
          if (!empty($obj->records)) {
            $nivel = new Nivel();
			      foreach($n as $campo => $valor) { $nivel->$campo = $valor; }
            $nivel->records = ''; 
            break; //qdo acha um primeiro relacionamento sai do laÃ§o
          }
        }
      }
      //Se nao houver uma instancia padrao selecionada, 
      //entao busca uma outra qualquer (no outro tempo, selecionado acima)
      if (empty($obj->records) && !empty($niveisFormais->records)) {
        foreach($niveisFormais->records as $n) {
          $obj = $this->getNiveis('',$n,$mostraPassado);
          if (!empty($obj->records)) {
            $nivel = new Nivel();
			      foreach($n as $campo => $valor) { $nivel->$campo = $valor; }
            $nivel->records = ''; 
            break; //qdo acha um primeiro relacionamento sai do laÃ§o
          }
        }
      }
      
      //Se ainda nao estiver em nenhuma instancia do relacionamento, 
      //tenta buscar uma comunidade    
      if (empty($obj->records)) {  
        $obj=$this->getComunidades(); 
        if (!empty($obj->records)) {
          $nivel = Nivel::getNivelComunidade();
        }
      }

      //
      
      //$k Ã© pk na tabela do nivel de relacionamento (ex.codigo da turma)
      //ou na comunidade
      $k = $nivel->nomeFisicoPK;       
    }
    
    if (!empty($nivel) && !empty($obj->records)) {
      $instanciaInicial = new InstanciaNivel($nivel,$obj->records[0]->$k);
      //indica se a instancia inicial guardada no banco foi utilizada
      $instanciaInicial->instanciaInicialTableUsed=$instanciaInicialTableUsed;
      //coloca o mostra passado como atributo da instancia inicial para que os direitos sejam coerentes
      $instanciaInicial->mostraPassado = $mostraPassado; 
      //coloca o interage como atributo da instancia inicial para que os direitos sejam coerentes
      $instanciaInicial->interage = $obj->records[0]->interage; 
      //Seta o nivel inicial para ser recuperado
      $this->nivelInicial = $nivel;  
    } 
    
    return $instanciaInicial;
  }
  
  /*
   * Seta nivel/instancia a serem carregados no login do usuario
   */   
  function setInstanciaNivelInicial($codPessoa,$nivel,$instanciaNivel,$mostraPassado=0,$interage) {
    $sql = 'update instanciainicial set codNivel='.quote_smart($nivel->codNivel).
           ',codInstanciaNivel='.quote_smart($instanciaNivel->codInstanciaNivel).  
           ',userRole='.quote_smart($this->userRole).             
           ',mostraPassado='.quote_smart($mostraPassado).    
           ',interage='.quote_smart($interage).    
           ' Where codPessoa='.quote_smart($codPessoa);    
    mysql_query($sql);
    //echo "sql: ".$sql."<BR>".mysql_error();
    //se nao houver registros afetados, verifica se nao eh a propria instancia
    //que ja estava no banco. Se nao for, insere, deve ser a primeira vez que o usuario acessa
    //ou entao o banco foi limpo 
    if (!mysql_affected_rows()) {
      $sql = "Select 1 from instanciainicial Where codNivel=".quote_smart($nivel->codNivel).
           " AND codInstanciaNivel=".quote_smart($instanciaNivel->codInstanciaNivel).  
           " AND mostraPassado=".quote_smart($mostraPassado).  
           " AND interage=".quote_smart($interage).  
           " AND codPessoa=".quote_smart($codPessoa);
      $result = mysql_query($sql);
      //echo "sql: ".$sql."<BR>".mysql_error();
      if (!mysql_num_rows($result)) {
        $sql = ' INSERT INTO instanciainicial (codPessoa,codNivel,codInstanciaNivel,userRole,mostraPassado,interage)'.
               ' VALUES ('.quote_smart($codPessoa).','.quote_smart($nivel->codNivel).','.
               quote_smart($instanciaNivel->codInstanciaNivel).','.
               quote_smart($this->userRole).','.
               quote_smart($mostraPassado).','.
               quote_smart($interage).')';  
                   
        mysql_query($sql);
        //echo "sql: ".$sql."<BR>".mysql_error();
      }
    }
  }
  
  
  /*
   * Retorna os objetos de todas as instancias do nivel onde se relaciona
   */
  function getTodosNiveis() {
  }
  
  
  /* Pessoa->getInstanciasRelacionamento()
   * Retorna a arvore completa de cada instancia de cada nivel de relacionamento armazenado
   */
   /* TEMPLATE SQL USADO:
      //em todas as turmas do aluno
      SELECT 
      Instituicao.abreviatura as Instituicao,UnidadeAcademica.abreviatura as UnidadeAcademica,
      Area.nome as Area,Curso.DESC_CURSO as Curso,
      Disciplina.DESC_DIS as Disciplina,Turma.NOME_TURMA as Turma
      FROM 
      Instituicao,UnidadeAcademica,AreaUnidade,Area,Curso,Disciplina,Turma,Aluno_turma
      Where
      Aluno_turma.COD_AL = 2 AND
      Turma.COD_TURMA=Aluno_turma.COD_TURMA AND
      Turma.COD_DIS = Disciplina.COD_DIS AND
      Disciplina.COD_CURSO = Curso.COD_CURSO AND
      Curso.codAreaUnidade  = AreaUnidade.codAreaUnidade AND
      Area.codArea = AreaUnidade.codArea AND
      UnidadeAcademica.codUnidade = AreaUnidade.codUnidade AND
      UnidadeAcademica.codInstituicao=Instituicao.codInstituicao 
    */
  function getInstanciasRelacionamento($mostraPassado=0) {
    $todosNiveisRelacionamento = Nivel::getNiveisRelacionamentoFormal();
    $instanciasRelacionamento=array();
    
    //Para cada nivel de relacionamento, constroi a consulta buscando os relacionamentos da pessoa com 
    //as instancias
    for($h=0;$h<count($todosNiveisRelacionamento->records);$h++) {
      //Cria o objeto com os atributos da consulta ref ao nivel de relacionamento
      $nivel = new Nivel();
      foreach($todosNiveisRelacionamento->records[$h] as $campo=>$valor) {
        $nivel->$campo=$valor;
      }
      //Busca niveis hierarquicamente superiores a este
      $hierarquia = $nivel->getHierarquiaPai(); 
      $max = count($hierarquia)-1;
      
      //Busca campos e tabelas, varrendo a hierarquia de cima para baixo 
      for($i=$max;$i>=0;$i--) {
        $obj = &$hierarquia[$i];
        //campos
        $campos[] = $obj->nomeFisicoTabela.'.'.Nivel::getCampoAbreviaturaOuNome($obj).' AS nome'.$i;
        $campos[] = InstanciaNivel::getTablePK($obj).'.'.$obj->getPK().' AS pk'.$i;
        //tabelas. se for n:m com seu pai, que jÃ¡ estarÃ¡ na hierarquia, tambem busca a tabela de relacionamento n:m com o pai
        $tabelas[] = $hierarquia[$i]->nomeFisicoTabela;
        if ($obj->tipoRelacionamentoComNivelPai==2) { $tabelas[] = $obj->nomeFisicoTabelaRelacionamento;}
      }
      //Acrescenta o atributo de interacao, para defaultPage poder passar ao index
      $campos[] = 'TIPO.interage';
      //Acrescenta a tabela de relacionamento na lista de tabelas
      $tabelas[] = $this->getTabelaRelacionamento($nivel);
      //Acrescenta a tabela de papel (tipo_professor ou tipo_aluno_ na lista de tabelas
      $tabelas[] = $this->getTabelaSubPapel().' AS TIPO ';
       
       
      //acrescenta tabela de periodos, caso seja solicitado
      //confirma que  nivel tem temporalidade
      if ($nivel->temporal) {        
          $tabelas[] = TABELA_TEMPO.' TP ';
      }
      
      //Constroi os wheres que sao os joins para selecionar toda a hierarquia,
      //varrendo-a de cima para baixo
      for($i=($max-1);$i>=0;$i--) {
        $objPai = &$hierarquia[$i+1];
        $obj    = &$hierarquia[$i];
        $whereTemp = "";
        //eh preciso usar getPK pois nao sabemos se o pai era 1:N ou N:M com o avo
        $whereTemp = InstanciaNivel::getTablePK($objPai).".".$objPai->getPK()."=";
        //1:N com o nivel pai, basta fazer join simples entre as duas tabelas
        if ($hierarquia[$i]->tipoRelacionamentoComNivelPai==1) {
          $whereTemp.= $obj->nomeFisicoTabela.".".$objPai->getPK();
        }
        //N:M com o nivel pai, eh preciso fazer join entre as duas tabelas e tambem com a tabela de relacionamento
        else if ($obj->tipoRelacionamentoComNivelPai==2) { 
          $whereTemp.= $obj->nomeFisicoTabelaRelacionamento.".".$objPai->getPK(); //completa o join do pai
          $whereTemp.= " AND ".$obj->nomeFisicoTabela.".".$obj->nomeFisicoPK."="; //o join deste nivel com a tabela de relacionamento
          $whereTemp.= $obj->nomeFisicoTabelaRelacionamento.".".$obj->nomeFisicoPK; 
        }
        //acrescenta na lista
        $where[] = $whereTemp;
      }
      //join entre o nivel que relaciona e a pessoa
      $where[] = $nivel->nomeFisicoTabela.".".$nivel->nomeFisicoPK."=".$this->getTabelaRelacionamento($nivel).".".$nivel->nomeFisicoPK;
      //join entre o relacionamento e o papel (tipo_aluno/professor)
      $where[] = $this->getTabelaRelacionamento($nivel).".".$this->getPKSubPapel()."=TIPO.".$this->getPKSubPapel();
      //seleciona a pessoa (professor ou aluno)
      $where[] = $this->getTabelaRelacionamento($nivel).".".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa;      

      //se o nivel for temporal, entao acrescenta clausula para filtrar o tempo, se necessario
      if ($nivel->temporal) {
        if (!$mostraPassado) { $operador='>='; } else { $operador='<'; }
        $where[] =  'TP.tipoPeriodo='.$nivel->nomeFisicoTabela.'.tipoPeriodo and '.$nivel->nomeFisicoTabela.'.anoPeriodo'.$operador.'TP.anoPeriodo';        
      }

      /*
       * Constroi string para buscar toda as instancias da arvore sistemica
       * a partir do relacionamento (ex.turma de alunos)
       */    
      $sql='';
      $sql .= 'SELECT ';
      while (list(,$c) = each($campos)) {  $sql.=$c.', '; } $sql = rtrim($sql,', ');
      $sql .= ' FROM ';
      while (list(,$tab) = each($tabelas)) {  $sql.=$tab.', '; } $sql = rtrim($sql,', ');
      $sql .= ' WHERE ';
      while (list(,$w) = each($where)) {  $sql.=$w.' AND '; } $sql = rtrim($sql,' AND ');
      //ordena mostrando as mais recentes primeiro, e alfabeticamente dentro do mesmo periodo
      $sql.= ' ORDER BY ';
      //if ($nivel->temporal) {
        $sql.=$nivel->nomeFisicoTabela.'.anoPeriodo DESC, ';
      //}
      $sql.=' nome0 ASC '; //alias da abreviatura ou nome da tab q relaciona, ao projetar os campos
      //echo '<pre> MOSTRA PASSADO:'; var_dump($mostraPassado); echo $sql;
      $consulta = new RDCLQuery($sql);
      $instanciasRelacionamento[] = array($hierarquia,$consulta->records);
    } 
    
    return $instanciasRelacionamento;

  }
  /*
   * Busca as comunidades onde a pessoa esta inserida ou pendente
   */
  function getComunidades($flagPendente="",$codComunidade="",$moderador='') {
    
    $nivelComunidade = Nivel::getNivelComunidade();
	  //note($nivelComunidade);
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    $tabRelacionamento = $this->getTabelaRelacionamento($nivelComunidade);
    $pkRelacionamento = $this->getPKRelacionamento($nivelComunidade);
    
    $sql = " Select ".$this->userRole." AS userRole,C.*,TR.*, ".$nivelComunidade->codNivel." as codNivel";
    //vamos mostrar os nomes das pessoas
    if (empty($this->valorChavePessoa)) { 
      $sql.=",P.COD_PESSOA,P.NOME_PESSOA ";  
    }
    else {  //vamos buscar tambem o papel da pessoa na comunidade
      $sql.=",TIPO.interage ";     
    } 
    
    $sql.= " from ".$nivelComunidade->nomeFisicoTabela." C"; //tabela de comunidades

    //utilizado para o moderador(professor) ou adm nivel poder ter uma visÃ£o geral
    //de todas as pendÃªncias  
    if (!empty($moderador)) {
     $sql.= " INNER JOIN ".$moderador->getTabelaRelacionamento($nivelComunidade).
     " PROF ON (C.".$pkComunidade."=PROF.".$pkComunidade.
     " AND PROF.".$moderador->getPKRelacionamento($nivelComunidade)."=".$moderador->valorChavePessoa." AND PROF.ativo=1)";    
    }

    $sql.= " INNER JOIN ".$tabRelacionamento." TR ON (C.".$pkComunidade."=TR.".$pkComunidade.")";
    //quando esta 
    if (!empty($this->valorChavePessoa)) {
      $sql.= " INNER JOIN ".$this->getTabelaSubPapel()." TIPO ON (TR.".$this->getPKSubPapel()."=TIPO.".$this->getPKSubPapel().")";
    } 
    //Ver todas as pessoas ou apenas certa pessoa
    if (!empty($this->valorChavePessoa)) {
      $where[]= " TR.".$pkRelacionamento." = ".$this->valorChavePessoa;
    }
    else { //mostra os nomes das pessoas
      $sql.=" INNER JOIN ".$this->getTabela()." TAB on (TAB.".$pkRelacionamento."=TR.".$pkRelacionamento.")";
      $sql.=" INNER JOIN pessoa P on (P.COD_PESSOA=TAB.COD_PESSOA)";
    }
    //usado para ver as comunidades onde meu ingresso estÃ¡ pendente
    //ou somente as ativas
    if ($flagPendente) {    $where[] =" TR.ativo=0";   } else { $where[] =" TR.ativo=1";   }
    //usado para ver certa comunidade ou todas
    if ($codComunidade) {   $where[] =" C.".$pkComunidade."=".$codComunidade;  }

    //trata as condicoes where de modo conjuntivo (somente AND )
    if (!empty($where)) { 
      list(,$cond) = each($where);
      $sql.=" WHERE ".$cond; //coloca a primeira condicao com where
      while ( list(,$cond) = each($where) ) {
        $sql.=" AND ".$cond;      
      }
    }
    return new RDCLQuery($sql);
  }
  /*
   * Busca as comunidades onde a pessoa NAO esta inserida e nem pendente
   */
  function getComunidadesAusente($codInstanciaGlobal='',$nomeBusca='') {
  
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    $tabRelacionamento = $this->getTabelaRelacionamento($nivelComunidade);
    $pkRelacionamento = $this->getPKRelacionamento($nivelComunidade);

    $sql = " SELECT COMUNIDADE.* FROM  ".$nivelComunidade->nomeFisicoTabela." AS COMUNIDADE";
    if (!empty($codInstanciaGlobal)) { //busca apenas as filtradas pela instancia atual
      $sql .= " INNER JOIN instanciacomunidade IC ON (COMUNIDADE.".$pkComunidade."=IC.".$pkComunidade." AND IC.codInstanciaGlobal=".quote_smart($codInstanciaGlobal).")";
    }
    $sql .= " left outer join ".$tabRelacionamento." as INSCRICAO on (INSCRICAO.".$pkComunidade." = COMUNIDADE.".$pkComunidade." AND INSCRICAO.".$pkRelacionamento."=".$this->valorChavePessoa.")";
    $sql .= " Where INSCRICAO.".$pkRelacionamento." is null";
    if (!empty($nomeBusca)) {
      $sql.=" AND COMUNIDADE.".$nivelComunidade->nomeFisicoCampoNome." LIKE ".quote_smart("%".$nomeBusca."%");
    }
    //aqui verificamos se o papel da 
    if (!empty($this->campoParticipacaoPapel)) {
      $sql.=" AND COMUNIDADE.".$this->campoParticipacaoPapel."=1"; 
    }
    //echo $sql;
    return new RDCLQuery($sql);
  }
  /*
   * Inscreve a pessoa na comunidade
   */
  function inscreverComunidade($codComunidade) {
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    $tabRelacionamento = $this->getTabelaRelacionamento($nivelComunidade);
    $pkRelacionamento = $this->getPKRelacionamento($nivelComunidade);
    $pkTipoPapel=$this->getPKPapel($nivelComunidade);
    $sql = "INSERT INTO ".$tabRelacionamento." (".$pkComunidade.",".$pkRelacionamento.",".$pkTipoPapel.",ativo) " ;
    $sql.= " VALUES (".$codComunidade.",".$this->valorChavePessoa.",1,0);";

    mysql_query($sql);

    return (!mysql_errno());
  }
  /*
   * Libera a pessoa em certa comnidade pendente
   */
  function liberarPessoaComunidade($codComunidade) {
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    $tabRelacionamento = $this->getTabelaRelacionamento($nivelComunidade);
    $pkRelacionamento = $this->getPKRelacionamento($nivelComunidade);

    $sql = "UPDATE ".$tabRelacionamento." SET ativo=1 " ;
    $sql.= " WHERE ".$pkComunidade."=".$codComunidade." AND ".$pkRelacionamento."=".$this->valorChavePessoa;

    mysql_query($sql);

    return (!mysql_errno());
  }

  /*
   *  Busca a(s) pessoas de acordo com o parÃ¢metro: codPessoa ou nome
   */   
  function buscaPessoa($parametro) {
    if (is_numeric($parametro)) { //codPessoa, ja sabemos quem Ã©
      $this->RDCLRow('pessoa','COD_PESSOA',$parametro);
	    return 1;      
    }
    else { // busca por nome
      $busca = new RDCLQuery("Select * from pessoa where NOME_PESSOA LIKE '%".$parametro."%'");
      if (count($busca->records)==1) { //apenas 
        $this->RDCLRow('pessoa','COD_PESSOA',$busca->records[0]->COD_PESSOA);
        return 1;
      }
      else {  //vÃ¡rios registros, vamos retorna-los para que a aplicaÃ§Ã£o trabalhe
        return $busca;
      }   
    }
  }
  
  /*
   * Verifica se a pessoa possui papel de aluno   
   */   
  function isAluno() {
    $result = mysql_query("Select COD_AL from aluno WHERE COD_PESSOA=".quote_smart($this->COD_PESSOA));
    $linha = mysql_fetch_assoc($result);
    
    return $linha['COD_AL']; 
  }
  
  
  function isProfessor() {
 
    $result = mysql_query("Select COD_PROF from professor WHERE COD_PESSOA=".quote_smart($this->COD_PESSOA));
    $linha = mysql_fetch_assoc($result);
    
    return $linha['COD_PROF'];   
  }
  
  
  function administrador() {
  
  
  }
  
  /**
   * Retorna se eh um usuario ativo
   */       
  function usuarioAtivo() {
    $result = new RDCLQuery("SELECT ativa FROM pessoa WHERE COD_PESSOA=".quote_smart($this->COD_PESSOA));
    return $result->records[0]->ativa;
  }
  
  /**
   * Ativa o usuario
   */       
  function ativaUsuario() {
    //echo "UPDATE pessoa SET ativa=1 WHERE COD_PESSOA=".quote_smart($this->COD_PESSOA);
    mysql_query("UPDATE pessoa SET ativa=1 WHERE COD_PESSOA=".quote_smart($this->COD_PESSOA));
    //echo mysql_error();
    return (mysql_errno() == 0);
  }
      
  /**
   * Inscreve o aluno, registrando em InscricoesAluno  
        
  function inscrever($nivel, $codInstanciaGlobal) {
  }

  /**
   * Liberar a inscricao do aluno
   */       
  function liberarInscricao($nivel, $codInstanciaGlobal, $codInstanciaNivel) {
    //echo "Pessoa::liberarInscricao<br>";
    if (!$this->usuarioAtivo()) {      
      //echo "ativando usuario";
      return $this->ativaUsuario();
    }
    else  
      return 1;
  }

  /**
   * Deleta a pessoa
   */     
  function deleta() {
    if (!isset($this->COD_PESSOA)) 
      return 0;

    $sql = "DELETE FROM pessoa WHERE COD_PESSOA=".$this->COD_PESSOA;
    mysql_query($sql);
    
    //echo $sql; echo mysql_error();
    
    return (mysql_error() == 0);
  }

  /*
   * Este mÃ©todo determina se a pessoa tem direito de admnistrar, editar/excluir
   * ela pode se for admnistrador de nivel ou geral,
   * ou entao se for um professor, com permissao de interacao, dentro de um nivel que relacione pessoas       
   */   
  function podeAdministrar($userRole,$nivel,$interage=1) {
    //talvez instancia atual possa ser passada como parametro
    $instanciaAtual = new InstanciaNivel($nivel,getCodInstanciaNivelAtual());
    
    if ($userRole==ADMINISTRADOR_GERAL || $userRole==ADM_NIVEL) {
      return 1;
    }
    //PROFESSOR  tem de estar numa instancia em que pode interagir e tambem relaciona pessoas
    elseif ($userRole==PROFESSOR && $interage && $instanciaAtual->relacionaPessoas()) {
      return 1;
    }
    else {
      return 0;
    }  
  }
  /*
   * Informa se a pessoa eh administrador,
   * algumas funcoes sao exclusivas desta autoridade/papel
   * Verifica userRole em sessao
   */   
  function isAdm($userRole) {    
    if ($userRole==ADMINISTRADOR_GERAL || $userRole==ADM_NIVEL) {
      return 1;
    }
  }

  /*
   *  MÃ©todo para exibir o icone se a pessoa estÃ¡ online
   *
   *  o recurso pode fornecer o timestamp a ser aplicado para poder desprezar
   *  o texto de execuÃ§Ã£o do script.   
   *  
   *  O objeto pessoa deve ter ter os atributos inicializados
   */         
  function isOnline($timeStamp='',$closeLink=0) {
    global $url,$urlImagem;
    if (empty($timeStamp)) {  $timeStamp=time(); }
    
    /* debug...
    echo "<BR>TEMPO: ".$timeStamp;
    echo "<BR>ALIVE: ".$this->alive;
    echo "<BR>TIMEOUT: ".USER_TIMEOUT;*/
    $ret='';
    //mostra se o usuario professor estÃ¡ online ou offline
    if (!$this->alive || ( ($timeStamp-$this->alive) > USER_TIMEOUT) ) {              
      $ret.="<a href='".$url."/alunos/enviatorpedo.php?codPessoaDestino=".$this->COD_PESSOA."&offline=1' title='Offline. O usuÃ¡rio somente verÃ¡ o torpedo quando entrar no NAVi novamente!' ><img src='".$urlImagem."/useroffline.png' border='0' >"; 
    }
    else {
      $ret.="<a href='".$url."/alunos/enviatorpedo.php?codPessoaDestino=".$this->COD_PESSOA."'  title='Online! Clique para enviar um torpedo para ".$this->NOME_PESSOA."'  >";
      $ret.="<img src='".$urlImagem."/useronline.png' border='0'>"; 
    }
    if ($closeLink) {
      $ret.="</a>";    
    }
    
    return $ret;
  }

  /*
   * Retorna as insriÃ§Ãµes em comunidadades pendentes de aprovaÃ§Ã£o
   */
  function getInscricoesPendentesEmComunidades($codComunidade="",$flagTodasModeradas) {
    $arrayAlunosPendentes=array(); $arrayProfessoresPendentes=array();
    
    $aluno = new Aluno();
    $professor = new Professor();
    
    if ($flagTodasModeradas) { $moderador = $this; } else { $moderador=''; }
    
    $alunosPendentes      =  $aluno->getComunidades(1,$codComunidade,$moderador);
    $professoresPendentes =  $professor->getComunidades(1,$codComunidade,$moderador);
    
    if (!empty($alunosPendentes->records)) {
      $arrayAlunosPendentes=$alunosPendentes->records;
    }
    
    if (!empty($professoresPendentes->records)) {
      $arrayProfessoresPendentes=$professoresPendentes->records;
    }
    
    return array_merge($arrayAlunosPendentes,$arrayProfessoresPendentes);
  }
   /**
   * Se o nivel possuir relacionamento com alunos, inscreve o aluno no relacionamento  
   */        
  function inscreverInstancia($nivel,$codInstanciaNivel,$codTipoPapel=1,$ativo=1) {
    
    $chaveRelacionamento = $nivel->getPK(); 
    $tabela = $this->getTabelaRelacionamento($nivel);
    $pkRelacionamento = $this->getPKRelacionamento($nivel);
    $PKPapel=$this->getPKPapel($nivel);
    
    
    $sql = "INSERT INTO ".$tabela." (".$chaveRelacionamento.",".$pkRelacionamento .",".$PKPapel;
    if ($nivel->isNivelComunidade()) {
      $sql.= ",ativo";
    }
    
    $sql.= ") ";
    $sql.= "VALUES (".$codInstanciaNivel.",".$this->valorChavePessoa.",".$codTipoPapel;
    
    if ($nivel->isNivelComunidade()) {
      $sql.= ",".$ativo."";
    }
    $sql.= ")";
    
    //echo '<BR>SQL: '.$sql;
    mysql_query($sql);
    //echo '<BR>ERRO:  '.mysql_error(); die;
    return !mysql_errno();
  }


  /*
   * Retira integrante de determinada instancia.
   */
  function retirarInstancia($nivel,$codInstanciaNivel) {
  
	  $chaveRelacionamento = $nivel->getPK();
    $sql = "DELETE FROM ".$this->getTabelaRelacionamento($nivel)." WHERE ".$chaveRelacionamento."=".$codInstanciaNivel." AND ".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa; 
    
    mysql_query($sql);
    return (!mysql_errno());
  }
  /**
   *funÃ§Ã£o para retirar o aluno na tabela instanciaNivel Incial para qdo ele relogar nÃ£o gerar conflito
   */     
  function deleteInstanciaNivelInicial($codPessoa,$nivel,$instanciaNivel){
    $sql = "DELETE FROM instanciainicial WHERE codNivel=".quote_smart($nivel->codNivel).
           " AND codInstanciaNivel=".quote_smart($instanciaNivel->codInstanciaNivel).  
    //     " AND userRole=".quote_smart($this->userRole).             
           " AND codPessoa=".quote_smart($codPessoa);    
     //echo $sql; die;
     mysql_query($sql);
     return (!mysql_errno());
  }
  
  /*
   * Recusar a pessoa em certa comnidade pendente
   */
  function recusarPessoaComunidade($codComunidade) {
   
    $nivelComunidade = Nivel::getNivelComunidade();
    $pkComunidade = $nivelComunidade->nomeFisicoPK;
    $tabRelacionamento = $this->getTabelaRelacionamento($nivelComunidade);
    $pkRelacionamento = $this->getPKRelacionamento($nivelComunidade);


    $sql = "DELETE FROM ".$tabRelacionamento." " ;
    $sql.= " WHERE ".$pkComunidade."=".$codComunidade." AND ".$pkRelacionamento."=".$this->valorChavePessoa;
    mysql_query($sql);

    return (!mysql_errno());
  }
 
  /*
   * Retorna o nome do subpapel da pessoa
   */   
  function getSubPapel() {
    $nivelAtual  = getNivelAtual();
    $tabPapel    = $this->getTabela();
    $tabSubPapel = $this->getTabelaSubPapel();
    $pkSubPapel  = $this->getPKSubPapel();
    
    $sql = "Select descTipoProfessor FROM ".
    $tabPapel." PAPEL INNER JOIN ".$tabSubPapel." SUBPAPEL ".
    " ON (PAPEL.".$pkSubPapel."=SUBPAPEL.".$pkSubPapel.
    " WHERE PAPEL.".$this->getPKRelacionamento($nivelAtual).".=".quote_smart($this->valorChavePessoa);
    
    //echo $sql;
  }
 

  function getCodPessoa($nivel){
    $sql="SELECT COD_PESSOA FROM ".$this->tabela." WHERE ".$this->getPKRelacionamento($nivel)."=".$this->valorChavePessoa;
    //echo $sql;
    $result=mysql_query($sql);
    $codPessoa= mysql_fetch_assoc($result);
  
    return $codPessoa["COD_PESSOA"];
  }
 
  function buscaPessoasDesvinculadas() {
    //projecao comum as tres consultas
    $projecao= ' SELECT DISTINCT P.NOME_PESSOA, P.'.PK_PESSOA.    ' FROM '.TABELA_PESSOA.' P ';

    $niveis = Nivel::getNiveisRelacionamentoFormal();
    $sql='';
   
   
    //professor e administrador comentado por hora, em principio nao sera utilizado
    /*
    //primeiro busca atuacoes como administrador
    //$sql = $projecao.' INNER JOIN administrador ADM ON ( P.'.PK_PESSOA.' = ADM.'.PK_PESSOA.' )';    

    $juncaoProf = ' INNER JOIN '.TABELAPROFESSOR.' PF ON ( P.'.PK_PESSOA.' = PF.'.PK_PESSOA.' )';
    //acrescenta os niveis de relacionamento. cada um gera uma nova juncao externa/where
    $sql.= $projecao.$juncaoProf;
    foreach($niveis->records as $n) {
      $sql.= ' LEFT OUTER JOIN '.$n->nomeFisicoTabelaRelacionamentoProfessores;
      $sql.= ' ON ('.$n->nomeFisicoTabelaRelacionamentoProfessores.'.'.Professor::getPKRelacionamento($n).'=PF.'.PK_PROFESSOR.')';
      $where.=$n->nomeFisicoTabelaRelacionamentoProfessores.'.'.Professor::getPKRelacionamento($n).' IS NULL AND ';
    }
    */
    //apenas alunos
    $juncaoAluno = ' INNER JOIN '.TABELA_ALUNO.' AL ON ( P.'.PK_PESSOA.' = AL.'.PK_PESSOA.' )';
    
    $sql.= $projecao.$juncaoAluno;
    //acrescenta os niveis de relacionamento. cada um gera uma nova juncao externa e um where is null 
    foreach($niveis->records as $n) {
      $sql.= ' LEFT OUTER JOIN '.$n->nomeFisicoTabelaRelacionamentoAlunos;
      $sql.= ' ON ('.$n->nomeFisicoTabelaRelacionamentoAlunos.'.'.Aluno::getPKRelacionamento($n).'=AL.'.PK_ALUNO.')';
      $where.=$n->nomeFisicoTabelaRelacionamentoAlunos.'.'.Aluno::getPKRelacionamento($n).' IS NULL AND ';
    }
    $where = rtrim($where, ' AND');  //retira ultmo and
    
    $sql.='WHERE '.$where.' ORDER BY P.NOME_PESSOA'; 
    /*
    $sql= " SELECT P.NOME_PESSOA, P.COD_PESSOA ".
        " FROM pessoa P".
        " LEFT OUTER JOIN administrador ADM ON ( P.COD_PESSOA = ADM.COD_PESSOA )".
        " LEFT OUTER JOIN professor PF ON ( P.COD_PESSOA = PF.COD_PESSOA )".
        " LEFT OUTER JOIN aluno AL ON ( P.COD_PESSOA = AL.COD_PESSOA )".
        " LEFT OUTER JOIN professor_turma PFT ON ( PF.COD_PROF = PFT.COD_PROF )".
        " LEFT OUTER JOIN aluno_turma AT ON ( AL.COD_AL = AT.COD_AL )".
        " LEFT OUTER JOIN alunocomunidade AC ON ( AL.COD_AL = AC.COD_AL)".
        " LEFT OUTER JOIN professorcomunidade PFC ON ( PF.COD_PROF = PFC.COD_PROF)".

        " WHERE PFT.COD_PROF IS NULL".
        " AND AT.COD_AL IS NULL".
        " AND ADM.COD_PESSOA IS NULL".
        " AND AC.COD_AL IS NULL".
        " AND PFC.COD_PROF IS NULL".
        " order by P.NOME_PESSOA ASC";
    $result=  new RDCLQuery($sql);
    */

    //die($sql);
    $result = new RDCLQuery($sql);

    return $result; 
  }
  
  /*
   * Informa se a pessoa possui algum relacionamento no tempo requerido (passado/presente)
   */   
  function possuiRelacionamentoNoTempo($mostraPassado) {
    $retorno=false;
    
    $niveisFormais = Nivel::getNiveisRelacionamentoFormal();
    
    //Busca se houver uma instancia padrao selecionada
    if (!empty($niveisFormais->records)) {
      foreach($niveisFormais->records as $n) {
        $obj = $this->getNiveis("",$n,$mostraPassado);
        //note($obj);    
         
        if (!empty($obj->records)) {
          $retorno =true;
          break; //qdo acha um primeiro relacionamento sai do laÃ§o
        }
      }
    }
                                                                      
    return $retorno;
  }

  function corProfessor($codPessoa,$codInstanciaNivel, $nivel) {
    if (!$nivel->relacionaPessoas()) { return false; }

    $sql = ' select TP.corChat  from professor P,'.$nivel->nomeFisicoTabelaRelacionamentoProfessores.' REL, tipo_professor TP';
    $sql.= ' where P.COD_PROF=REL.'.$nivel->nomeFisicoPKRelacionamentoProfessores;
    $sql.= ' AND REL.codTipoProfessor=TP.codTipoProfessor';
    $sql.= ' AND REL.'.$nivel->getPK().'='.$codInstanciaNivel;
    $sql.= ' AND P.COD_PESSOA='.$codPessoa;

    $result = mysql_query($sql);
    $obj = mysql_fetch_assoc($result);
    return $obj['corChat'];
  }

}

?>
