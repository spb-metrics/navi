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

/* possiveis respostas*/
if(empty($opcoes)){
$opcoes[1]='Discordo plenamente';
$opcoes[2]='Discordo';
$opcoes[3]='N&atilde;o discordo e nem concordo';
$opcoes[4]='Concordo';
$opcoes[5]='Concordo Plenamente';
$opcoes[6]='N&atilde;o se Aplica';
}
/* DescriþÊo (label) dos campos, utilizar melhor no futuro*/ 
if(empty($rotulos)){
$rotulos[] = "Avalia&ccedil;&atilde;o do Professor";
$rotulos[] = '1 - O professor trabalhou os conte&uacute;dos da disciplina com clareza, destacando aspectos importantes da mat&eacute;ria.';
$rotulos[] = '2 - Os v&iacute;deos utilizados foram objetivos, apropriados e claros.';
$rotulos[] = '3 - O professor utilizou instrumentos (provas, trabalhos, etc) de avaliaþÒo compat&iacute;veis com os conhecimentos, habilidades e atitudes desenvolvidas na disciplina.';
$rotulos[] = '4 - O professor possibilitou din&acirc;micas que favorecem rela&ccedil;&otilde;es entre o conte&uacute;do da disciplina com os demais conte&uacute;dos do curso.';
$rotulos[] = 'AvaliaþÒo do Tutor';
$rotulos[] = '1 - O tutor incentivou a participa&ccedil;&atilde;o dos alunos, considerando o seu questionamento cr&iacute;tico e suas contribui&ccedil;&otilde;es.';
$rotulos[] = '2 - O tutor mostrou-se dispon&iacute;vel para atender aos alunos sempre que poss&iacute;vel.';
$rotulos[] = '3 - O tutor analisou com os alunos os resultados das avalia&ccedil;&otilde;es e esclareceu d&iacute;vidas.';
$rotulos[] = '4 - O tutor participou ativamente dos chats de forma qualificada e ativa, promovendo o debate.';
$rotulos[] = '5 - O tutor respondeu às quest&otilde;es levantadas nos F&oacute;runs, dentro do tempo previsto.';
$rotulos[] = 'Avalia&ccedil;&atilde;o do Coordenador';
$rotulos[] = '1 - O coordenador cumpriu sua carga horßria na disciplina.';
$rotulos[] = '2 - O coordenador incentivou a participa&ccedil;&atilde;o dos alunos, considerando o seu questionamento cr&iacute;tico e sugest&otilde;es.';
$rotulos[] = '3 - O coordenador mostrou-se dispon&iacute;vel para atender aos alunos sempre que poss&iacute;vel.';
$rotulos[] = '4 - O coordenador apresentou e deixou claro os procedimentos adotados em cada encontro.';
$rotulos[] = 'Avalia&ccedil;&atilde;o da Disciplina';
$rotulos[] = '1 - O plano de ensino da disciplina apresenta com clareza: objetivos, conte&uacute;dos, bibliografia, sistema de avalia&ccedil;&atilde;o e atividades a serem realizadas.';
$rotulos[] = '2 - Os objetivos de aprendizagem da disciplina foram alcanþados.';
$rotulos[] = '3 - A disciplina contribuiu para o desenvolvimento da capacidade intelectual do aluno, nÒo se restringindo à memoriza&ccedil;&atilde;o.';
$rotulos[] = '4 - A distribui&ccedil;&atilde;o dos conte&uacute;dos da disciplina foi adequada.';
$rotulos[] = '5 - A disciplina utilizou exerc&iacute;cios, trabalhos prßticos, visitas T&eacute;cnicas ou outros, quando adequados.';
$rotulos[] = '6 - Sempre que poss&iacute;vel foram estabelecidas rela&ccedil;&otilde;es entre conte&uacute;dos das disciplinas e os campos de trabalho da profissÒo.';
$rotulos[] = '7 - Sempre que poss&iacute;vel os conhecimentos desenvolvidos na disciplina foram contextualizados na realidade social, econ¶mica, pol&iacute;tica e/ou ambiental brasileira.';
$rotulos[] = 'Avalia&ccedil;&atilde;o Estrutural';
$rotulos[] = '1 -As condi&ccedil;&otilde;es f&iacute;sicas de cada p&oacute;lo (sala de aula, acessibilidade, limpeza, etc) colaboram para o desenvolvimento da disciplina.';
$rotulos[] = '2 - As condi&ccedil;&otilde;es da plataforma virtual colaboram para o desenvolvimento da disciplina.';
$rotulos[] = '3 - A plataforma virtual disponibiliza um ambiente adequado para a realiza&ccedil;&atilde;o dos chats.';
$rotulos[] = '4 - A plataforma virtual disponibiliza um ambiente adequado para a realiza&ccedil;&atilde;o dos f&oacute;runs.';
$rotulos[] = '5 - A plataforma virtual possui as ferramentas necessßrias para o pleno desenvolvimento do ensino à distÈncia.';
$rotulos[] = '6 - O material disponibilizado &eacute; adequado/suficiente para o desenvolvimento da disciplina.';
$rotulos[] = 'Auto-avalia&ccedil;&atilde;o';
$rotulos[] = '1 - Eu possu&iacute;a os pr&eacute;-requisitos necess&aacute;rios para o bom acompanhamento da disciplina.';
$rotulos[] = '2 - Estou satisfeito com o que aprendi na disciplina.';
$rotulos[] = '3 - Dediquei o esfor&ccedil;o necess&aacute;rio à disciplina.';
$rotulos[] = '4 - Participei ativamente dos chats, debatendo os temas da disciplina com colegas e tutor.';
$rotulos[] = '5 - Participei dos f&oacute;runs, esclarecendo minhas d&uacute;vidas.';
$rotulos[] = '6 - Participei dos f&oacute;runs, contribuindo com o grupo atrav&eacute;s de reflex&otilde;es.';
}

