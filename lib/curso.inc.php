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

define("PAGINA_ALUNOS",40);
include_once($caminhoBiblioteca."/pessoa.inc.php");
include($caminhoBiblioteca."/aluno.inc.php");
include($caminhoBiblioteca."/professor.inc.php");


/* 
 *Busca os alunos deste nível 
 *
 * Supoe a tabela de Pessoas e PK como sendo: Pessoa (COD_PESSOA)
 */
function listaAlunos($numPagina="",$membrosAtivos=1,$soUsuariosOnline='',$codInstanciaNivel='', $nivelAtual='',$listaInvisiveis='') {
  if(empty($nivelAtual)){	 $nivelAtual = getNivelAtual();   }
	
  //por padrao, verifica se é comunidade e portanto somente mostra membros ativos no nivel
  //se for setado para nao mostrar, entao nao filtra 
  if ($membrosAtivos) { $membrosAtivos = $nivelAtual->isNivelComunidade(); }
  $pk = Aluno::getPKRelacionamento($nivelAtual);
  //Somente retorna se este nivel possuir relacionamento com alunos

  if(empty($codInstanciaNivel)) {	$codInstanciaNivel=getCodInstanciaNivelAtual(); }

  if (!empty($pk)) {

    $strSQL = 'SELECT P.alive,P.COD_PESSOA, P.ultimoAcesso, P.NOME_PESSOA, P.EMAIL_PESSOA,P.USER_PESSOA, P.DESC_PERFIL, P.FOTO, AT.codTipoAluno,TA.*, A.'.PK_ALUNO.
      ' FROM '.Aluno::getTabela().' A, '.Aluno::getTabelaRelacionamento($nivelAtual).' AT, pessoa P, tipo_aluno TA '.
      ' WHERE A.COD_PESSOA = P.COD_PESSOA AND A.'.PK_ALUNO.' = AT.'.$pk.' AND '.
      ' AT.codTipoAluno=TA.codTipoAluno AND '.
      '		 AT.'.$nivelAtual->nomeFisicoPK.' = '.$codInstanciaNivel  ;

    if ($membrosAtivos) { $strSQL .= " AND ativo=1 "; }
    if ($soUsuariosOnline == '1') {  $strSQL .= " AND ((".time()." - alive) <" .USER_TIMEOUT.")";}

//mostra apenas pessoas com atributo de visibilidade
    if (!$listaInvisiveis) {    $strSQL.= ' AND TA.visivel=1 ';  }

    $strSQL .= " ORDER BY P.NOME_PESSOA";
    if (!empty($numPagina)) {
      $inicio = ($numPagina-1) * PAGINA_ALUNOS;
      $strSQL.=" LIMIT ".$inicio.",".PAGINA_ALUNOS;
    }
    //echo $strSQL;
    return mysql_query($strSQL);
  } 
  else {
    return "";
  }
}

