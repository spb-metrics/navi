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
 * short desc: Niveis Sistemicos Formais e Informais da Instituicao de Ensino
 *
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/Fábio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de Administração/UFRGS 2005
 */
 
/* Tipos de nível */
define('NIVEL_AGRUPA',1);
define('NIVEL_AGRUPA_RELACIONA',2);
define('NIVEL_RELACIONA',3);
define('NIVEL_SUBGRUPO',4);
define('NIVEL_COMUNIDADE',5);
/* Tabela que indica os periodos  de tempo em que os niveis podem ocorrer */
define('TABELA_TEMPO','tipoperiodo');


class Nivel extends RDCLRow {
  /* MINI DICIONARIO DE DADOS
   * $tipoNivel ENUM (1,2,3,4,5)
   
   * Tipos de nivel: 
   *   1 - Nivel Formal que somente agrupa outros niveis   
   *   2 - Nivel Formal que as vezes agrupa niveis e as vezes relaciona alunos/professores (ex.curso, disciplina)          
   *   3 - Nivel Formal que sempre relaciona alunos/professores (ex.turma, conselho)
   *   4 - Nivel Formal relaciona pessoas como um sub grupo de outro que também relaciona (ex.grupo, projeto)
   *   5 - Nivel Informal: Comunidade
   *      
   * $tipoRelacionamentoComNivelPai: ENUM (1,2) 1=> 1:n  2=> n:m
   *    configurado de acordo com a cardinalidade do relacionamento entre o nivel e seu pai
   */
  //Setada on the fly quando necessario
  var $isEmpty;
  /*
   * Construtor
   * 
   */
  function Nivel($valorChave='',$campo='codNivel') {
    if (!empty($valorChave)) { //Para os casos em que se quer apenas instanciar o objeto nao se faz a consulta
      $this->RDCLRow('nivel',$campo,$valorChave);
    }
  }
  /*
   * retorna o nivel anterior na hierarquia
   */
  function getNivelAnterior() {
    if (empty($this->codNivelPai)) { return 0; }

    //instancia o nivel atraves do codNivelPai
    $nivelAnterior = new Nivel($this->codNivelPai);
    return $nivelAnterior;
  }
  /*
   * Retorna o proximo nivel, se for especificado
   * Obsoleto. Novo nucleo usa apenas codNivelPai   
   */
  /*
  function getProximoNivel() {
    if (empty($this->codProximoNivel)) { return 0; }
    //instancia o nivel atraves do codProximoNivel, ao inves da PK
    $proximoNivel = new Nivel($this->codProximoNivel);

    return $proximoNivel;
  }
  */
  
  /*
   * Retorna os subniveis
   */
  function getSubNiveis() {
    if (empty($this->codNivel)) { return 0; }
    return new RDCLQuery('Select * from nivel Where codNivelPai='.$this->codNivel);
  }

  /*
   * Busca todos os niveis inferiores na hierarquia
   */
  function getHierarquiaFilho() {
    $sql = 'Select * from nivel  order by codNivelPai'; 
    //echo $sql;
    $resultado = mysql_query($sql);
    //seta dois atributos para serem compartilhados entre métodos 
    $this->listaFilhos=array();    
    $this->arrayNiveis=array();
    //faz array com todos os niveis
    while ($nivelResult = mysql_fetch_object($resultado)) {      
      $nivel = new Nivel();
      foreach($nivelResult as $campo=>$valor) {  $nivel->$campo=$valor;  }
      $this->arrayNiveis[$nivel->codNivel]=$nivel;      
    }
    $this->montaListaFilhos($this->codNivel);   
     
    return $this->listaFilhos;
  }
  /*
   * Monta lista de filhos recursivamente e coloca as chaves
   */   
  function montaListaFilhos($codNivelPai) {    
     
    //se o pai ainda nao estiver na lista, acrescenta-se
    if (empty($this->listaFilhos[$codNivelPai])) {
      $this->listaFilhos[$codNivelPai]=$this->arrayNiveis[$codNivelPai];            
    }      
    //chama recursivamente para todos os filhos
    foreach($this->arrayNiveis as $codNivel=>$atributos) {
      if ($atributos->codNivelPai==$codNivelPai) {
        $this->montaListaFilhos($codNivel);  
      }
    }
  }
  