/* Nomes fisicos dos campos. A ordem é relevante. */
if(empty($campos)){
$campos[] = 'avaliacaoProfessor1';
$campos[] = 'avaliacaoProfessor2';
$campos[] = 'avaliacaoProfessor3';
$campos[] = 'avaliacaoProfessor4';
$campos[] = 'avaliacaoTutor1';
$campos[] = 'avaliacaoTutor2';
$campos[] = 'avaliacaoTutor3';
$campos[] = 'avaliacaoTutor4';
$campos[] = 'avaliacaoTutor5';
$campos[] = 'avaliacaoCoordenador1';
$campos[] = 'avaliacaoCoordenador2';
$campos[] = 'avaliacaoCoordenador3';
$campos[] = 'avaliacaoCoordenador4';
$campos[] = 'avaliacaoDisciplina1';
$campos[] = 'avaliacaoDisciplina2';
$campos[] = 'avaliacaoDisciplina3';
$campos[] = 'avaliacaoDisciplina4';
$campos[] = 'avaliacaoDisciplina5';
$campos[] = 'avaliacaoDisciplina6';
$campos[] = 'avaliacaoDisciplina7';
$campos[] = 'avaliacaoEstrutural1';
$campos[] = 'avaliacaoEstrutural2';
$campos[] = 'avaliacaoEstrutural3';
$campos[] = 'avaliacaoEstrutural4';
$campos[] = 'avaliacaoEstrutural5';
$campos[] = 'avaliacaoEstrutural6';
$campos[] = 'autoAvaliacao1';
$campos[] = 'autoAvaliacao2';
$campos[] = 'autoAvaliacao3';
$campos[] = 'autoAvaliacao4';
$campos[] = 'autoAvaliacao5';
$campos[] = 'autoAvaliacao6';
}
//insere os dados a partir de um POST de formulario
function registraAvaliacaoInstanciaAluno($codInstanciaGlobal,$codAluno) {
  global $campos;

  $avaliacao=getPesquisaAvaliacaoAluno($codInstanciaGlobal); 
  $espacoAberto=$avaliacao->records[0]->espacoAberto;
   
  
  $sql = "INSERT INTO pesquisaavaliacaoinstanciapeloaluno (codInstanciaGlobal";
  foreach($campos as $c) { $sql.=','.$c; }
  if($espacoAberto)  {
    $sql.=',espacoAberto';
  }
  $sql.=" ) VALUES (".quote_smart((int)$codInstanciaGlobal);
  
  foreach($campos as $c) { $sql.=','.quote_smart((int) $_POST[$c]); }
  if($espacoAberto) {
    $sql.=",".quote_smart( addSlashes($_POST['espacoAberto']) )."";
  }
  $sql.= ")";
 
  mysql_query($sql);
  
  $sql = "INSERT INTO pesquisaavaliacaopreenchida (codInstanciaGlobal,codAluno) ".
         " VALUES (".quote_smart($codInstanciaGlobal).",".quote_smart($codAluno).")";

  mysql_query($sql);
}

//insere os dados a partir de um POST de formulario
function alunoJaRespondeu($codInstanciaGlobal,$codAluno) {
  $sql = "SELECT 1 FROM pesquisaavaliacaopreenchida  ".
         " WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal)." AND ".
         " codAluno=".quote_smart($codAluno);
  $result = mysql_query($sql);
  return mysql_num_rows($result);
}

function opcoesAvaliacao($nome,$opcoes) {
  //global $opcoes;
  echo "</td><td valign='middle'>";
  
  echo "<SELECT name ='".$nome."' id ='".$nome."' >";
  echo "<OPTION value='-1'>Selecione uma op&ccedil;&atilde;o</OPTION>";
  foreach($opcoes as $valor=>$descricao) {
    echo "<OPTION value='".$valor."'>".$descricao."</OPTION>";
  }
  echo "</SELECT></td>";
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
  $sql = "Select count(*) FROM pesquisaavaliacaoinstanciapeloaluno Where 
          codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
  $result = mysql_query($sql);
  $linha = mysql_fetch_array($result);
  
  return $linha[0];
}