/*======================================================================================================
// Lista os professores desse nivel de relacionamento (ou comuinidade)
*/
function listaProfessores($membrosAtivos=1,$soProfOnline='',$codInstanciaNivel='',$nivel='',$listaInvisiveis='') {

	//obtem a nome da tabela e o campo chave da tabela de relacionamento
	//entre professor e as instancias do nivel atual
  if(empty($nivel)){ $nivel = getNivelAtual();   }
	
	$tblRel = Professor::getTabelaRelacionamento($nivel);
	$pkRel = Professor::getPKRelacionamento($nivel);
	$nivelPK = $nivel->nomeFisicoPK;
	

  //por padrao, verifica se é comunidade e portanto somente mostra membros ativos no nivel
  //se for setado para nao mostrar, entao nao filtra 
  if ($membrosAtivos) { $membrosAtivos = $nivel->isNivelComunidade(); }

  if(empty($codInstanciaNivel)){ $codInstanciaNivel = getCodInstanciaNivelAtual(); }
  
  $strSQL = "SELECT P.alive,P.COD_PESSOA, P.NOME_PESSOA, P.USER_PESSOA, PT.codTipoProfessor, TP.*, P.EMAIL_PESSOA, P.FOTO, P.DESC_PERFIL, P.FOTO_REDUZIDA, PR.COD_PROF".
    " FROM ".Professor::getTabela()." PR, ".$tblRel." PT, pessoa P, tipo_professor TP".
    " WHERE PR.COD_PESSOA = P.COD_PESSOA AND PR.".$pkRel." = PT.".$pkRel." AND".
    " PT.codTipoProfessor=TP.codTipoProfessor AND ".
    "	PT.".$nivelPK." = ". quote_smart($codInstanciaNivel);
  if ($membrosAtivos){ $strSQL .= " AND ativo=1 "; }

  if ($soProfOnline == '1') {  $strSQL .= " AND ((".time()." - alive) <" .USER_TIMEOUT.")";}
  
  //mostra apenas pessoas com atributo de visibilidade
  if (!$listaInvisiveis) {    $strSQL.= ' AND TP.visivel=1 ';  }
  
  $strSQL.= ' ORDER BY P.NOME_PESSOA';
  //echo $strSQL;
  return mysql_query($strSQL);
}

