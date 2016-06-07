<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

/* possiveis respostas*/
$opcoes[1]='0';
$opcoes[2] = '1';

$rotulo[1]='Toma iniciativa';
$rotulo[2] = 'Espera ser solicitado';
$rotulo[3] = 'Loquaz';
$rotulo[4] = 'Quieto';
$rotulo[5] = 'Comunica-se com rapidez';
$rotulo[6] = 'Comunica-se com pondera��o';
$rotulo[7]= 'Desafiador';
$rotulo[8] = 'Encorajador';
$rotulo[9] = 'Direto';
$rotulo[10] = 'Sutil';
$rotulo[11] = 'Faz afirma��es';
$rotulo[12] = 'Faz perguntas';
$rotulo[13] = 'Aparenta confian�a';
$rotulo[14] = 'Aparenta d�vida';
$rotulo[15] = 'Ativo';
$rotulo[16] = 'Reativo';
$rotulo[17] = 'Decis�es r�pidas';
$rotulo[18] = 'Decis�es analisadas';
$rotulo[19] = 'Sentido de urg�ncia';
$rotulo[20] = 'Sentido de paci�ncia';
$rotulo[21] = 'Expont�neo';
$rotulo[22] = 'Autocontrolado';
$rotulo[23] = 'Impulsivo';
$rotulo[24] = 'Autodisciplinado';
$rotulo[25] = 'Expressa sentimentos';
$rotulo[26] = 'Oculta sentimentos';
$rotulo[27] = 'Brincalh�o';
$rotulo[28] = 'Retra�do';
$rotulo[29] = 'Parece acess�vel';
$rotulo[30] = 'Parece inacess�vel';
$rotulo[31] = 'Orientado para relacionamento';
$rotulo[32] = 'Orientado para resultados';
$rotulo[33] = 'Caloroso';
$rotulo[34] = 'Impass�vel';
$rotulo[35] = 'Orientado para o Macro';
$rotulo[36] = 'Orientado para o Micro';
$rotulo[37] = 'Improvisador';
$rotulo[38] = 'Organizado';
$rotulo[39] = 'Aproxima-se';
$rotulo[40] = 'Mant�m dist�ncia';




/* Nomes fisicos dos campos */

$campos[] = 'opcao1';
$campos[] = 'opcao2';
$campos[] = 'opcao3';
$campos[] = 'opcao4';
$campos[] = 'opcao5';
$campos[] = 'opcao6';
$campos[] = 'opcao7';
$campos[] = 'opcao8';
$campos[] = 'opcao9';
$campos[] = 'opcao10';
$campos[] = 'opcao11';
$campos[] = 'opcao12';
$campos[] = 'opcao13';
$campos[] = 'opcao14';
$campos[] = 'opcao15';
$campos[] = 'opcao16';
$campos[] = 'opcao17';
$campos[] = 'opcao18';
$campos[] = 'opcao19';
$campos[] = 'opcao20';



//insere os dados a partir de um POST de formulario
function registraAvaliacaoInstanciaAluno($codInstanciaGlobal,$codAluno) {
  global $campos; 
  if(!empty($codAluno)){
      $sql = "INSERT INTO pesquisaavaliacao2 (codInstanciaGlobal";
      foreach($campos as $c) { $sql.=','.$c; }
      $sql.=',agenciaUnidade,codAluno) ';
      $sql.=" VALUES (".quote_smart((int)$codInstanciaGlobal);

      foreach($campos as $c) { $sql.=','.quote_smart((int) $_POST[$c]); }

      $sql.=",".quote_smart( addSlashes($_POST['agenciaUnidade']) ). " ,".$codAluno.")";
      mysql_query($sql);
      return;
  }
  else{
      return -1;
  }
  
 // $sql = "INSERT INTO pesquisaavaliacaopreenchida (codInstanciaGlobal,codAluno) ".
 //        " VALUES (".quote_smart($codInstanciaGlobal).",".quote_smart($codAluno).")";
//  mysql_query($sql);
}

//insere os dados a partir de um POST de formulario
function alunoJaRespondeu($codInstanciaGlobal,$codAluno) {
  $sql = "SELECT codAluno FROM pesquisaavaliacao2  ".
         " WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal)." AND ".
         " codAluno=".quote_smart($codAluno);
  $result = mysql_query($sql);
  return mysql_num_rows($result);
}



function validaCamposPreenchidos() {
  global $campos;
  echo " function validaCamposPreenchidos() {";
  foreach($campos as $c) {
    echo "el = document.getElementById('".$c."'); ";
    echo "if (el.options[el.options.selectedIndex].value==-1) { alert('Responda a todas as perguntas!');  return false; }";
  }
  echo " return true; } ";
}

/* Total de respostas da instancia */
function getTotalRespostas($codInstanciaGlobal) {
 $sql = "Select count(*) FROM pesquisaavaliacao2 WHERE 
          codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
 
  $result = mysql_query($sql);
 
  $linha = mysql_fetch_array($result);
  
  return $linha[0];
}

/* Total de respostas da instancia 
 */
function getRespostas($codInstanciaGlobal,$campo,$valor=0) {
  //  $sql = "SELECT $campo as ocorrencias "; 
  $sql = "SELECT count(".$campo.") as ocorrencias ";
 // if (!$valor) {    //se o valor nao � informado, calculamos a media das respostas validas
 //   $sql.=",avg(".$campo.") as media"; $where=$campo."!=-1"; 
//  }
//  else {    //se o valor � informado, calculamos as ocorrencias deste valor 
    $where=$campo."=".$valor;   
 // }  
  
  $sql.=" FROM pesquisaavaliacao2 Where codInstanciaGlobal=".
          quote_smart($codInstanciaGlobal);  
  $sql.=" AND ".$where;
  //print_r($sql);
  $result = mysql_query($sql);
  //echo $sql;
  return mysql_fetch_assoc($result);
}

function getRespostasTotalOpcao($codInstanciaGlobal,$campo) {
  //  $sql = "SELECT $campo as ocorrencias "; 
  $sql = "SELECT count(".$campo.") as ocorrencias ";
  $where=$campo."=".$valor;   
  $sql.=" FROM pesquisaavaliacao2 Where codInstanciaGlobal=".
          quote_smart($codInstanciaGlobal);  
 $result = mysql_query($sql);
  //echo $sql;
  return mysql_fetch_assoc($result);
}
function getNomeAluno($codPessoa){
  $sql="SELECT NOME_PESSOA FROM pessoa WHERE COD_PESSOA=".$codPessoa."";
  $result = mysql_query($sql);
  $linha = mysql_fetch_array($result);
  return $linha["NOME_PESSOA"];
}
?>