/* 
 * Total de respostas da instancia
 * usada tanto para o total de respostas da questao como 
 * para o subtotal de cada op&ccedil;&atilde;o  
 */
function getRespostas($codInstanciaGlobal,$campo,$valor='') {
    
  $sql = "SELECT count(".$campo.") as ocorrencias ";
  if ($valor=='') {    //se o valor nao for informado, calculamos a media das respostas validas
    $sql.=",avg(".$campo.") as media"; $where=$campo."!=-1"; 
  }
  else {    //se o valor for informado, calculamos as ocorrencias deste valor 
    $where=$campo."=".$valor;   
  }  
  
  $sql.=" FROM pesquisaavaliacaoinstanciapeloaluno Where codInstanciaGlobal=".
          quote_smart($codInstanciaGlobal);  
  $sql.=" AND ".$where;
  
  $result = mysql_query($sql);
  //echo $sql;
  return mysql_fetch_assoc($result);
}

function inserePesquisaavaliacaopeloaluno($codInstanciaGlobal,$request){
//note($request);
 $novo=getPesquisaAvaliacaoAluno($codInstanciaGlobal); 
 
 if(empty($novo->records)){
  $sql="INSERT INTO configuracaogeralinstancia (codInstanciaGlobal,instrucoes,rotulos,opcoes,marcarTdCampos,dataFim,dataInicio,espacoAberto) VALUES(".$codInstanciaGlobal.",".quote_smart($request["instrucoes"]).",".quote_smart($request["rotulos"]).",".quote_smart($request["opcoes"]).",'".$request["marcarTdCampos"]."','".$request["dataFim"]."','".$request["dataInicio"]."','".$request["espacoAberto"]."')";
 //print_r($sql);
}
 else{
 $sql="UPDATE configuracaogeralinstancia SET instrucoes=".quote_smart($request["instrucoes"]).", rotulos=".quote_smart($request["rotulos"]).", opcoes=".quote_smart($request["opcoes"]).", marcarTdCampos='".$request["marcarTdCampos"]."',dataInicio='".$request["dataInicio"]."',dataFim='".$request["dataFim"]."', espacoAberto='".$request["espacoAberto"]."'  WHERE codInstanciaGlobal='".$codInstanciaGlobal."'";

 }

 $result = mysql_query($sql);
 //$noticia = NoticiaInsere("Avalia&ccedil;&atilde;o", "<a href=\"../pesquisaavaliacaoinstanciapeloaluno/index.php\">Clique aqui para realizar a avalia&ccedil;&atilde;o</a>", "","","");
 
 //NoticiaLocalInsere($noticia, $codInstanciaGlobal, "1", "1","2");
  return mysql_error();
}

/*
 *
 */ 
function getPesquisaAvaliacaoAluno($codInstanciaGlobal,$hoje=""){
  $sql="SELECT instrucoes,rotulos,opcoes,marcarTdCampos, DATE_FORMAT(dataInicio, '%d/%m/%Y')as dataI,DATE_FORMAT(dataFim,'%d/%m/%Y')as dataF,espacoAberto FROM configuracaogeralinstancia WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal); 
  if(!empty($hoje)) $sql.=" AND dataInicio<=".quote_smart($hoje)." AND dataFim>=".quote_smart($hoje)."";
  // print_r($sql); die();
  $result = new RDCLQuery($sql);
  return $result;
}

function dateDiff($date1, $date2) {
    $inicio=explode("-",$date1);
    $fim=explode("-",$date2);
   // print_r($fim);
   // print_r($inicio);
    if($fim[2]==0 or $fim[1]==0 or $fim[0]==0){  return 1;}
    if($inicio[2]>=$fim[2]){
      if ($inicio[2]==$fim[2]){
          if($inicio[1]>=$fim[1]){
            if($inicio[1]==$fim[1]){
                if($inicio[0]>=$fim[0]){
                    if($inicio[0]==$fim[0]) {  return 1;}
                    else $return -1;
                }
                else{  return 1;}
            }
            else return -1;
          }
          else { return 1;}  
      }
      else return -1;    
    }
    else{
       return 1;
    }
}
/*
 * Transforma uma string separada por \n em um array 1-based (indice inicia em 1)
 */ 
function string2Array1Based($string) {
  $array1Based=array();
  $contador=1;
  $aux=explode("\n",$string);      
  foreach($aux as $item) { 
    $item=rtrim($item); 
    if (!empty($item)) { 
      $array1Based[$contador]=$item; $contador++; 
    } 
  }
  
  return $array1Based;
}


function getRespostasIndividuais($codInstanciaGlobal) {
  $sql = 'select * from pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal='.quote_smart($codInstanciaGlobal);
  $obj = new RDCLQuery($sql);
  return $obj->records;
}

?>