  /*
   * Busca os niveis superiores na hierarquia
   * Retorna um array com os campos
   */
  function getHierarquiaPai() {
    $sql = "Select * from nivel order by codNivelPai";
    $resultado = mysql_query($sql);
    //faz array com todos os niveis
    while ($nivelResult = mysql_fetch_object($resultado)) {
      $arrayNiveis[$nivelResult->codNivel]=$nivelResult;
    }
    //Seleciona a hierarquia, criando os respectivos objetos nivel
    $nivelAtributos = $arrayNiveis[$this->codNivel];    
    while (!empty($nivelAtributos)) {
      $nivel = new Nivel();
      foreach($nivelAtributos as $campo=>$valor) {  $nivel->$campo=$valor;  }
      $pais[]=$nivel;
      //Proximo pai
      $nivelAtributos=$arrayNiveis[$nivel->codNivelPai];
    } 
       
    return $pais;
  }
  /*
  versao antiga
  function getHierarquiaPai() {
    $sql = "Select * from nivel Where codProximoNivel<=".$this->codNivel." order by codProximoNivel";
    $pais = new RDCLQuery($sql);

    return $pais->records;
  }
  */
  
  /*
   * Retorna o nivel que tem a flag setada para ser o nivel de relacionamento com alunos e professores
   */
  /* 
   * NAO é MAIS UTILIZADO PORQUE 
   * MAIS DE UM NIVEL PODE TER RELACIONAMENTO COM PESSOAS
   *    
   */       
  /*
  function getNivelRelacionamentoAlunosProfessores() {

    return new Nivel(1,"relacionaAlunosProfessores");
  }
  */
  /*
   * Se houver o campo abreviatura mostra, se nao mostra o campo nome 
   */
  function getCampoAbreviaturaOuNome($obj="") {
    if (empty($obj)) { $obj=$this; } //para funcionar tanto com chamadas estaticas como dinamicas
    if (!empty($obj->nomeFisicoCampoAbreviatura)) { 
      return $obj->nomeFisicoCampoAbreviatura;  
    }
    else {  
      return $obj->nomeFisicoCampoNome; 
    }
  }
  /*
   * Devolve o nivel que implementa comunidade
   */
  function getNivelComunidade() {
    return new Nivel(NIVEL_COMUNIDADE,'tipoNivel');
  }
  /*
   * Devolve todas as comunidades (para o administrador geral)
   */
  function getTodasComunidades() {
    $nivelComunidade = Nivel::getNivelComunidade();
    
    $sql = " Select ".ADMINISTRADOR_GERAL." AS userRole,C.* from ".$nivelComunidade->nomeFisicoTabela." C";

    return new RDCLQuery($sql);
  }
  /*
   * Indica se o nivel eh o de comunidade ou nao
   */
  function isNivelComunidade($obj="") {
    if (empty($obj)) { $obj = $this; }
    
    if ($obj->tipoNivel==NIVEL_COMUNIDADE) {  return 1;  }  else  {  }
  }
  
  /*
   * Retorna todos os registros da tabela (instancias)
   */
  function getRegistros($paiFiltro='') {

    $sql = 'SELECT * FROM '.$this->nomeFisicoTabela. ' NIVEL ';
    if (!empty($paiFiltro)) {
      if ($this->tipoRelacionamentoComNivelPai==2) {
        $sql.=' INNER JOIN '.$this->nomeFisicoTabelaRelacionamento.' RELACIONAMENTO ON (NIVEL.'.$this->nomeFisicoPK.'='.'RELACIONAMENTO.'.$this->nomeFisicoPK.')';
      }
      $sql.=' WHERE '.Nivel::getPK($paiFiltro->nivel).'='.$paiFiltro->codInstanciaNivel;  
    }
    
    //echo $sql;
    return new RDCLQuery($sql);
  }
	

