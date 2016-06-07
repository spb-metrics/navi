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
include($caminhoBiblioteca.'/pessoa.inc.php');
include($caminhoBiblioteca.'/professor.inc.php');
include($caminhoBiblioteca.'/aluno.inc.php');
*/

function listaPapel() {
	$sql="SELECT * FROM papelpessoa WHERE 1";

	$selectPapeis=mysql_query($sql); 
	
	
	while ($papel=mysql_fetch_object($selectPapeis)) {

    if(!empty($papel->tabelaAtributo)) {			
		  $sql="SELECT * FROM ".$papel->tabelaAtributo." WHERE 1";
			$selectAtributo=mysql_query($sql);
      //descricao, visibilidade e interacao do usuario com a plataforma 
			while($atributo=mysql_fetch_array($selectAtributo)){
  			$papelAtributo[$papel->abrevPapel][$atributo[$papel->chaveAtributo]]['descricao']=$atributo[$papel->descAtributo];
  			$papelAtributo[$papel->abrevPapel][$atributo[$papel->chaveAtributo]]['visivel']=$atributo['visivel'];
  			$papelAtributo[$papel->abrevPapel][$atributo[$papel->chaveAtributo]]['interage']=$atributo['interage'];
  			$papelAtributo[$papel->abrevPapel][$atributo[$papel->chaveAtributo]]['interage']=$atributo['interage'];
  			$papelAtributo[$papel->abrevPapel][$atributo[$papel->chaveAtributo]]['professorPodeGerenciar']=$atributo['professorPodeGerenciar'];        
  		}
		}
    else {
				$papelAtributo[$papel->abrevPapel][$papel->abrevPapel]['descricao']=$papel->papel;
    }

	}

  return $papelAtributo;
}

/*
 * Adiciona novos papeis, vinculando-os a uma autoridade
 */

function adicionaPapel($descPapel, $autoridade, $interage, $visivel, $professorPodeGerenciar) {

    $sql='SELECT * FROM papelpessoa  WHERE abrevPapel='.quote_smart($autoridade);
    $obj = new RDCLQuery($sql);
    
    $objPapel=$obj->records[0];

    $sql='INSERT into ' .$objPapel->tabelaAtributo .' ('.$objPapel->descAtributo.",interage,visivel,professorPodeGerenciar) value (".quote_smart($descPapel).",".quote_smart($interage).",".quote_smart($visivel).",".quote_smart($professorPodeGerenciar).")" ; 
      
    mysql_query($sql);
    
    return (! mysql_errno());
}

/*
 * Altera papeis existentes
 */
function alteraPapel($codPapel, $descPapel, $autoridade, $interage, $visivel, $professorPodeGerenciar) {

    $sql='SELECT * FROM papelpessoa  WHERE abrevPapel='.quote_smart($autoridade);
    $obj = new RDCLQuery($sql);    
    $objPapel=$obj->records[0];
    
    $sql='UPDATE ' .$objPapel->tabelaAtributo .' SET ';
    $sql.=$objPapel->descAtributo.'='.quote_smart($descPapel);
    $sql.=',interage='.quote_smart($interage);
    $sql.=',visivel='.quote_smart($visivel); 
    $sql.=',professorPodeGerenciar='.quote_smart($professorPodeGerenciar); 
    $sql.=' WHERE '.$objPapel->chaveAtributo.'='.quote_smart($codPapel);
      
    mysql_query($sql);
    
    return (! mysql_errno());
}
/*
 * Exclui papeis existentes
 */
function excluiPapel($codPapel,$autoridade){

  $sql='SELECT * FROM papelpessoa  WHERE abrevPapel='.quote_smart($autoridade);
  $obj = new RDCLQuery($sql);    
  $objPapel=$obj->records[0];

  $sql='DELETE FROM ' .$objPapel->tabelaAtributo;
  $sql.=' WHERE '.$objPapel->chaveAtributo.'='.quote_smart($codPapel);
  $sql.= ' LIMIT 1';
    
  mysql_query($sql);

  return (! mysql_errno());
}


/*
 *  Atualiza os atributos de interacao das instancias iniciais, caso um papel 
 *  tenha sido atualizado    
 *   
 *   TEMPLATE SQL UTILIZADO:
 *   UPDATE   instanciainicial II, professor_turma PT, professor P, tipo_professor TP      
 *   SET      II.interage=TP.interage      
 *   WHERE II.codPessoa = P.COD_PESSOA
 *   AND P.COD_PROF = PT.COD_PROF
 *   AND PT.codTipoProfessor = TP.codTipoProfessor
 *   AND PT.codTipoProfessor=1       
 *
 */   
