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


include($caminhoBiblioteca."/pessoa.inc.php");
include($caminhoBiblioteca."/aluno.inc.php");
include($caminhoBiblioteca."/professor.inc.php");
include($caminhoBiblioteca."/apresentacao.inc.php");

//include($caminhoBiblioteca."/instanciaNivel.inc.php");
/*
 * Retorna o numero de aulas total de determinada turma
 */
function getconfiguracaoRegistroPresenca($codInstanciaGlobal) {
  $strSQL ="SELECT * FROM configuracaoregistropresenca WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
  //print_r ($strSQL);
  return mysql_query($strSQL);
}

function iniciaConfiguracaoRegistroPresenca($codInstanciaGlobal) {
  $strSQL ="insert ignore into configuracaoregistropresenca values 
            ('".quote_smart($codInstanciaGlobal)."','','75','1','0','0','0','0','','','','','','','','','','');  ";
//echo $strSQL;
  return mysql_query($strSQL);
}


/*
 * Retorna o numero de aulas ate o momento de determinado aluno
 */
function getNumeroAulasParcial($codInstanciaGlobal) {
 $strSQL ="SELECT COD_AL,count(*) as numPresencas from registropresenca ".
          "Where codInstanciaGlobal=".quote_smart($codInstanciaGlobal)."
          GROUP BY COD_AL ORDER BY COD_AL"; 
 //echo $strSQL;
 return mysql_query($strSQL);
}

/*
 * Retorna para o combo as datas de todas as aulas para serem editadas
 */
function listaDatasAulas($codInstanciaGlobal) {
 $strSQL ="SELECT distinct dataAula FROM `registropresenca` WHERE `codInstanciaGlobal` = 
 ".quote_smart($codInstanciaGlobal)." ORDER BY `dataAula` DESC";
 //print_r($strSQL); 
 return mysql_query($strSQL);
}

/*
 * Grava registros da tabela registropresenca
 */

function deletaRegistroPresenca($a,$codInstanciaGlobal,$data) {
 $strSQL ="delete FROM `registropresenca` WHERE dataAula = '".$data."' and `codInstanciaGlobal` =
'".quote_smart($codInstanciaGlobal)."'";
 if ($a !=''){
   $count = count($a);
   for($i=0 ;$i < $count-1;$i++){
     $strSQL .=" and `COD_AL` !='".$a[$i]."' ";
   }
   $strSQL .=" and `COD_AL` !='".$a[$i]."'; ";
 }
//echo $strSQL;
 return mysql_query($strSQL);
}

function gravaRegistroPresenca($a,$data,$codInstanciaGlobal) {
 $strSQL ="insert ignore into `registropresenca` values ";
 if ($a !=''){
   $count = count($a);
   for($i=0 ;$i < $count-1;$i++){
     $strSQL .=" ('".quote_smart($a[$i])."','".$data."','".quote_smart($codInstanciaGlobal)."'), ";
   }
   $strSQL .=" ('".quote_smart($a[$i])."','".$data."','".quote_smart($codInstanciaGlobal)."'); ";
 }
 //echo $strSQL; die;
 return mysql_query($strSQL);
}

function gravaConfiguracaoRegistroPresenca($codInstanciaGlobal,$numeroAulas,$percentPresencaMin,$arredBaixo,$nroAvaliacoes,$metodoAvaliacao,$metodoAvaliacaoFinal,$mostrarNotaFinal,$aInicial,$aFinal,$bInicial,$bFinal,$cInicial,$cFinal,$dInicial,$dFinal,$eInicial,$eFinal,$checkFaltas) {
$linhasAfetadas=0;
 $strSQL ="update `configuracaoregistropresenca` set 
           numeroAulas=".quote_smart($numeroAulas)."
          ,percentPresencaMin=".quote_smart($percentPresencaMin)."
          ,arredBaixo=".quote_smart($arredBaixo)."
          ,nroAvaliacoes=".quote_smart($nroAvaliacoes)."
          ,metodoAvaliacao=".quote_smart($metodoAvaliacao)."
          ,metodoAvaliacaofinal=".quote_smart($metodoAvaliacaoFinal)."
          ,mostrarNotaFinal=".quote_smart($mostrarNotaFinal)."

          ,aInicial=".quote_smart($aInicial)."
          ,aFinal=".quote_smart($aFinal)."
          ,bInicial=".quote_smart($bInicial)."
          ,bFinal=".quote_smart($bFinal)."
          ,cInicial=".quote_smart($cInicial)."
          ,cFinal=".quote_smart($cFinal)."
          ,dInicial=".quote_smart($dInicial)."
          ,dFinal=".quote_smart($dFinal)."
          ,eInicial=".quote_smart($eInicial)."
          ,eFinal=".quote_smart($eFinal)."
          ,checkFaltas=".quote_smart($checkFaltas)."

           where codInstanciaGlobal=".quote_smart($codInstanciaGlobal)."";
  //echo $strSQL;
  $result = mysql_query($strSQL);
  if (mysql_affected_rows() == '1'){$linhasAfetadas++; }
  
  if ($linhasAfetadas==0){ //SE AINDA TA VAZIO, DA INSERT AO INVES DE UPDATE
    $strSQL ="insert into `configuracaoregistropresenca` values(
           '".quote_smart($codInstanciaGlobal)."'
          ,'".quote_smart($numeroAulas)."'
          ,'".quote_smart($percentPresencaMin)."'
          ,'".quote_smart($arredBaixo)."'
          ,'".quote_smart($nroAvaliacoes)."'
          ,'".quote_smart($metodoAvaliacao)."'
          ,'".quote_smart($metodoAvaliacaoFinal)."'
          ,'".quote_smart($mostrarNotaFinal)."'

          ,'".quote_smart($aInicial)."'
          ,'".quote_smart($aFinal)."'
          ,'".quote_smart($bInicial)."'
          ,'".quote_smart($bFinal)."'
          ,'".quote_smart($cInicial)."'
          ,'".quote_smart($cFinal)."'
          ,'".quote_smart($dInicial)."'
          ,'".quote_smart($dFinal)."'
          ,'".quote_smart($eInicial)."'
          ,'".quote_smart($eFinal)."'
          ,'".quote_smart($checkFaltas)."')";

        //echo $strSQL;
        return mysql_query($strSQL);
  }


}

