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

/**
 * short desc: Codigo global de uma instancia de nivel 
 *
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/F�bio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de Admnistra��o/UFRGS 2005
 */
class InstanciaGlobal extends RDCLRow{
  /*
   * Construtor
   */
  function InstanciaGlobal($codInstanciaGlobal="") {
    if (!empty($codInstanciaGlobal)) {
      $this->RDCLRow("instanciaglobal","codInstanciaGlobal",$codInstanciaGlobal);    
    }
  }
  /*
   * Retorna a instancia global a partir do nivel e instancia
   */
  function getByInstanciaNivel($codNivel,$codInstanciaNivel) {
   if (empty($codNivel) || empty($codInstanciaNivel)) { return ''; }
    $sql = "Select codInstanciaGlobal from instanciaglobal Where codNivel=".$codNivel." AND codInstanciaNivel=".$codInstanciaNivel;
    $obj = new RDCLQuery($sql);
    $obj->createSimpleObject($this);
  }

  function getUsoMathml(){
  	$sql="SELECT usaMathml FROM configuracaogeralinstancia WHERE codInstanciaGlobal=".$this->codInstanciaGlobal;
    
  	$result=mysql_query($sql);
    if(mysql_num_rows($result) >0){
  	  $usaMathml=mysql_fetch_array($result);
      return $usaMathml["usaMathml"]; 
  	}
    else {
  		return -1;
  	}
  }
function setUsoMathml(){
$usaMathml=$this->getUsoMathml();
	if($usaMathml>=0){
		$sql="UPDATE configuracaogeralinstancia SET usaMathml=1 WHERE codInstanciaGlobal=".$this->codInstanciaGlobal;
	}else{
		
		$sql="INSERT INTO configuracaogeralinstancia (codInstanciaGlobal , usaMathml) VALUES ('".$this->codInstanciaGlobal."',1)";
	}

mysql_query($sql);
return (! mysql_errno()); 
}

function unsetUsoMathml(){
	$sql="UPDATE configuracaogeralinstancia SET usaMathml=0 WHERE codInstanciaGlobal=".$this->codInstanciaGlobal;
	
  mysql_query($sql);
return (! mysql_errno()); 
}

  /*
   *  Aos poucos, todos os met�dos de parametriza��o devem vir para esta classe.  
   */  
  function getAlinhamentoConteudos() {
    $sql = "SELECT alinhamentoConteudos from configuracaogeralinstancia Where codInstanciaGlobal=".quote_smart($this->codInstanciaGlobal);
    $obj = new RDCLQuery($sql);
    
    if (!empty($obj->records)) { return $obj->records[0]->alinhamentoConteudos;}
    else { return 'center'; }              
  }    

  function setAlinhamentoConteudos($align) {
    if (empty($align)) { return ; }

    $sql = "update configuracaogeralinstancia set alinhamentoConteudos=".quote_smart($align);

    mysql_query($sql);
  }    
 
 
function gravaConfiguracaoInstancia($codInstanciaGlobal, $request){
 //print_r($request);
  if(Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])){
    if($request["codMenu"]){
      gravaMenuInstancia($codInstanciaGlobal,$request["codMenu"]);
    }
    else{
      //alinhamento de conteudos
      $this->setAlinhamentoConteudos($request['alinhar']);  
      //menu
      gravaMenuFixo($codInstanciaGlobal,$request);
      //conficuracao pessoal
      salvaConfiguracaoPessoa($_SESSION[COD_PESSOA],$request); 
      //mathml
      if($request['usaMathMl']) $this->setUsoMathml();
      else $this->unsetUsoMathml();
      //portfolio  
      if($request['permiteArquivoParticular']) permiteArquivoParticular($codInstanciaGlobal);
      if($request['permiteArquivoGeral'])  permiteArquivoGeral($codInstanciaGlobal) ;    
      alteraParticularGeralPortfolio($request['permiteArquivoGeral'],$request['permiteArquivoParticular'],$codInstanciaGlobal);


    }
 }
 
 if(empty($request["codMenu"])){ 
   salvaConfiguracaoPessoa($_SESSION[COD_PESSOA],$request);
  }
  return;
 } 


}
