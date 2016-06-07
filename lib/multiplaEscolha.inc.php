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
class MultiplaEscolha extends Questao{
  var $tipoQuestao;
function MultiplaEscolha(){
    $this->tipoQuestao="Multipla Escolha";
  }
  /**
  *Layout da questão Verdadeiro ou Falso
  */
  function mostraLayout($acao,$codQuestao=""){
    $editor=ativaDesativaEditorHtml();
    echo $editor;
    if(!empty($codQuestao)){
      $ok=parent::getQuestao($codQuestao);
      foreach($ok->records as $campo){
        $descricao=$campo->descricao;
        $enunciado=$campo->enunciado;
        $codAlternativaCorreta=$campo->codAlternativaCorreta;
      } 
      $linha=parent::mostraAlternativa($codQuestao);
      $i=0;
      foreach($linha->records as $linhaA){
        $alternativa[$i]= $linhaA->texto;
        $codAlternativa[$i]=$linhaA->codAlternativa;  
        $i=$i+1;
      }
       echo "<table  align=\"left\"><tr><td align=\"left\">" . "\n";
		   echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este arquivo ?'))href='exercicioLocal.php?opcao=removerGeral&codQuestao=".$codQuestao."'\">" . "\n";
		   echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
			 echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
			 echo "<td><font color='red'>";
			 echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este arquivo ?'))href='exercicioLocal.php?opcao=removerGeral&codQuestao=".$codQuestao."'\">" . "\n";
			 echo "Excluir Questao</a></font>" . "\n";
			 echo "</td></tr></table><br><br>";
    }   
    
    echo "<form  name=\"frmMultiplaEscolha\"  method=\"post\"  action=\"".$acao."\">";
    echo "<table>";
    echo "<tr><td colspan=\"2\">Descrição da questão:</td></tr>";
    echo "<tr><td colspan=\"2\"><input type=\"text\" name=\"descricao\" value=\"".$descricao."\" size=\"80%\"></td></tr>";
    echo "<tr><td colspan=\"2\">Enunciado:</td></tr>";
    echo "<tr><td colspan=\"2\">".
         "<textarea id=\"Enunciado\" name=\"enunciado\" cols=\"80\" rows=\"4\">".$enunciado."</textarea>";
   
    echo "</td></tr>";
    echo "<tr><td>Alterntivas:</td></tr>";
    for($i=0;$i<5;$i++){
      echo "<tr><td align=\"right\"><input type=\"radio\" name=\"alternativaCorreta\" value=\"".$i."\"";
      if($codAlternativa[$i]==$codAlternativaCorreta){echo "checked";}
      echo "></td>";
      echo "<td align=\"left\"><textarea name=\"alternativa[]\" cols=\"83\" rows=\"3\">".$alternativa[$i]."</textarea></td></tr>";
          
    }
    echo "<tr><td  align=\"right\"><input type=\"submit\" name=\"Submit\" value=\"Enviar\"></td>"; 
    echo "<td align=\"left\"><input type=\"reset\" value=\"Cancelar\" onClick=\"window.location.href='".$_SERVER[PHP_SELF]."?acao=criar_questao'\"></td></tr>";
    echo "<input type=\"hidden\" name=\"codQuestao\" value=\"".$codQuestao."\">";
    echo "<input type=\"hidden\" name=\"tipoQuestao\" value=\"MultiplaEscolha\">";
    echo "</table></form>";
   
 
    
  }
/**
*Função que insere a questão e a resposta no banco de dados
*Insere enunciado, insere alternativa , update da questao com o codAlternatiavCorreta
*/
  function gravar($codPessoa,$request){
    if(empty($request["codQuestao"])){
      $sql= "INSERT INTO questao (codPessoa,tipoQuestao,enunciado,descricao) VALUES (".$codPessoa.",".quote_smart($request["tipoQuestao"]).",".quote_smart($request["enunciado"]).",".quote_smart($request["descricao"]).") ";
      mysql_query($sql);
      $codQuestao=mysql_insert_id(); 
      //inserir as alternativas
      for($i=0;$i<5;$i++){
        if(!empty($request["alternativa"][$i])){
            $sql = "INSERT INTO alternativa (codQuestao, texto) VALUES(".$codQuestao.",".quote_smart($request["alternativa"][$i]).")";
            mysql_query($sql); 
        }
        if($i==$request["alternativaCorreta"]){
          $codAlternativaCorreta=mysql_insert_id(); 
          //faz um update na questao para colocar alternativa correta
          $sql= "UPDATE questao SET codAlternativaCorreta=". $codAlternativaCorreta." WHERE codQuestao=".$codQuestao."";
        mysql_query($sql);
        }
     }
   }
   else{
   
      $sql="UPDATE questao SET enunciado=".quote_smart($request["enunciado"]).",descricao=".quote_smart($request["descricao"])." WHERE codQuestao=".$request["codQuestao"]."";
      mysql_query($sql);
      $i=0;
      $rsCon = mysql_query("SELECT codAlternativa FROM alternativa WHERE codQuestao=".$request["codQuestao"]."");
      while($linha = mysql_fetch_array($rsCon)) {
         $sql="UPDATE alternativa SET texto=".quote_smart($request["alternativa"][$i])." WHERE codAlternativa=".$linha["codAlternativa"]."";  
         mysql_query($sql);
         if($i==$request["alternativaCorreta"]){
          $sql="UPDATE questao SET codAlternativaCorreta=".$linha["codAlternativa"]." WHERE codQuestao=".$request["codQuestao"]."";
          mysql_query($sql);
         }
         $i=$i+1;
     }
   }
  return;
  }
  /**
  * selecionar todas as alternativas relacionadas aos alunos
  */
  function getAlternativa($codQuestao,$imprimirAlternativaAleatoriamente){
    $sql= "SELECT * FROM alternativa WHERE codQuestao=".$codQuestao." ";
    if($imprimirAlternativaAleatoriamente) $sql.="ORDER BY RAND()";
    else $sql.= "ORDER BY codAlternativa";
    $result= new RDCLQuery($sql);
    return $result;
  }
 /**
 *mostrar questoa para resolucao
 */
  function showQuestao($codQuestao,$questao,$imprimirAlternativaAleatoriamente,$respostaPessoaQuestao=''){
 
    foreach($questao->records as $ques){
        echo "<tr><td align=\"center\">";
        echo "<table class=\"questao\"cellpadding=\"4\" cellspacing=\"2\">";
        echo "<tr><td align=\"center\" class=\"tituloquestao\">".$ques->enunciado."</td></tr>";
    }
    $alternativa=$this->getAlternativa($codQuestao,$imprimirAlternativaAleatoriamente);
    foreach($alternativa->records as $alt){
         echo"<tr><td align=\"left\" class=\"linha\"><input type=\"radio\" name=\"resposta[".$codQuestao."]\" value=\"".$alt->codAlternativa."\"";
         if($respostaPessoaQuestao->codAlternativaEscolhida==$alt->codAlternativa){
         echo "checked";
         }
         echo ">".$alt->texto."</td></tr>";
    }
    echo "</table></td></tr>";
  }
}?>
