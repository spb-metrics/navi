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
class VerdadeiroFalso extends Questao {
  var $tipoQuestao;
  function VerdadeiroFalso(){
     $this->tipoQuestao="Verdadeiro/Falso";
  }
  /**
  *Layout da quest�o Verdadeiro ou Falso
  */
  function mostraLayout($acao,$codQuestao=""){
    $editor=ativaDesativaEditorHtml();
    echo $editor;
    if(!empty($codQuestao)){
      $ok=parent::getQuestao($codQuestao);
      foreach($ok->records as $campo){
        $descricao=$campo->descricao;
        $enunciado=$campo->enunciado;
      }
      $ok=parent::mostraAlternativa($codQuestao);
      foreach($ok->records as $alternativas) $alternativa=$alternativas->texto;
       echo "<table  align=\"left\"><tr><td align=\"left\">" . "\n";
		   echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este arquivo ?'))href='exercicioLocal.php?opcao=removerGeral&codQuestao=".$codQuestao."'\">" . "\n";
		   echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
			 echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
			 echo "<td><font color='red'>";
			 echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este arquivo ?'))href='exercicioLocal.php?opcao=removerGeral&codQuestao=".$codQuestao."'\">" . "\n";
			 echo "Excluir Quest�o</a></font>" . "\n";
			 echo "</td></tr></table><br><br>";
    }
       
    echo "<table>";
    echo "<tr><td align=\"center\"></td></tr>";
    echo "</table>";
    echo "<form name=\"frmVerdadeiroFlaso\" method=\"post\" action=\"".$acao."\">";
    echo "<table>";
    echo "<tr><td colspan=\"2\">Descri��o da quest�o:</td></tr>";
    echo "<tr><td colspan=\"2\"><input type=\"text\" name=\"descricao\" value=\"".$descricao."\" size=\"80%\"></td></tr>";
    echo "<tr><td colspan=\"2\">Enunciado da quest�o :</td></tr>";
    echo "<tr><td colspan=\"2\"><textarea name=\"enunciado\" cols=\"80\" rows=\"4\">".$enunciado."</textarea></td></tr>";
   
    echo "<tr><td><input type=\"radio\" name=\"resposta\" value=\"verdadeiro\"";
    if($alternativa=="verdadeiro") echo "checked";
    echo ">Verdadeiro</td>";
    echo "<td><input type=\"radio\" name=\"resposta\" value=\"falso\"";
    if ($alternativa=="falso") echo "checked";
    echo ">Falso</td></tr>";
    echo "<tr><td align=\"center\"><input type=\"submit\"  name=\"Submit\" value=\"Enviar\"></td>";
    echo "<input type=\"hidden\" name=\"codQuestao\" value=\"".$codQuestao."\">";
    echo "<td align=\"center\"><input type=\"reset\" value=\"Cancelar\" onClick=\"window.location.href='".$_SERVER[PHP_SELF]."?acao=criar_questao'\"></td></tr>";
    echo "<input type=\"hidden\" name=\"tipoQuestao\" value=\"VerdadeiroFalso\">";
    echo "</form>";
   
  }
/**
*Fun��o que insere a quest�o e a resposta no banco de dados
*Insere enunciado, insere alternativa , update da questao com o codAlternatiavCorreta
*/
  function gravar($codPessoa,$request){
    if(empty($request["codQuestao"])){
       //insere o enunciado
       $sql= "INSERT INTO questao (codPessoa,tipoQuestao,enunciado,descricao) VALUES (".$codPessoa.",".quote_smart($request["tipoQuestao"]).",".quote_smart($request["enunciado"]).",".quote_smart($request["descricao"]).") ";
       mysql_query($sql);
      $codQuestao=mysql_insert_id(); 
      //inserir a alternativa
      $sql = "INSERT INTO alternativa (codQuestao, texto) VALUES(".$codQuestao.",'".$request["resposta"]."')";
      mysql_query($sql);
      $codAlternativaCorreta=mysql_insert_id(); 
      //faz um update na questao para colocar alternativa correta
      $sql= "UPDATE questao SET codAlternativaCorreta=". $codAlternativaCorreta." WHERE codQuestao=".$codQuestao."";
    }
    else{
        $sql="UPDATE questao SET enunciado =".quote_smart($request["enunciado"]).",  descricao=".quote_smart($request["descricao"])." WHERE codQuestao=".$request["codQuestao"]."";
       // print_r($sql); 
        mysql_query($sql);
        $sql="UPDATE alternativa SET texto=".quote_smart($request["resposta"])." WHERE codQuestao=".$request["codQuestao"]."";
      //  print_r($sql);
    }
    mysql_query($sql);// die();
    return;
  }
/**
* mostrar questoa para resolucao
*/
  function showQuestao($codQuestao,$questao,$teste="",$respostaPessoaQuestao=''){
   
    foreach($questao->records as $ques){
        echo "<tr><td align=\"center\">";
        echo "<table class=\"questao\"cellpadding=\"4\" cellspacing=\"2\">";
        echo "<tr><td align=\"center\" class=\"tituloquestao\" colspan=\"2\">".$ques->enunciado."</td></tr>";
    }
   
    $alternativa=$this->mostraAlternativa($codQuestao);
    
    foreach($alternativa->records as $alt){
      if($alt->texto=="verdadeiro"){$verdadeiro=$alt->codAlternativa; $falso=falso;} else{$falso=$alt->codAlternativa; $verdadeiro=verdadeiro;} 
    }
     echo "<tr><td align=\"left\" class=\"linha\"><input type=\"radio\" name=\"resposta[".$codQuestao."]\" value=\"".$verdadeiro."\"";
     if($respostaPessoaQuestao->codAlternativaEscolhida==$verdadeiro){ echo "checked"; }
     echo ">Verdadeiro</td>";
     echo "<td align=\"left\" class=\"linha\"><input type=\"radio\" name=\"resposta[".$codQuestao."]\" value=\"".$falso."\" ";
     if($respostaPessoaQuestao->codAlternativaEscolhida==$falso){ echo "checked"; }
     echo ">Falso</td></tr>";
     echo "</table></td></tr>";
}
    
  

}?>