/*
 * Lista todos os integrantes associados ao nivel atual, recursivamente
 *  
 * @param $contagem indica se deve retornar apenas a contagem ou a lista propriamente dita   
 */ 
 /* VERSAO EM UMA CONSULTA UNICA
function listaTodosIntegrantesAntiga($pessoa,$membrosAtivos=1,$online='',$contagem=0,$numPagina=0,$codInstanciaNivel='',$nivel='') {
  
  $tabelasSuperiores='';
  $juncoes='';
  //inicia o primeiro nivel a ser construido na consulta com o nivel atual
  
  if (empty($nivel)) { $nivel=getNivelAtual(); }  
    
  if(empty($codInstanciaNivel)) {
	  //Seleciona a instancia de nivel atual na consulta
	  $whereInstanciaAtual = InstanciaNivel::getTablePk($nivel).".".$nivel->getPk()."=".getCodInstanciaNivelAtual();
  }
  else {
	  //Seleciona a instancia de nivel passado para consulta
	  $whereInstanciaAtual = InstanciaNivel::getTablePk($nivel).".".$nivel->getPk()."=".$codInstanciaNivel;
  }

  //Constroi a lista de tabelas e as juncoes, no formato de clausula where
  $subNiveis = $nivel->getHierarquiaFilho();
  
  $juncaoPessoa=''; 
  $juncoes=InstanciaNivel::getTablePk($nivel); //a primeira tabela é a atual nas juncoes 
  
  //coloca as tabelas dos filhos e as respectivas juncoes
  foreach($subNiveis as $n) {
  
	  //verifica a juncao com o pai e relacionamento entre pessoas
    $pai = $nivel->listaFilhos[$n->codNivelPai];
             
    $juncoesExternas='';
    
      if (!empty($pai)) { //apenas os niveis que sao filhos deste nível entram, pois serão as junções da consulta             
        //junção com a tabela propriamente dita do nivel,
        //foi alterada para ficar sempre externa
        $juncoes.=' LEFT JOIN ';  
        
        //Juncao do nivel com seu pai. 
        $juncoes.=InstanciaNivel::getTablePk($n).' ON (';
        $juncoes.=InstanciaNivel::getTablePk($n).'.'.$pai->getPK().'=';           
        $juncoes.=InstanciaNivel::getTablePk($pai).'.'.$pai->getPK(); 
        $juncoes.=') ';       
      }

      if ($n->relacionaPessoas()) {
        //tabela de relacionamento da pessoa com o nivel 
        $juncoesExternas=' LEFT JOIN '.$pessoa->getTabelaRelacionamento($n).' ON (';
        $juncoesExternas.=$pessoa->getTabelaRelacionamento($n).'.'.$n->getPK().'=';
        $juncoesExternas.=InstanciaNivel::getTablePk($n).'.'.$n->getPK();
        $juncoesExternas.=') ';
      
        //tabela do papel renomeada (nome da tabela+nivel)
        $papelRenomeado = $pessoa->getTabela().InstanciaNivel::getTablePk($n);
        $juncoesExternas.=' LEFT JOIN '.$pessoa->getTabela().' '.$papelRenomeado. ' ON (';
        $juncoesExternas.=$papelRenomeado.'.'.$pessoa->getPK().'=';
        $juncoesExternas.=$pessoa->getTabelaRelacionamento($n).'.'.$pessoa->getPKRelacionamento($n);
        $juncoesExternas.=') ';
        //condicao lógica para filtrar as pessoas
        $juncaoPessoa.=$papelRenomeado.'.'.PK_PESSOA.'=P.'.PK_PESSOA.' OR ';
      }
        
    if (!empty($juncoesExternas)) {
      $juncoes.=$juncoesExternas;
    }
 	    
  }

  //Juncao de pelo menos uma das tabelas de relacionamento com a tabela de pessoas
  //$juncoes.=$pessoa->getTabela().'.'.PK_PESSOA.'=P.'.PK_PESSOA;
  $juncaoPessoa = rtrim($juncaoPessoa,' OR ');
  $juncoes.='INNER JOIN '.TABELA_PESSOA. ' P ON ('.$juncaoPessoa.')'; 
  
  //por padrao, verifica se é comunidade e portanto somente mostra membros ativos no nivel
  //se for setado para nao mostrar, entao nao filtra 
  if ($membrosAtivos) { $membrosAtivos = $nivel->nivelComunidade; }

	//obtem a nome da tabela e o campo chave da tabela de relacionamento
	//entre professor e as instancias do nivel pai
   
	//$tblRel  = $pessoa->getTabelaRelacionamento($nivel); 
	//$pkRel   = $pessoa->getPKRelacionamento($nivel); 
	//$nivelPK = $nivel->getPk(); 
  

  $strSQL = "SELECT DISTINCT ";
  if (!$contagem) {  
    $strSQL.="  P.alive, P.COD_PESSOA, P.NOME_PESSOA, P.USER_PESSOA, ";
    $strSQL.=" P.EMAIL_PESSOA, P.FOTO, P.DESC_PERFIL, P.FOTO_REDUZIDA ";
  }
  else {
    $strSQL .= " count(distinct P.COD_PESSOA) as numAlunos";
  }  
  $strSQL.=' FROM '.$juncoes;

  $strSQL.=' WHERE '.$whereInstanciaAtual;  
  //se for professor acrescenta a juncao com a tabela de tipo de professor
  //if ($pessoa->userRole==PROFESSOR) {  $strSQL.=" AND REL.codTipoProfessor=TP.codTipoProfessor "; }
  
  if ($membrosAtivos) {  $strSQL .= " AND ativo=1 "; }
  if ($online) {  $strSQL .= " AND ((".time()."-alive)<".USER_TIMEOUT.")" ; }
  
  $strSQL.=' ORDER BY P.NOME_PESSOA';
    
  //se usar paginacao busca somente a pagina correta
  if (!empty($numPagina)) {
    $inicio = ($numPagina-1) * PAGINA_ALUNOS;
    $strSQL.=" LIMIT ".$inicio.",".PAGINA_ALUNOS;
  }
  //echo $strSQL; 
  $result =  mysql_query($strSQL);
  //echo 'erro: '.mysql_error();
  
  return $result;

}
*/

/* 
 * Lista todos os integrantes associados ao nivel atual, recursivamente, visiveis ou nao
 * 
 * Separa cada "ramo" da hierarquia sistemica em uma consulta, 
 * para otimizar (retira a juncao com 'or' para a tabela pessoa)    
 * 
 *
 * @param $contagem indica se deve retornar apenas a contagem ou a lista propriamente dita   
 */ 