function atualizaInstanciaInicial($codPapel, $autoridade) {

  $sql='SELECT * FROM papelpessoa  WHERE abrevPapel='.quote_smart($autoridade);
  $obj = new RDCLQuery($sql);
  $objPapel=$obj->records[0];

  $todosNiveisRelacionamento = Nivel::getNiveisRelacionamentoFormal();  
  $ok = true;
  
  //Para cada nivel de relacionamento, ajusta o papel nas respectivas instancias iniciais
  if (!empty($todosNiveisRelacionamento->records)) {
    for($h=0;$h<count($todosNiveisRelacionamento->records);$h++) {
      //Cria o objeto com os atributos da consulta ref ao nivel de relacionamento
      $nivel = new Nivel();
      foreach($todosNiveisRelacionamento->records[$h] as $campo=>$valor) {
        $nivel->$campo=$valor;
      }

      if ($autoridade=='ANB') {  /* PROFESSOR */
        $tab = TABELAPROFESSOR;
        $tabRelacionamento = $nivel->nomeFisicoTabelaRelacionamentoProfessores;
        $pkRelacionamento = $nivel->nomeFisicoPKRelacionamentoProfessores;
        $pkAutoridade = PK_PROFESSOR;            
      }
      else if ($autoridade=='NA') { /*  ALUNO  */
        $tab = TABELA_ALUNO;
        $tabRelacionamento = $nivel->nomeFisicoTabelaRelacionamentoAlunos;            
        $pkRelacionamento = $nivel->nomeFisicoPKRelacionamentoAlunos;
        $pkAutoridade = PK_ALUNO;            
      }              

      $sql = 'UPDATE   instanciainicial II, '.$tabRelacionamento.' RELACIONAMENTO, '.$tab.' AUTORIDADE, '.$objPapel->tabelaAtributo.' PAPEL';      
      $sql.= ' SET      II.interage=PAPEL.interage';      
      $sql.= ' WHERE II.codPessoa = AUTORIDADE.'.PK_PESSOA;
      $sql.= ' AND AUTORIDADE.'.$pkAutoridade.' = RELACIONAMENTO.'.$pkRelacionamento;
      $sql.= ' AND RELACIONAMENTO.'.$pkRelacionamento.' = PAPEL.'.$objPapel->chaveAtributo;
      $sql.= ' AND PAPEL.'.$objPapel->chaveAtributo.'='.quote_smart($codPapel);        
      
      mysql_query($sql);
      $ok  = ( $ok && !mysql_errno()); //se alguma das queries retornar erro
          
    }
  }
  
  return $ok;
}

/*
 * Verifica se ha alguma pessoa ocupando o relacionamento
 * (se houver, nao permitira delecao) 
 */   
function isPapelUtilizado($codPapel, $autoridade) {
  /*  Busca o nome da tabela de metadados que contem os papeis*/
  $sql='SELECT * FROM papelpessoa  WHERE abrevPapel='.quote_smart($autoridade);
  $obj = new RDCLQuery($sql);
  $objPapel=$obj->records[0];

  $todosNiveisRelacionamento = Nivel::getNiveisRelacionamentoFormal();  
  $ok = false;
  
  //Para cada nivel de relacionamento, ajusta o papel nas respectivas instancias iniciais
  if (!empty($todosNiveisRelacionamento->records)) {
    for($h=0;$h<count($todosNiveisRelacionamento->records);$h++) {
      //Cria o objeto com os atributos da consulta ref ao nivel de relacionamento
      $nivel = new Nivel();
      foreach($todosNiveisRelacionamento->records[$h] as $campo=>$valor) {
        $nivel->$campo=$valor;
      }

      if ($autoridade=='ANB') {  /* PROFESSOR */
        $tabRelacionamento = $nivel->nomeFisicoTabelaRelacionamentoProfessores;
      }
      else if ($autoridade=='NA') { /*  ALUNO  */
        $tabRelacionamento = $nivel->nomeFisicoTabelaRelacionamentoAlunos;            
      }              

      $sql= 'SELECT 1 from '.$tabRelacionamento.'  WHERE '.$objPapel->chaveAtributo.'='.quote_smart($codPapel);
      //echo $sql;     
      $result = mysql_query($sql);
      if (mysql_num_rows($result)) { $ok = true; break; } //encontrou registros usando o papel solicitado
          
    }
  }
  
  return $ok;
}

/*
 * Retorna a instancia inicial para comparacao em caso de delecao da pessoa
 */
function instanciaNivelInicial($codPessoa) {
  
  //$sql = "Select userRole, mostraPassado,codNivel,codInstanciaNivel from instanciainicial Where codPessoa=".quote_smart($codPessoa);        
  $sql = "Select  * from instanciainicial Where codPessoa=".quote_smart($codPessoa); 
  //echo $sql; die;   
  $obj = new RDCLQuery($sql);
  
  return $obj->records[0];
}

?>