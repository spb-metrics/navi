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
ini_set('display_errors',1);
error_reporting(E_ALL);

include("../config.php");
include($caminhoBiblioteca."/pessoa.inc.php");  
include($caminhoBiblioteca."/pesquisaavaliacaoinstanciapeloaluno.inc.php");  
include($caminhoBiblioteca."/widgets.inc.php");  
session_name(SESSION_NAME); session_start(); security(); session_write_close();


?>
<html><head>
<script>
function verRespostas(control) {
 var allPageTags=document.getElementsByTagName("span"); 
 
 for (i=0; i<allPageTags.length; i++) { 
  
   if (allPageTags[i].className=='respostasIndividuais') { 
      el = allPageTags[i]; 
      if (el.style.display=='inline') {
        el.style.display='none';
        control.innerHTML = 'Clique para ver todas as questoes'
      }
      else  {
        el.style.display='inline';
        control.innerHTML = 'Clique para ver apenas respostas abertas'    
      }
    }
  }
}
</script>
<title>Avalia&ccedil;&atilde;o pelos alunos</title>
</head>
<body>

<?php
$totalRespostas = getTotalRespostas($_SESSION['codInstanciaGlobal']);

//Verifica se j� foram dadas respostas
if (!$totalRespostas) {
  echo "<center>Sem respostas.</center>";
  exit; die;
}

echo "<center><table width='100%'><tr><td width='65%'>";
//titulo
echo "<b><big>Avalia&ccedil;&atilde;o pelos Alunos"; 
echo "<br>Total de Respostas: ".$totalRespostas;
echo "</b></big></td><td>";
//legenda 
echo "<table bgcolor='#000000'  cellspacing='0'  cellpadding='0' ><tr><td>";
echo "<table cellspacing='1'>";
echo "<tr><td bgcolor='navy' width='10%'></td><td bgcolor='white'> >75% at&eacute; 100%</td></tr>";
echo "<tr><td bgcolor='darkgreen' width='10%'></td><td bgcolor='white'> >50% at&eacute; 75%</td></tr>";
echo "<tr><td bgcolor='yellow' width='10%'></td><td bgcolor='white'> >25% at&eacute; 50%</td></tr>";
echo "<tr><td bgcolor='darkred' width='10%'></td><td bgcolor='white'> >0%  at&eacute; 25%</td></tr>";
echo "<tr><td bgcolor='gray' width='10%'></td><td bgcolor='white' width='130'>Sem preenchimento</td></tr>";
echo "</table>";
echo "</td></tr></table>";
echo "</td></tr></table></center>";

$avaliacao= getPesquisaAvaliacaoAluno($_SESSION["codInstanciaGlobal"]);

foreach($avaliacao->records as $linha){
   $instrucao=$linha->instrucoes;
   //deixa o array 1-base e retira itens vazios do array
   if(!empty($linha->rotulos)){ $rotulos=string2Array1Based($linha->rotulos); }
   if(!empty($linha->opcoes)) { $opcoes = string2Array1Based($linha->opcoes); }
   $marcarTdCampos=$linha->marcarTdCampos; 
   $espacoAberto= $linha->espacoAberto;    
}

$i=0; //contador dos campos
$maximoOpcoes = count($opcoes);

while ( list(,$rot) = each($rotulos) ) {
  
  //busca os rotulos das questoes  
  echo "";
  if (!is_numeric(substr($rot,0,1)))  { 
    echo "<br><b><big><big>".$rot."</big></big></b>"; 
    continue;
  }
  //Titulo
  echo "<br><b><big>".$rot."</big></b><br>";

  $c=$campos[$i];  $i++;
  $respostas = getRespostas($_SESSION['codInstanciaGlobal'],$c); 
  
  //numero de respostas e a media desta questao 
  $numRespostas = $respostas['ocorrencias']; $media = $respostas['media']; 
  
  if ($numRespostas>0) {
    $percentualRespondido = $numRespostas/$totalRespostas*100;
    echo "M&eacute;dia (1-".$maximoOpcoes."): ".$media." | Numero de respostas:".$numRespostas."/".$totalRespostas." (".round($percentualRespondido,2)."%)"; 
    gauge($percentualRespondido);
    
    echo "<br>Ocorr�ncias de cada op��o:";
    foreach($opcoes as $valor=>$op) {
      $linha = getRespostas($_SESSION['codInstanciaGlobal'],$c,$valor);
      $respOpcao=0;
      $respOpcao = $linha['ocorrencias'];
      $percentualOpcao=$respOpcao/$numRespostas*100;
      echo "<br>".$op.": ".$respOpcao." (".round($percentualOpcao,2)."%)";    
      gauge($percentualOpcao);
    }
  }
  else {
    echo "Sem respostas.<br>";
  }

}


echo '<br><br><b><big>Respostas individuais</big></b> - ';
echo '<span onClick="verRespostas(this);" style="cursor:pointer;">Clique para ver todas as questoes</span>';
$respostas = getRespostasIndividuais($_SESSION['codInstanciaGlobal']);
echo '<table border="1" bordercolor="black" borderwidth="1" cellpading="3" cellspacing="0">';

//busca os rotulos das questoes  
echo '<tr>';
reset($rotulos);
while ( list(,$rot) = each($rotulos) ) {    
  if (!is_numeric(substr($rot,0,1)))  {     continue;  }
  echo '<th title="'.$rot.'"><span class="respostasIndividuais" style="display:none;">'.substr($rot,0,10).'...</span></th>';
}
if ($espacoAberto) {   echo '<th>Espa&ccedil;o Aberto para o Aluno</th>';}
echo '</tr>';

//Impressao dos dados

while(list(,$linha)=each($respostas)) {
  echo '<tr>';
  //lista todos os campos
  reset($rotulos);
  while ( list(,$rot) = each($rotulos) ) {    
    if (!is_numeric(substr($rot,0,1)))  {     continue;  }
    reset($campos);
    list(,$c) = each($campos);   
    echo '<td><span class="respostasIndividuais" style="display:none;">&nbsp;'.$opcoes[$linha->$c].'</span></td>';  
  }
  if ($espacoAberto) {  echo '<td>'.$linha->espacoAberto.'</td>';}
  echo '</tr>';
}
echo '</table>';
?>
</body>
</html>