  /**
	 *  Insere um instancia na tabela do nivel
	 *  Tambem cria uma instancia global para esta instancia
	 *  
	 *  Por padrão grava o nome; eventualmente grava outros campos   	 
   */
	function insereInstancia($nome,$outrosCampos='', $chavePai='' ) {
	  
  	/*
     * insere a instancia na tabela do nivel
     */     
		$sql = 'INSERT INTO '.$this->nomeFisicoTabela.'('.$this->nomeFisicoCampoNome;
		if (!empty($outrosCampos)) {
		  $fieldValues = ""; $fieldNames = "";
      while (list($campo,$valor)=each($outrosCampos) ) {
        if (!empty($campo)) { 
          $fieldNames.=",".$campo;   
          $fieldValues.=",".quote_smart($valor); //ja guarda os valores para completar a SQL
        }
      }    
    } 

    //acrescenta chave do pai para o caso tradicional de cardinalidade 1xN
    if (count($chavePai) && $this->tipoRelacionamentoComNivelPai==1 && $this->codNivelPai!=0) { 
      list($campo,$valor)=each($chavePai); 
      if (!empty($campo)) { 
        $fieldNames.=",".$campo;   
        $fieldValues.=",".quote_smart($valor); //ja guarda os valores para completar a SQL
      }
    }
    $sql.= $fieldNames.') ';		
    $sql.= 'VALUES ('.quote_smart($nome).$fieldValues.')';

    $sucesso = mysql_query($sql); 
    //echo '<BR>AGORA 261: '.$sql.mysql_error();
		if (!$sucesso) return 0;    

		//pega o codigo da instancia recem-inserida
		$codInstanciaNivel = mysql_insert_id();
    
    /*
     * INSERT NA TABELA DE RELACIONAMENTO N x M, quando apropriado (ex.areaunidade)
     */
    if ($this->tipoRelacionamentoComNivelPai==2 && $this->codNivelPai!=0) {
      $fieldNames=''; $fieldValues=''; //reseta variaveis auxiliares
      if (!empty($chavePai)) {
        list($campo,$valor)=each($chavePai);
        if (!empty($campo)) { 
          $fieldNames.=",".$campo;   
          $fieldValues.=",".quote_smart($valor); //ja guarda os valores para completar a SQL
        }
      }
      
      $sql = 'INSERT INTO '.$this->nomeFisicoTabelaRelacionamento.'('.$this->nomeFisicoPK;
      $sql.= $fieldNames.') ';		
      $sql.= 'VALUES ('.$codInstanciaNivel.$fieldValues.')';
      $sucesso = mysql_query($sql);
      //echo '<BR>'.$sql.mysql_error();
      if (!$sucesso) return 0;
      //pega o codigo da instancia recem-inserida, esta PK é que representara na instancia global
      $codInstanciaNivel = mysql_insert_id();
    }

    /*
     * insere a instancia global
     */     
		$sql = "INSERT INTO instanciaglobal(codNivel,codInstanciaNivel) VALUES (";
    $sql.= $this->codNivel.",".$codInstanciaNivel.")";
    
    $this->codInstanciaNivel = $codInstanciaNivel;

		return mysql_query($sql);
	}
	/**
	 *  Altera a instancia na tabela do nivel
	 *
	 */
	function alteraInstancia($cod,$nome,$abreviatura='',$chavePai='') {
			$sql = 'UPDATE '.$this->nomeFisicoTabela.' SET '.$this->nomeFisicoCampoNome.'='.quote_smart($nome);
			if (!empty($abreviatura)) {  $sql.=','.$this->nomeFisicoCampoAbreviatura.'='.quote_smart($abreviatura);   }
			if (!empty($chavePai)) {
        $pai = $this->getNivelAnterior(); 
        $sql.=','.$pai->getPK().'='.quote_smart($chavePai);   
      }
			$sql.= ' WHERE '.$this->nomeFisicoPK.'='.quote_smart($cod);
			return mysql_query($sql);
	}
	/** 
	 * Exclusao da instancia
	 */
	function excluiInstancia($cod) {
	  if ($this->relacionaPessoas()) {
	    $this->retiraParticipantesDaInstancia($cod);
	  }
		$sql = "DELETE FROM ".$this->nomeFisicoTabela." WHERE ".$this->nomeFisicoPK."=".quote_smart($cod);
		
    
		
    return mysql_query($sql);
	}

  /*
   * Retirar instancia inicial
   */   	      	
  function retiraParticipantesDaInstancia($cod){
  
   $sqlProfessor = "DELETE FROM ".$this->nomeFisicoTabelaRelacionamentoProfessores." WHERE ".$this->nomeFisicoPK."=".$cod;    
   $sqlAluno = "DELETE FROM ".$this->nomeFisicoTabelaRelacionamentoAlunos." WHERE ".$this->nomeFisicoPK."=".$cod;
   $sqlInstanciaIncial= "DELETE FROM instanciainicial WHERE codNivel=".$this->codNivel." AND codInstanciaNivel=".$cod;
  
 
   mysql_query($sqlProfessor);
   //echo mysql_error();
   mysql_query($sqlAluno);
   //echo mysql_error();
   mysql_query($sqlInstanciaIncial);
   //echo mysql_error();
   
  }	

