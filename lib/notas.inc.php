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

//include("/opt/lampp/htdocs/xampp/famurs/producao/CL/CLDb.inc.php");

class NotasInstancia {
  var $codInstanciaGlobal;
  //quantidade de notas
  var $notas;

  function NotasInstancia($codInstanciaGlobal) {
    $this->codInstanciaGlobal = $codInstanciaGlobal;
  }



  function comboEscolherNota() {
  
    $sql = "Select nroAvaliacoes from configuracaoregistropresenca where codInstanciaGlobal = ".$this->codInstanciaGlobal;
    $objx = new RDCLQuery($sql);
    return $objx;
/*
    $objx = new RDCLQuery($sql);
    $param["name"]=""; $param["id"]="";    
    $param["optionValue"]=""; $param["optionName"]="";
    return $objx->combo($param);
*/
   
     
  /*  if (mysql_affected_rows() > 0){

      $obj = mysql_fetch_object($rs);
      $result = "<select name='notas' id='notas'>";
      for($i=1;$i<=$obj->nroAvaliacoes;$i++){
        $result.="<option id='nota".$i."' senha='nota".$i."' value=\"".$i."\">Nota".$i."</option>";
      }
      $result.= "</select>";    
    }
    echo $result;*/
  }

  function exportarNotas($nota,$notasAlunos) {
    //note($notasAlunos);
    $afetado = '0';
    foreach($notasAlunos as $codAluno =>$valorNota) {
      $sql = "update notasinstancia set nota".$nota." = '".$valorNota."' 
      where codInstanciaGlobal = '".$this->codInstanciaGlobal."' and codAluno = '".$codAluno."';";
      
      $result = mysql_query($sql);
      if (mysql_affected_rows() == '1'){$afetado++; }
    }
    if ($afetado==0){
      $sql = "INSERT INTO notasinstancia ( codInstanciaGlobal,codAluno,nota".$nota.") VALUES "; 
      foreach($notasAlunos as $codAluno =>$valorNota) {
        $sql.=" ('".$this->codInstanciaGlobal."','".$codAluno."','".$valorNota."'),";
      }
      $sql = rtrim($sql,",");      
      $sql .= ";"; 
      mysql_query($sql);
    }
  echo "<font color=\"blue\">As notas foram inseridas com sucesso<br></font>";
  }

  function verificaNotaUtilizada($nota) {
  
    $sql = "Select count(*) as numNotas FROM notasinstancia ". 
           " Where codInstanciaGlobal=".quote_smart($this->codInstanciaGlobal).
           " AND nota".$nota."!=''";
    $result = mysql_query($sql);
    $linha = mysql_fetch_assoc($result);
    
    return $linha['numNotas'];
  }

}



/*testando a classe
=======

/*
 *
 *
 */
/*
//testando a classe

  mysql_connect('localhost', 'root', '');
  mysql_select_db('eavirtual');

//instancia o codInstanciaGlobal
  $instancia = new NotasInstancia("78");

//escreve na tela o combo com as notas de determinado codInstanciaGlobal
  $instancia->comboEscolherNota();

//grava as notas 
  $a=array("1665"=>"1","1666"=>"2","1667"=>"3","1668"=>"4","1669"=>"5");
  $instancia->exportarNotas('2',$a);
*/


?>