function listaAlunosPresentes($COD_AL,$dataAula,$codInstanciaGlobal) {
 $strSQL ="SELECT * FROM `registropresenca`WHERE COD_AL='".$COD_AL."' and `dataAula`='".$dataAula."' and `codInstanciaGlobal` = ".quote_smart($codInstanciaGlobal);

//echo $strSQL;
 return mysql_query($strSQL);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getNotas($codInstanciaGlobal) {
 $strSQL ="SELECT *
            FROM notasinstancia
            INNER JOIN aluno A ON ( notasinstancia.codAluno = A.COD_AL )
            INNER JOIN pessoa p ON ( p.COD_PESSOA = A.COD_PESSOA )
            WHERE codInstanciaGlobal=".$codInstanciaGlobal."
            order by p.NOME_PESSOA";
// echo $strSQL;
 return mysql_query($strSQL);
}


function getPeso($codInstanciaGlobal="",$nroAvaliacoes="") {
  $strSQL ="SELECT ";
  for($i=1;$i<=$nroAvaliacoes;$i++){
    $strSQL .="peso".$i.",";
  }
  $strSQL = rtrim($strSQL,","); 
  $strSQL .=" from pesoinstancia where codInstanciaGlobal = ".$codInstanciaGlobal;
//echo $strSQL;
  return mysql_query($strSQL);
}

function gravaPesoInstancia($codInstanciaGlobal,$nroAvaliacoes,$pesos) {
$linhasAfetadas = '0';
  $strSQL = "update pesoinstancia set ";
  for($i=1;$i<=$nroAvaliacoes;$i++){
    $pesos[$i] = strtr($pesos[$i],",",".");
    $strSQL.="peso".$i." = '".$pesos[$i]."',";
  }
  $strSQL = rtrim($strSQL,",");
  $strSQL.=" where codInstanciaGlobal = ".$codInstanciaGlobal." ;";
 // echo $strSQL;
  $result = mysql_query($strSQL);
  if (mysql_affected_rows() == '1'){$linhasAfetadas++; }
  
  if ($linhasAfetadas==0){//SE AINDA TA VAZIO, DA INSERT AO INVES DE UPDATE

    $strSQL="insert into pesoinstancia (codInstanciaGlobal,";
    for($i=1;$i<=$nroAvaliacoes;$i++){
      $strSQL .="peso".$i.",";
    }
    $strSQL = rtrim($strSQL,","); 
    $strSQL.=") values (".$codInstanciaGlobal.",";
    for($i=1;$i<=$nroAvaliacoes;$i++){
      $pesos[$i] = strtr($pesos[$i],",",".");
      $strSQL .=$pesos[$i].",";
    }
    $strSQL = rtrim($strSQL,","); 
    $strSQL.=");";
   // echo $strSQL;
    return mysql_query($strSQL);
  }
}


function gravaNotasinstancia($nroAlunos,$nroAvaliacoes,$codInstanciaGlobal,$alunosNotas,$notaFinal){
$linhasAfetadas = '0';
  //Varre os alunos
  foreach($alunosNotas as $codAluno =>$notas) {
    $strSQL = "update notasinstancia set ";
    //notas do aluno
    foreach($notas as $nota=>$valorNota) {
       $valorNota = strtr($valorNota,",",".");
       $strSQL.="nota".$nota." = '".$valorNota."',";
    }
    $strSQL = rtrim($strSQL,",");
    $strSQL.=" where codInstanciaGlobal = ".$codInstanciaGlobal." and codAluno = ".$codAluno.";";

    $result = mysql_query($strSQL);

    if (mysql_affected_rows() == '1'){$linhasAfetadas++; }
  } 

  if ($linhasAfetadas==0){//SE AINDA TA VAZIO, DA INSERT AO INVES DE UPDATE

      $strSQL = "INSERT IGNORE INTO notasinstancia ( codInstanciaGlobal,codAluno,";
      for($i=1;$i<=$nroAvaliacoes;$i++){
        $strSQL .=" nota".$i.",";
      }
      $strSQL = rtrim($strSQL,","); 
      $strSQL .=") VALUES "; //echo $strSQL;die;

      foreach($alunosNotas as $codAluno =>$notas) {
        $strSQL.=" ('".$codInstanciaGlobal."','".$codAluno."',";
        foreach($notas as $nota=>$valorNota) {
          $valorNota = strtr($valorNota,",",".");
          $strSQL .="'".$valorNota."',";
        }
        $strSQL = rtrim($strSQL,","); 
        $strSQL .="),";
      }
      $strSQL = rtrim($strSQL,",");      
      $strSQL .= ";"; 
  
 // echo$strSQL;
  mysql_query($strSQL);
  }
$i=1;
   foreach($alunosNotas as $codAluno =>$notas) {
    $notaFinal[$i] = strtr($notaFinal[$i],",",".");
    $sql ="UPDATE notasinstancia SET notaFinal = '".$notaFinal[$i]."' WHERE codInstanciaGlobal =".$codInstanciaGlobal." AND codAluno ='".$codAluno."';"; $i++;
//echo $sql."<Br>";
mysql_query($sql);
   }
  
  

}


/*
 * Funcao listaAlunosPresenca
 * @ int codInstanciaGlobal - relaciona com a instancia sistemica
 * @ int modo 0 - inserção de presenças em nova aula; 1 - edição
 */

function listaAlunosPresenca($codInstanciaGlobal="",$data="",$COD_PESSOA="") {
  $membrosAtivos=1;
  $nivelAtual = getNivelAtual();
  //por padrao, verifica se é comunidade e portanto somente mostra membros ativos no nivel
  //se for setado para nao mostrar, entao nao filtra 
  if ($membrosAtivos) { $membrosAtivos = $nivelAtual->nivelComunidade; }
  $pk = Aluno::getPKRelacionamento($nivelAtual);
  //Somente retorna se este nivel possuir relacionamento com alunos

  if (!empty($pk)) { 

    $strSQL = "SELECT distinct A.".Aluno::getPKRelacionamento($nivelAtual).",P.NOME_PESSOA,RP.COD_AL as presente,N.*";
    if ($modo) { $strSQL .= ",RP.dataAula";}
    $strSQL .=" FROM pessoa P INNER JOIN ".Aluno::getTabela()." A on (P.COD_PESSOA=A.COD_PESSOA)".
              " INNER JOIN ".Aluno::getTabelaRelacionamento($nivelAtual). " TAB ON (TAB.".$pk."=A.".$pk.
               " AND TAB.".$nivelAtual->getPK()."=".quote_smart(getCodInstanciaNivelAtual()).")";
              //" AND TAB.".InstanciaNivel::getPK($nivelAtual)."=".quote_smart(getCodInstanciaNivelAtual()).")";
    $strSQL .=" LEFT JOIN notasinstancia N ON ( A.COD_AL = N.codAluno AND N.codInstanciaGlobal =".quote_smart($codInstanciaGlobal)." ) ";
    $strSQL .=" LEFT JOIN registropresenca RP ON ( A.COD_AL = RP.COD_AL "; 
    $strSQL .=" AND RP.codInstanciaGlobal =".quote_smart($codInstanciaGlobal)." AND RP.dataAula='".$data."') ";
    $strSQL .=" order by P.NOME_PESSOA";
  }
  //echo $strSQL;
  return mysql_query($strSQL);
}

// altera data da aula caso o professor tenha se enganado 
function modificaDataAula($codInstanciaGlobal,$dataAnterior,$novaData) 
{
	
	echo $dataAnterior, '<br />'.$novaData.'<br />'; 
	$strSQL = "UPDATE registropresenca SET dataAula =".quote_smart($novaData);
  $strSQL.= "WHERE dataAula =".quote_smart($dataAnterior);
  $strSQL.= "AND codInstanciaGlobal =".quote_smart($codInstanciaGlobal);
	//echo $strSQL; die();
  return mysql_query($strSQL);


}


?>