  /**
   * Retorna a tabela de relacionamento com alunos
   */      
  function getTabelaRelacionamentoAlunos() {
    return $this->nomeFisicoTabelaRelacionamentoAlunos;
  }	
	/**
	 * Retorna a chave de relacionamento com alunos
	 */   		
	function getPKRelacionamentoAlunos() {
    return $this->nomeFisicoPKRelacionamentoAlunos;
  }  
	/**
	 * Retorna a chave de relacionamento com professores
	 */   		
	function getPKRelacionamentoProfessores() {
    return $this->nomeFisicoPKRelacionamentoProfessores;
  }
  /*
   * Niveis nos quais as pessoas relacionam-se formalmente 
   */   
  function getNiveisRelacionamentoFormal() {
    $sql = "SELECT * from nivel where tipoNivel=".NIVEL_RELACIONA." OR tipoNivel=".NIVEL_AGRUPA_RELACIONA;   
    $consulta = new RDCLQuery($sql);
    
    return $consulta;
  }

  /*
   * Retorna um RDCLQuery com as instancias do ivel
   * 
   */
  //old-style
  //function getInstancias($codInstanciaPai='',$pkNivelPai='') {
  function getInstancias($instanciaPai) {
     
    //Cardinalidade do relacionamento com nivel pai.
    //1 => 1xN
    //2 => NxM
    switch($this->tipoRelacionamentoComNivelPai) {
      case 1:  //1:n
        $statSQL = ' SELECT '.$this->nomeFisicoPK.' as chave,'.$this->nomeFisicoCampoNome.' as nome FROM '.$this->nomeFisicoTabela. ' NIVEL';
        $aliasPai='NIVEL';
        break;
      case 2:  //n:m
        $statSQL = ' SELECT RELACIONAMENTO.'.$this->nomeFisicoPKRelacionamento.' as chave,NIVEL.'.$this->nomeFisicoPK.' as chaveFraca, NIVEL.'.$this->nomeFisicoCampoNome.' as nome ';
        if (!empty($instanciaPai)) { //busca nome do pai, se preciso 
          $statSQL.=', PAI.'.$instanciaPai->nivel->nomeFisicoCampoNome.' as nomePai ';
        }
        $statSQL.= ' FROM '.$this->nomeFisicoTabela.' NIVEL INNER JOIN '.$this->nomeFisicoTabelaRelacionamento.' RELACIONAMENTO ';
        $statSQL.= ' ON (NIVEL.'.$this->nomeFisicoPK.' = RELACIONAMENTO.'.$this->nomeFisicoPK.')';
        if (!empty($instanciaPai)) {
          $statSQL.= ' INNER JOIN '.$instanciaPai->nivel->nomeFisicoTabela.' PAI ';
          $statSQL.= ' ON (PAI.'.$instanciaPai->nivel->nomeFisicoPK.' = RELACIONAMENTO.'.$instanciaPai->nivel->nomeFisicoPK.')';
        }
        $aliasPai='RELACIONAMENTO';
        break;
    }
    //old-style
    //if ($codInstanciaPai) { $statSQL.=' WHERE '.$pkNivelPai.' = '.$codInstanciaPai; }
    if (!empty($instanciaPai)) {    $statSQL.=' WHERE '.$aliasPai.'.'.$instanciaPai->nivel->getPK().' = '.$instanciaPai->codInstanciaNivel; }

    
	  $statSQL.=' ORDER BY nome ASC';// mostrar por ordem alfabetica
	  //echo $statSQL; 
    return new RDCLQuery($statSQL);
  }
  /*
   * Retorna a pk de fato, se eh a da tabela que implementa o nivel (1:N)
   * ou entao a chave de seu relacionamento com o pai (N:M)
   */
  function getPK($obj='') {
    if (empty($obj)) {  $obj=$this; }
    //o nome da chave PK do nivel dentro do subnivel depende de 
    //como é o relacionamento dele com o pai (avo do subnivel)
    switch($obj->tipoRelacionamentoComNivelPai) {
      case 1:  $chaveNivel= $obj->nomeFisicoPK; break;//O relacionamento com o nivel pai deste era 1:n
      case 2:  $chaveNivel= $obj->nomeFisicoPKRelacionamento; break;//O relacionamento com o nivel pai deste era n:m
      default:  $chaveNivel= $obj->nomeFisicoPK; //padrao é ser a propria PK, como no caso 1:N
    }
    return $chaveNivel;
  }

