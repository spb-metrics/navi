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

include_once($caminhoBiblioteca."/verdadeiroFalso.inc.php"); 
include_once($caminhoBiblioteca."/multiplaEscolha.inc.php");
include_once($caminhoBiblioteca."/utils.inc.php");
class Questao {
 var $questao= array();
 var  $codQuestaoGlobal;
 
 function Questao($codQuestao) {
    $this->codQuestaoGlobal = $codQuestao;
    
    
 }

/**
*Forma o form para o enunciado da questão
*/
function escolheTipoQuestao(){
 //array que informa as possiveis questões   
    $questao[0][1]= "Verdadeiro/Falso";
    $questao[0][2]= "VerdadeiroFalso";
    $questao[1][1]= "Resposta &Uacute;nica";
    $questao[1][2]= "MultiplaEscolha";
       
    echo "<table width=\"80%\"><tr><td align=\"left\">Escolha o tipo da Questão:</td></tr></table>";
    echo "<table><tr><td></td></tr>";
    for($i=0;$i<2;$i++){
       echo "<tr><td class=\"tipos\"><input type=\"radio\" align=\"center\"  name=\"tipoQuestao\" value=\"".$questao[$i][2]."\" ";
       echo " onClick=\"if(this.checked){location.href='".$_SERVER["PHP_SELF"]."?acao=desenhaQuestao&classeQuestao=".$questao[$i][2]."';}\">";
       echo $questao[$i][1]."</td></tr>";
    }
   echo "</div></tr></table>";
}
/**
*mostra todos os tipo de questões que podem ser mostradas na plataforma
*/
function mostraTipoQuestao(){
 /* $sql = "SELECT *  FROM tipo_questoes ";
  $result = new RDCLQuery($sql);*/
}
/**
*mostra a pergunta e o tipo de questao da questao
*/
 function mostraquestao ($codPessoa, $codQuestao){
  $sql = "SELECT * FROM questao WHERE codPessoa=".$codPessoa." AND codQuestao=".$codQuestao."";
  $result = new RDCLQuery($sql);
  return $result;
 }
 /**
*mostras as alternativas da questao
*/
function mostraAlternativa($codQuestao){
 $sql = "SELECT * FROM alternativa WHERE codQuestao=".$codQuestao."";
 $result = new RDCLQuery($sql);
 return $result;
}
/**
* insere no BD a pergunta da questão
*/
function insereEnuciado($codPessoa,$descPergunta,$tipoQuestao){
 if(empty($resposta)) {$resposta="0";}
 $descricao = "descricao";
 $sql= "INSERT INTO questao (codPessoa,tipoQuestao,enunciado,descricao) VALUES ('".$codPessoa."','".$tipoQuestao."','".$descPergunta."','".$descricao."') ";
 mysql_query($sql);
 return mysql_insert_id(); 
}
/**
*listagem das questoes dependendo do filtro e da pessoa
*/
function listaQuestaoAdm($codInstanciaGlobal,$local,$codPessoa){
  $strSQL = "SELECT  *  ";

  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT codQuestao FROM exercicioquestao ET");
		
      $strSQL  .= " FROM questao Q WHERE Q.codQuestao NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon)){
      // print_r($linha);
	        $strSQL .= $linha["codQuestao"] . ",";
	    }
		
      $strSQL .= "0)";
    }

  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT EQ.codQuestao FROM exercicioquestao EQ");
		
      $strSQL .= " FROM questao Q WHERE Q.codQuestao IN (";
		
      while ($linha = mysql_fetch_array($rsCon)){
	     $strSQL .= $linha["codQuestao"] . ",";
		}
      $strSQL .= "0)";	
    }

  if ($local == "todas"){
	    $strSQL .= " FROM questao Q";
}
  //		$strSQL .= " FROM enquete E";

  if ($codPessoa != ""){
    $strSQL = "SELECT * FROM questao Q WHERE Q.codPessoa = '". $codPessoa ."'";
	}	
  $strSQL .= " ORDER BY Q.codQuestao";
 //print_r($strSQL);
  $result = new RDCLQuery($strSQL);
  return $result;
 }
/**
*função de layout para listar exercicios e questões
*/
function layoutLista($linha,$remover,$alterar){
        $html= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"85%\"  align=\"center\">";
			  $html.= "<tr><td width=\"80\" align=\"center\">";
        if(!empty($linha->records)){
         $html.= "<b> Excluir - Alterar </b></td>";
         $html.= "<td width=\"40\" align=\"right\">&nbsp</td>";   
         $html.= "<td><b>Descrição</b></td></tr>";
			   $html.= "<tr><td colspan=\"3\">&nbsp;</td></tr>";
			   foreach($linha->records as $linhaN){
				   $html.= "<tr>\n".
				  	       "<td align=\"center\">".
				  	 	     "<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?'))href='".$remover."&codQuestao=".$linhaN->codQuestao."&classeQuestao=".$linhaN->tipoQuestao."'\">".
				  	 	     "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
				  	 	     "</a>&nbsp;&nbsp;&nbsp;".
				  	 	     "<a href=\"".$alterar."&codQuestao=".$linhaN->codQuestao."&classeQuestao=".$linhaN->tipoQuestao."\">".
				  	 	     "<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
				  	 	     "</a>".
				  	 	     "</td>".
				  	 	     "<td align=\"right\">";
             $html.= $linhaN->codQuestao."&nbsp;&nbsp;</td>";
 				     $html.= "<td align=\"left\">".$linhaN->descricao."</td>";
			   }
			 }
       $html.= "</tr>";
			 if(empty($linha->records)){ $html.= "<tr><td> &nbsp;&nbsp;&nbsp; <b> Não há questões cadastradas. </b></td></tr>";}
       $html.= "</table></td></tr>";
       $html.= "</table>";
      return $html;
  }
