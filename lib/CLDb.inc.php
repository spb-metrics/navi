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
* Query manual 
* Erro: resultado da query
* Records: array de stdclass. Os atributos sao os campos da query.
*/
ini_set("memory_limit","512M");
class RDCLQuery {

  var $erro,$records;

  /*
   * Construtor
   */
  function RDCLQuery($sql,$start="",$numRows="") {
    if (empty($sql)) { return ""; }

    $this->erro=0; 
    $this->records = array();

    if (!empty($start)) {
      $sql.= " LIMIT ".$start.",".$numRows;
    }
    
    $result = mysql_query($sql);
    
    //fica melhor nao percorrer o result quando ha erro
    if (mysql_errno()) {
      $this->erro=mysql_error();
      echo "-----------------------";
      echo $sql;
      echo mysql_error();
      //debug_print_backtrace();
      echo "-----------------------";
    }
    else {
      //Retorna todos os objetos em um array
      while ($tupla = mysql_fetch_object($result)) {
        $this->records[] = $tupla;
      }
    }

  }
  /*
   * Constroi um combo
   */
  function combo($param) {

    $ret='<select name='.$param['name'].' id='.$param['id'];
    if (!empty($param['onChange'])) { $ret.=' onChange="'.$param['onChange'].'" '; }
     
    if (!$param['static']) { //chamada estatica
      if (!empty($this->records)) {
        $ret.=' >'; //fecha o select e coloca os options
        foreach($this->records as $reg) {
          if ($param['optionSelected']==$reg->$param['optionValue']) { $selected='selected'; } else { $selected='';}
          $ret.='<option value="'.$reg->$param['optionValue'].'" '.$selected.'>'.$reg->$param['optionName'].'</option>';
        }
      }
      else { 
        $ret.=' disabled>'; //Fecha colocando disabled, pois nao ha opcoes
      }
    }
    else { 
      $ret.=' disabled>'; //Fecha colocando disabled, pois foi uma chamada estatica apenas para exibir o widget
    } 

    $ret.='</select>';

    return $ret;
  }
  // Cria um objeto simples
  // usado com RDCLRow
  function createSimpleObject(&$obj,$indice=0) {
		if (!empty($this->records[$indice])) {
			foreach($this->records[$indice] as $campo => $valor) {
				 $obj->$campo = $valor;
			}
		}
  }

}



/*  
 * Lida com Registros individuais
 */
class RDCLRow {
  
  function RDCLRow($tabela,$chave,$valorChave) {
    $obj = new RDCLQuery("select * from ".$tabela." where ".$chave."=".quote_smart($valorChave)); 
    //Cria para este objeto atributos do registro em questao...
    $obj->createSimpleObject($this);
  }
}


// Quote variable to make safe 
function quote_smart ($value ) {
 // Stripslashes 
 if (get_magic_quotes_gpc()) { 
   $value = stripslashes ($value );
 } 
 // Quote if not integer 
 if (! is_numeric ($value )) { 
   $value ="'" .mysql_real_escape_string ($value ) . "'" ;
 } 
 return $value ;
}

/* fundamental */
function note($varArray) {
 echo "<PRE>"; print_r($varArray);
}

function formataUltimoAcesso($acesso){
  $arrayUltimoAcesso = explode ("-",$acesso); 	
  $arrayTime = explode (" ",$arrayUltimoAcesso[2]);
  $ultimoAcesso = $arrayTime[0]."/".$arrayUltimoAcesso[1]."/".$arrayUltimoAcesso[0]." às ".$arrayTime[1];
  return $ultimoAcesso;
}