  /*
   * Verifica se este nível é do tipo que pode relacionar pessoas ou nao 
   */
  function relacionaPessoas() {
    /* o nivel relaciona pessoas */  
    if ($this->tipoNivel==NIVEL_RELACIONA)  {
      return true;
    }
    /* Este nivel é um subgrupo, relaciona pessoas de outros niveis
       mas em principio nao é necessário para este método 
    else if ($this->tipoNivel==NIVEL_SUBGRUPO) {
      return true;
    }    
    */
    /* Neste nivel a instancia pode eventualmente relacionar pessoas*/
    
    else if ($this->tipoNivel==NIVEL_AGRUPA_RELACIONA) {
      return true;
    }
    
    /* relacionamento informal */
    else if ( $this->tipoNivel==NIVEL_COMUNIDADE) {
      return true;
    }
    else {
      return false;
    }
    
  }

  /*
   * Verifica se algum nível desta instalação é do tipo que suporta temporalidade
   */
  function existeNivelTemporal() {
    $sql = "SELECT 1 from nivel where temporal=1 ";   
    $consulta = mysql_query($sql);
    
    return mysql_num_rows($consulta);
  
  }
  /*
   * Busca todas as instancias do nivel superior, 
   * em um nivel que se relaciona NxM com o nivel pai (nesse caso cada um dos relacionamentos é uma instancia global)   
   * algumas delas poderao ser relacionadas a instancia-filha 
   */
  function buscaTodosSuperiores($chaveFraca) {
    $nivelPai = $this->getNivelAnterior();

    $statSQL = ' SELECT DISTINCT NIVEL.'.$this->nomeFisicoPK.' as chaveFraca, NIVEL.'.$this->nomeFisicoCampoNome.' as nome ';
    $statSQL.= ', PAI.'.$nivelPai->nomeFisicoCampoNome.' as nomePai, PAI.'.$nivelPai->nomeFisicoPK.' as chaveFracaPai ';
    $statSQL.= ' FROM '.$nivelPai->nomeFisicoTabela.' PAI ';
    $statSQL.= ' LEFT OUTER JOIN '.$this->nomeFisicoTabelaRelacionamento.' RELACIONAMENTO ';
    $statSQL.= ' ON (PAI.'.$nivelPai->nomeFisicoPK.' = RELACIONAMENTO.'.$nivelPai->nomeFisicoPK.' AND RELACIONAMENTO.'.$this->nomeFisicoPK.'='.$chaveFraca.')';
    $statSQL.= ' LEFT OUTER JOIN '.$this->nomeFisicoTabela.' NIVEL ';
    $statSQL.= ' ON (NIVEL.'.$this->nomeFisicoPK.' = RELACIONAMENTO.'.$this->nomeFisicoPK.' AND NIVEL.'.$this->nomeFisicoPK.'='.$chaveFraca.')';
    //echo $statSQL;
     
    return new RDCLQuery($statSQL); 
  }
  /*
   * AJusta as instancias que sao do relacionamento entre um nivel NxM com o nivel Pai
   * (nesse caso é uma entidade fraca, pois a instancia global vem do relacionamento)   
   */
  function salvarRelacionamentos($codInstanciasPai,$chaveFraca) {
    $nivelPai = $this->getNivelAnterior();
  
    if ($this->tipoRelacionamentoComNivelPai!=2) { return true; } //somente niveis com tipo=2, que é NxM com Pai, podem alterar pais via tabela de relacionamento
    //verifica variaveis para evitar o delete inapropriado
    if (empty($chaveFraca) || empty($this->nomeFisicoPK)) { return; } 

    //TODO: poderia ser colocado em transacao, como sao dois comandos em conjunto
    //Depende de padronizar tabelas filho em INNODB, hoje é o unico 
        
    //Primeiro, exclui todos os registros        
    $sql = 'DELETE FROM '.$this->nomeFisicoTabelaRelacionamento.' where '.$this->nomeFisicoPK.'='.quote_smart($chaveFraca);
    //echo $sql;
    mysql_query($sql);
    if (mysql_errno()) { echo 'erro!'; }

    //Depois, inclui todos os pais selecionadas, com chave do pai e chave do filho
    if (!empty($codInstanciasPai)) {
      $codInstanciaNivel = quote_smart($this->codInstanciaNivel);       
      foreach ($codInstanciasPai as $codPai) {      
        $sql = 'INSERT INTO '.$this->nomeFisicoTabelaRelacionamento.' ('.$nivelPai->nomeFisicoPK.','.$this->nomeFisicoPK.') VALUES ('.quote_smart($codPai).','.$chaveFraca.');';
        //echo $sql;
        mysql_query($sql);
        if (mysql_errno()) { echo 'erro!'; }
      }
    }
    return true;  
  }  
}
?>
