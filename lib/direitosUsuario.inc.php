<?php 
class DireitosUsuario{
  var $codPessoa;           //codigo da pessoa
  var $autoridade;          //UserRole (qual autoridade está exercendo neste momento )  
  var $nivel;               //$nivel
  var $papel;               //papel exercido ex:moderador
  var $visivel;             // se a pessoa deve aparecer na listagem
  var $visualiza;           // se a pessoa apenas visualiza o material      
  var $interage;             //se a pessoa pode visualizar e interagir ex: postar mensagens no forum
  var $codInstanciaNivel;
    //construtor                       
  function DireitosUsuario($codPessoa,$autoridade,$codInstanciaNivel,$nivel){

    $this->codPessoa=$codPessoa;
    $this->autoridade=$autoridade;
    $this->nivel=$nivel;
    $this->codInstanciaNivel=$codInstanciaNivel;
    $this->getDireitosUsuario();
  }
 
  /**
   *  Essa função retorna 1 se a pessoa está visivel na listagem para que outras pessoas
   *  possam ve-la, e 0 caso não esteja visivel  
   */

  function visivel() {
    return $this->visivel;
  }
  /**
   * permite escolher se os recursos estão disponíveis para a pessoa,
   * por ex: userRole PUBLICO que é para as pessoas que não estão logadas dessa forma
   * os recursos não estão disponiveis para visualização   
   */  
  function visualiza() {
    return $this->visualiza;
  }
  /**
   * Retorna 1 caso a pessoa possa interagir no ambiente(ex:escrevendo mensagens em fóruns, chats, multivideo-chat)
   * Retorna 0 caso contrário.   
   */     
  function interage() {
    return $this->interage;
  }
  /**
   * Retorna o label que a pessoa está exercendo no momento
   */      
  function papel(){
      return $this->papel;
  }
  /**
   * Retorna 1 caso a pessoa tenha direito de administrador de sistema
   * Retorna 0 caso contrario
   */        
  function podeAdministrar() {
    if ($this->autoridade==ADMINISTRADOR_GERAL || $this->autoridade==ADM_NIVEL) {
      return true;
    }
    elseif($this->autoridade==PROFESSOR && ($this->nivel->relacionaAlunosProfessores || $this->nivel->nivelComunidade) && $this->interage()){
      return true;
    }else return false;
  } 
  /**
   * Essa função reune/retorna todos os direitos do usuário exercidos por uma determinada pessoa
   */         
  function getDireitosUsuario(){
    if($this->autoridade==ADMINISTRADOR_GERAL || $this->autoridade==ADM_NIVEL){
        $this->interage=1;
        $this->visualiza=1;
        $this->visivel=0;  
    
    }else{
      if($this->autoridade==PUBLICO){
        $this->interage=0;
        $this->visualiza=0;
        $this->visivel=0;
        $this->papel='Publico'; 
        
      }else{
        if($this->autoridade==PROFESSOR){
          $nomeFisicoTabelaPapel=$this->nivel->nomeFisicoTabelaPapelAdministradores;
          $nomeFisicoPKPapel=$this->nivel->nomeFisicoPKPapelAdministradores;
          $nomeFisicoTabelaRelacionamento=$this->nivel->nomeFisicoTabelaRelacionamentoProfessores;
          $nomeFisicoPKRelacionamento=$this->nivel->nomeFisicoPKRelacionamentoProfessores;
          $nomeFisicoTabela="professor";
          $nomeFisicoDescPapel="descTipoProfessor";
         
        }elseif($this->autoridade==ALUNO){
          $nomeFisicoTabelaPapel=$this->nivel->nomeFisicoTabelaPapelNaoAdministradores;
          $nomeFisicoPKPapel=$this->nivel->nomeFisicoPKPapelNaoAdministradores;      
          $nomeFisicoTabelaRelacionamento=$this->nivel->nomeFisicoTabelaRelacionamentoAlunos;
          $nomeFisicoPKRelacionamento=$this->nivel->nomeFisicoPKRelacionamentoAlunos;
          $nomeFisicoTabela="aluno";
          $nomeFisicoDescPapel="descTipoAluno";
          
        }
        
        $sql ="SELECT * FROM ".$nomeFisicoTabelaPapel." TP";
        $sql.=" INNER JOIN ".$nomeFisicoTabelaRelacionamento." TR ON (TP.".$nomeFisicoPKPapel."=TR.".$nomeFisicoPKPapel.")";
        $sql.=" INNER JOIN ".$nomeFisicoTabela." T ON (TR.".$nomeFisicoPKRelacionamento."=T.".$nomeFisicoPKRelacionamento.")";
        $sql.=" WHERE T.COD_PESSOA=".$this->codPessoa;
        $sql.=" AND TR.".$this->nivel->nomeFisicoPK."=".$this->codInstanciaNivel;
      //print_r($sql);
        $result=mysql_query($sql);
      
      //print_r($result);
        $direitosUsuario=mysql_fetch_object($result);
          
        $this->interage=$direitosUsuario->interage;
        $this->visualiza=$direitosUsuario->visualiza;
        $this->visivel=$direitosUsuario->visivel;  
        $this->papel=$direitosUsuario->$nomeFisicoDescPapel;
        }
    }        
  }
}
?>