//function consultaAlunoTurmas($busca,$nivel){
function consultaAlunoTurmas($busca){
	if (is_numeric($busca)){
		$filtro = " p.cod_pessoa = ".$busca."";
	} else {
		$filtro = " p.nome_pessoa like '%".$busca."%' ";
	}

  //quem sabe traduzir para uma abordagem multinivel, buscando nos niveis de relacionamento e comunidade
  //template sql para incluir o nivel de comunidade:
  /*
SELECT p.cod_pessoa AS 'Codigo Pessoa', p.nome_pessoa AS 'Nome Pessoa', p.ultimoAcesso AS 'Ultimo Acesso', prof.cod_prof AS 'Codigo Professor', a.cod_al AS 'Codigo Aluno', c1.desc_curso AS 'Curso do Aluno', d1.desc_dis AS 'Disciplina do Aluno', t1.nome_turma AS 'Nome da Turma do Aluno', c2.desc_curso AS 'Curso do Professor', d2.desc_dis AS 'Disciplina do Professor', t2.nome_turma AS 'Nome da Turma do Professor', ct.nome AS 'Comunidade'
FROM pessoa AS p
LEFT OUTER JOIN aluno AS a ON ( p.cod_pessoa = a.cod_pessoa ) 
LEFT OUTER JOIN aluno_turma AS at ON ( a.cod_al = at.cod_al ) 
LEFT OUTER JOIN turma AS t1 ON ( at.cod_turma = t1.cod_turma ) 
LEFT OUTER JOIN disciplina AS d1 ON ( t1.cod_dis = d1.cod_dis ) 
LEFT OUTER JOIN curso AS c1 ON ( c1.cod_curso = d1.cod_curso ) 
LEFT OUTER JOIN professor AS prof ON ( p.cod_pessoa = prof.cod_pessoa ) 
LEFT OUTER JOIN professor_turma AS pt ON ( prof.cod_prof = pt.cod_prof ) 
LEFT OUTER JOIN turma AS t2 ON ( pt.cod_turma = t2.cod_turma ) 
LEFT OUTER JOIN disciplina AS d2 ON ( t2.cod_dis = d2.cod_dis ) 
LEFT OUTER JOIN curso AS c2 ON ( c2.cod_curso = d2.cod_curso ) 
LEFT OUTER JOIN alunocomunidade AS ac ON ( ac.cod_al = a.cod_al ) 
LEFT OUTER JOIN professorcomunidade AS pc ON ( pc.cod_prof = prof.cod_prof ) 
LEFT OUTER JOIN comunidadetematica AS ct ON ( ct.codcomunidadetematica = ac.codcomunidadetematica
OR ct.codcomunidadetematica = pc.codcomunidadetematica ) 
WHERE p.cod_pessoa =1
ORDER BY p.nome_pessoa
LIMIT 0 , 100


  */

  $strSQL ="SELECT	DISTINCT	p.foto_reduzida as 'foto' , p.cod_pessoa as 'Codigo Pessoa',p.nome_pessoa as 'Nome Pessoa',p.ultimoAcesso as 'Ultimo Acesso'
	,prof.cod_prof as 'Codigo Professor',a.cod_al as 'Codigo Aluno',c1.desc_curso as 'Curso do Aluno'
	,d1.desc_dis as 'Disciplina do Aluno'	,t1.nome_turma as 'Nome da Turma do Aluno'
	,c2.desc_curso as 'Curso do Professor',d2.desc_dis as 'Disciplina do Professor'	,t2.nome_turma as 'Nome da Turma do Professor'";

  $strSQL .="FROM		pessoa as p
			left outer join aluno as a on (p.cod_pessoa = a.cod_pessoa)
			left outer join aluno_turma as at on (a.cod_al = at.cod_al)
			left outer join turma as t1 on  (at.cod_turma = t1.cod_turma) 
			left outer join disciplina as d1 on (t1.cod_dis = d1.cod_dis)
			left outer join curso as c1 on (c1.cod_curso = d1.cod_curso)
			left outer join professor as prof on (p.cod_pessoa = prof.cod_pessoa )
			left outer join professor_turma as pt on (prof.cod_prof = pt.cod_prof)
			left outer join turma as t2 on (pt.cod_turma = t2.cod_turma )
			left outer join disciplina as d2 on (t2.cod_dis = d2.cod_dis)
			left outer join curso as c2 on (c2.cod_curso = d2.cod_curso)
			WHERE";
  $order = " order by 	p.nome_pessoa";

  $strSQL = $strSQL.$filtro; //." limit 0,100";
  $strSQL = $strSQL.= " AND (a.cod_al IS NOT NULL OR c1.desc_curso IS NOT NULL OR d1.desc_dis IS NOT NULL OR c2.desc_curso IS NOT NULL OR d2.desc_dis IS NOT NULL)";


	return  mysql_query($strSQL);				
	
}


?>