function listaTodosIntegrantes($pessoa,$membrosAtivos=1,$online='',$contagem=0,$numPagina=0,$codInstanciaNivel='',$nivel='')  {
  
  //seleciona o nivel atual, caso nao seja fornecido  
  if (empty($nivel)) { $nivel=getNivelAtual(); }  
    
  if(empty($codInstanciaNivel)) {
    //Seleciona a instancia de nivel atual na consulta, se nao for fornecida
    $codInstanciaNivel = getCodInstanciaNivelAtual();
  }
  
  //Seleciona a instancia de nivel passado para consulta
  
  //Constroi a projecao, a ser usada em cada consulta
  //por padrao, verifica se é comunidade e portanto somente mostra membros ativos no nivel
  //se for setado para nao mostrar, entao nao filtra 
  if ($membrosAtivos) { $membrosAtivos = $nivel->nivelComunidade; }

  $projecao = "SELECT DISTINCT ";
  if (!$contagem) {  
    $projecao.="  P.alive, P.COD_PESSOA, P.NOME_PESSOA, P.USER_PESSOA, ";
    $projecao.=" P.EMAIL_PESSOA, P.FOTO, P.DESC_PERFIL, P.FOTO_REDUZIDA ";
  }
  else {
    $projecao .= " count(distinct P.COD_PESSOA) as numAlunos";
  }  
  
  //Constoi clausula WHERE, a ser usado em cada consulta. 
  //INclui filtro da instancia atual se usuarios online/ativo
  $where  =' WHERE '.InstanciaNivel::getTablePk($nivel).".".$nivel->getPk()."=".$codInstanciaNivel;    
  if ($membrosAtivos) {  $where .= " AND ativo=1 "; }
  if ($online) {  $where.= " AND ((".time()."-alive)<".USER_TIMEOUT.")" ; }


  //Recupera os niveis subordinados
  $subNiveis = $nivel->getHierarquiaFilho();
  
  //inicializada com o nivel atual
  $listaJuncao=array();
  
  $strSQL='';   //Variavel que armazena a consula SQL final
  $first=1; 	//a partir da segundo consulta, tem que colocar o UNION

  //coloca as tabelas dos filhos e as respectivas juncoes, ate chegar a um nivel de relacionamento,
  //onde reinicia uma nova consulta e une as anteriores com UNION
  foreach($subNiveis as $n) {
    //chegou a um nivel que relaciona as pessoas, vamos construir a consulta
    if ($n->relacionaPessoas()) {

      if (!$first) { $strSQL.= "\n UNION \n";  } else { $first=0; } 

      //Inicia a consulta com as projecoes e a primeira tabela é a do nivel atual
      $strSQL.= $projecao.' FROM '.InstanciaNivel::getTablePk($nivel);  

      //busca os pais e armazena os niveis em um array
      //que indica, em cada consulta do union, quais os niveis envolvidos no "ramo"
      //inicializa com o proprio nivel
      $listaJuncao = array();
      $listaJuncao[] = $n;
      $codPaiTemp=$n->codNivelPai; 
      while($codPaiTemp) {
        $paiTemp = $nivel->listaFilhos[$codPaiTemp];
        //$listaJuncao[] = $paiTemp;
        $listaJuncao = array_merge(array(0=>$paiTemp),$listaJuncao);
        $codPaiTemp = $paiTemp->codNivelPai;
      }
      
      //junção com as tabelas do ramo de  niveis
      foreach($listaJuncao as $foo=>$nivelJuncao) {          
        //o primeiro nivel, sem pai, ja esta na lista de tabelas
        //se o primeiro nivel tambem tiver pais, nao queremos exibir
        if (!empty($nivel->listaFilhos[$nivelJuncao->codNivelPai])) {  
          $pai = $nivel->listaFilhos[$nivelJuncao->codNivelPai];

          $strSQL.=' INNER JOIN ';          
          //Juncao do nivel com seu pai. 
          $strSQL.=InstanciaNivel::getTablePk($nivelJuncao).' ON (';
          $strSQL.=InstanciaNivel::getTablePk($nivelJuncao).'.'.$pai->getPK().'=';           
          $strSQL.=InstanciaNivel::getTablePk($pai).'.'.$pai->getPK(); 
          $strSQL.=') ';       
        }

      }

      //tabela de relacionamento da pessoa com o nivel 
      $strSQL.=' INNER JOIN '.$pessoa->getTabelaRelacionamento($n).' ON (';
      $strSQL.=$pessoa->getTabelaRelacionamento($n).'.'.$n->getPK().'=';
      $strSQL.=InstanciaNivel::getTablePk($n).'.'.$n->getPK();
      $strSQL.=') ';
      
      //tabela do papel renomeada (nome da tabela+nivel)
      $papelRenomeado = $pessoa->getTabela().InstanciaNivel::getTablePk($n);
      $strSQL.=' INNER JOIN '.$pessoa->getTabela().' '.$papelRenomeado. ' ON (';
      $strSQL.=$papelRenomeado.'.'.$pessoa->getPK().'=';
      $strSQL.=$pessoa->getTabelaRelacionamento($n).'.'.$pessoa->getPKRelacionamento($n);
      $strSQL.=') ';

      //juncao com tabela de pessoa
      $strSQL.=' INNER JOIN '.TABELA_PESSOA. ' P ON ('.$papelRenomeado.'.'.PK_PESSOA.'=P.'.PK_PESSOA.')'; 

      //falta juncao com tabela de tipo para pegar somente pessoas com atributo de visibiilidade
      
      
      
      //coloca where para filtrar apenas instancia atual, e online/ativo (se for o caso)
      $strSQL.=$where;

      //seta reinicio da consulta
      $novaConsulta=1;
    }	    
  }
 

  //acrescenta ordenacao pelo nome das pessoas ao final dos unions,
  //caso nao seja consulta de contagem
  if (!$contagem) {
    $strSQL.=' ORDER BY NOME_PESSOA ASC';
  }
    
  //se usar paginacao busca somente a pagina correta
  if (!empty($numPagina)) {
    $inicio = ($numPagina-1) * PAGINA_ALUNOS;
    $strSQL.=" LIMIT ".$inicio.",".PAGINA_ALUNOS;
  }
  //echo $strSQL; 
  $result =  mysql_query($strSQL);
  //echo '<BR>erro: '.mysql_error();
  
  return $result;

}

//======================================================================================================
// tools/menus.php

function listaMenu($cod_turma){
  if ($cod_turma == ""){
    $strSQL = "SELECT M.COD_MENU, M.DESC_MENU FROM menu M";
  }
  return mysql_query($strSQL);					
}

//======================================================================================================
// disciplina_menu.asp
// Retorna os menus que estão disponiveis para aquela turma

function editaMenu( $cod_turma, $cod_menu ){
  if ( $cod_menu != ""){
      $strSQL = "SELECT * FROM turma TU, menu_turma MT, menu ME".
	" WHERE TU.COD_TURMA = MT.COD_TURMA AND MT.COD_MENU = ME.COD_MENU AND TU.COD_TURMA = '" . $_SESSION["COD_TURMA"] ."'".
	" ORDER BY MT.COD_MENU";
				
      return mysql_query($strSQL);					
    }
  else
    return false;
}
//======================================================================================================

function tipoProfessor($codTipoProfessor){
  $strSQL = "SELECT imagemTipoProfessor FROM tipo_professor  WHERE codTipoProfessor=".$codTipoProfessor."";
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);

  return $linha["imagemTipoProfessor"];
}


?>