/**
* seleciona os dados da questao 
*/
  function getQuestao ($codQuestao){
     $sql= "SELECT * FROM questao Q WHERE Q.codQuestao=".$codQuestao."";
     $result = new RDCLQuery($sql);
     return $result;
  }
/**
*seleciona todas as questoes usadas do exercicio
*/
function imprimiQuestoesUsadas($codExercicio){
    $sql="SELECT Q.descricao, Q.codQuestao FROM questao Q, exercicioquestao EQ WHERE EQ.codQuestao=Q.codQuestao AND EQ.codExercicio=".$codExercicio." ";
    $result= new RDCLQuery($sql);
    return $result;
}

/**
* imprimi as questões do exercicio
*/
function layoutDaResolucaoDoExercicio($dadosExercicio,$codExercicio,$acao,$arrayDeQuestoes="", $numeroTentativasAluno,$respostaPessoa=''){
 
  foreach($dadosExercicio->records as $dados){
     echo "<br><br><b><h7>".$dados->descricaoExercicio."</b></h7>";
     $numeroQuestoesTela=$dados->numeroQuestoesTela;
     $dataExpiracao=$dados->dataExpiracao;
     $numeroTentativas=$dados->numeroTentativas;
     $alunoPodeVerResultados=$dados->alunoPodeVerResultados;
     $imprimirAleatoriamente=$dados->imprimirQuestoesAleatoriamente;
     $imprimirAlternativaAleatoriamente=$dados->imprimirAlternativaAleatoriamente;
   }
   if($numeroTentativas>$numeroTentativasAluno-1){$gravar=1;} else {$gravar=0;}
//   print_r($numeroTentativasAluno);
//   print($gravar);
  
   if(empty($arrayDeQuestoes)){
     $sucesso=$this->imprimiQuestoesUsadas($codExercicio);
     foreach($sucesso->records as $ok){ $arrayDeQuestoes[].=$ok->codQuestao;}
   }

   if(!empty($_REQUEST["i"])) $inicio=$_REQUEST["i"]; else $inicio=0;
   
   if(!empty($imprimirAleatoriamente)){ shuffle($arrayDeQuestoes);}
   
   echo "<form name=\"resolveExercicio\" method=\"POST\" action=\"".$acao."\">";
   echo "<table align=\"center\" width=\"85%\">"; 
   
   if ($numeroQuestoesTela==4){
   $numeroQuestoesTela = count($arrayDeQuestoes);
   }
   
   for($i=$inicio;$i<$numeroQuestoesTela;$i++){
      if(!empty($arrayDeQuestoes["0"])){
        $questao=$this->getQuestao($arrayDeQuestoes["0"]);
      
      //Procura resposta do aluno para a questão 
       if(!empty($respostaPessoa)){        
         foreach($respostaPessoa->records as $indice=>$respostaPessoaQ){
          if($respostaPessoaQ->codQuestao==$questao->records[0]->codQuestao)
          {   
               $respostaPessoaQuestao=$respostaPessoaQ;
           }
         }
        }
         
         
       
        foreach($questao->records as $ques){
            $classe= new $ques->tipoQuestao();
            $classe->showQuestao($arrayDeQuestoes["0"],$questao,$imprimirAlternativaAleatoriamente,$respostaPessoaQuestao);
     
        }
        array_shift ($arrayDeQuestoes);
//        print_r($arrayDeQuestoes);
      }
  }
  
  echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Enviar Alternativas\"></td></tr>";
  echo "<input type=\"hidden\" name=\"codExercicio\" value=\"".$codExercicio."\">";
  echo "<input type=\"hidden\" name=\"numeroQuestoesTela\" value=\"".$numeroQuestoesTela."\">";
  echo "<input type=\"hidden\" name=\"gravar\" value=\"". $gravar."\">";
  for($i=0;$i<count($arrayDeQuestoes);$i++) echo "<input type=\"hidden\" name=\"arrayDeQuestoes[]\" value=\"".$arrayDeQuestoes[$i]."\">";
  echo "</table></form>";
 }

function excluirQuestao($codQuestao){
  $sql="DELETE FROM questao WHERE codQuestao=".$codQuestao."";
   mysql_query($sql);
   return(!mysql_errno());
}  
}?>
