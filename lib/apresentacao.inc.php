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
 * Arquivo que contém funções de apresentacao
 *   
 */



/* 
 * 
 * Busca quantos alunos há neste nível
 *   
 */
function numeroAlunosTurma($membrosAtivos=1,$contaInvisiveis=0) {

  $nivelAtual = getNivelAtual();
  $pk = Aluno::getPKRelacionamento($nivelAtual);
  
  //por padrao, verifica se é comunidade e portanto somente mostra membros ativos no nivel
  //se for setado para nao mostrar, entao nao filtra 
  if ($membrosAtivos) { $membrosAtivos = $nivelAtual->nivelComunidade; }

  //Somente retorna se este nivel possuir relacionamento com alunos
  if (!empty($pk)) {
    $strSQL = 'SELECT DISTINCT A.'.$pk.', count(*) as totalAlunos'.
      " FROM ".Aluno::getTabela()." A, ".Aluno::getTabelaRelacionamento($nivelAtual)." AT ";
      
    if (!$contaInvisiveis) {      $strSQL.=", tipo_aluno TA";    }  //acrescenta tabela de papeis na juncao
    
    $strSQL.=  " WHERE A.".$pk. "= AT.".$pk." AND ".
      "		 AT.".$nivelAtual->nomeFisicoPK ." = ". getCodInstanciaNivelAtual() ;
    
    if ($membrosAtivos) {      $strSQL .= " AND ativo=1 ";     }
    if (!$contaInvisiveis) {       $strSQL .= " AND TA.codTipoAluno=AT.codTipoAluno AND TA.visivel=1 ";     }
    $strSQL.=" GROUP BY AT.".$nivelAtual->getPK();

    $result = mysql_query($strSQL);
    //ECHO $strSQL;
    $num = mysql_fetch_assoc($result);

    return $num["totalAlunos"];
  }
  else {
    return 0;
  }
}
?>