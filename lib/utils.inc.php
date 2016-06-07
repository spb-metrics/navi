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
 * prepara texto para ser colocado no browser
 * usa um strtr padrao
 */
function preparaTexto($texto) {
  $entrada = array(" < "," > ","\n");
  $saida = array("&lt;","&gt;","<br>");
  $texto = str_replace($entrada,$saida,$texto);
  $texto = stripslashes($texto);
  return $texto;
}
/**
 * Funcao REPLICADA aqui do chat, verificar uma possivel otimiza��o
 */ 
function isProfessor($codPessoa,$codInstanciaNivel, $codNivel) {
  $nivel = new Nivel($codNivel);
  if (!$nivel->relacionaPessoas()) { return false; }

  $sql = ' select 1 from professor P,'.$nivel->nomeFisicoTabelaRelacionamentoProfessores.' REL ';
  $sql.= ' where P.COD_PROF=REL.'.$nivel->nomeFisicoPKRelacionamentoProfessores;
  $sql.= ' AND P.COD_PESSOA='.$codPessoa;
  $result = mysql_query($sql); 
  $num = mysql_num_rows($result);
  return $num;
}

/**
 * Descompacta um arquivo .zip para dentro de um direito $extractTo
 */
function descompactaZip($fileZip,$extractTo) {
  $zip = zip_open($fileZip);
 
  if (!file_exists($extractTo))
    //cria o diretorio de extracao se ele nao existe 
    mkdir($extractTo);
 
  $extractTo.= '/';
 
  while($zip_entry = zip_read($zip)) {
    $entry = zip_entry_open($zip,$zip_entry);
    $filename = zip_entry_name($zip_entry);
    $target_dir = $extractTo.substr($filename,0,strrpos($filename,'/')); 
    $filesize = zip_entry_filesize($zip_entry);
    if (is_dir($target_dir) || mkdir($target_dir)) {
       if ($filesize > 0) {
           $contents = zip_entry_read($zip_entry, $filesize);        
           $fh = fopen($extractTo.$filename,'w'); 
           fwrite($fh,$contents);
           fclose($fh);
       }
    }
  }
}
/**
 *  fun��o para escolher se quer ou n�o o editor html
 */ 

function ativaDesativaEditorHtml(){
  global $urlImagem;
  $variaveisRequest ='';
  
  $_SESSION['naoUsarEditorHTML']=!(getUsoEditorHTML($_SESSION['COD_PESSOA']));
  
  if (isset($_REQUEST['naoUsarEditorHTML'])) $_SESSION['naoUsarEditorHTML']=$_REQUEST['naoUsarEditorHTML'];
  if (!$_SESSION['naoUsarEditorHTML']) {
    echo "<script language=\"JavaScript\" type=\"text/javascript\">initHtmlEditor('200');</script>";
  } 
            foreach($_REQUEST as $variavel=>$valor) {
                  if ($variavel!='naoUsarEditorHTML') {
                    $variaveisRequest.= "&".$variavel."=".$valor;
                  }
                }
                   								
                if ($_SESSION['naoUsarEditorHTML']) {
                  $html= "<a href='".$_SERVER['PHP_SELF']."?naoUsarEditorHTML=0".$variaveisRequest."'align= \"top\">Usar o editor HTML</a>";
                }
                else {
                 $html="<span onMouseOver=\"document.getElementById('ajuda').style.visibility = 'visible';\" onMouseOut=\"document.getElementById('ajuda').style.visibility = 'hidden';\">";
                 $html.= "<a href='".$_SERVER['PHP_SELF']."?naoUsarEditorHTML=1".$variaveisRequest."'>N�o usar o editor HTML<img src=\"".$urlImagem."/help2.jpg\" border=\"no\"></a>";
                 $html.= "</span>";
                 $html.= "<div id=ajuda align=\"justify\" style=\"position:absolute; overflow: visible; visibility: hidden; background-color: white; border: 1px solid black; z-index:3;\">";
	               $html.= "Seu navegador pode ter restri��es com o Editor HTML. <br>Se n�o esta sendo poss�vel escrever na caixa de texto clique em \"N�o usar o editor HTML\"</div>";
  
                } 
               
            
    return $html;
}

//fun��o para adicionar o edito mathML
function ativaDesativaEditorMathMl($codInstanciaGlobal,$userRole){
    global $urlImagem;
    global $url;
    
    $variaveisRequest ='';
    $configMathml = new InstanciaGlobal ($codInstanciaGlobal);
    
    if (isset($_REQUEST['unSetMathml'])) { 
      if(!$_REQUEST["unSetMathml"]){
        $configMathml->setUsoMathml();
      }
      else  {
        $configMathml->unsetUsoMathml();
      }
    }
    
    $_SESSION['unSetMathml']= $configMathml->getUsoMathml();
    
     
  //  if($configMathml->getUsoMathml()==1 && empty($_REQUEST['unSetMathml']) && (!empty ($_SESSION['unSetMathml'])) ){
  if($configMathml->getUsoMathml()==1){
      $_SESSION['configMathml']['script']="<script type='text/javascript' src='".$url."/js/ASCIIMathML.js'></script>";
    }
    else {
      $_SESSION['configMathml']['script']="";
    }
   

     
    if($userRole!=ALUNO){
		  foreach($_REQUEST as $variavel=>$valor) {
          if ($variavel!='unSetMathml') {
              $variaveisRequest.= "&".$variavel."=".$valor;
          }
     }
    
    if ($configMathml->getUsoMathml()==1) {
        $html="<a href='".$_SERVER['PHP_SELF']."?unSetMathml=1".$variaveisRequest."'>N�o usar MathMl</a>";
        $html.= "<a target=\"_blanck\" href='".$url."/interacao/forum/instrucaoUsoPlugginMathml.php'><img src=\"".$urlImagem."/help.jpg\" border='no' title='Ajuda, Editor MathMl.'></a>";
     }
     else {
         $html= "<a href='".$_SERVER['PHP_SELF']."?unSetMathml=0".$variaveisRequest."'>Usar MathMl </a>";
   		   $html.= "<a target=\"_blanck\" href='".$url."/interacao/forum/instrucaoUsoPlugginMathml.php'><img src=\"".$urlImagem."/help.jpg\" border='no' title='Ajuda, Editor MathMl.'></a>";
   
    }
   		
    
   
  }
  else{$html.= "<a target=\"_blanck\" href='".$url."/interacao/forum/instrucaoUsoPlugginMathml.php'><img src=\"".$urlImagem."/help.jpg\" border='no' title='Ajuda, Editor MathMl.'>Instalar Pluggin MathMl</a>";}


   return $html;
}

/**
*funcao que salva as configuracoes da pessoa na tabela configuracaopessoa
*/
function  salvaConfiguracaoPessoa($codPessoa,$config){
  $sql="SELECT * FROM configuracaopessoa WHERE codPessoa=".$codPessoa;
	$result=mysql_query($sql);
  if(mysql_num_rows($result) >0){
     $sql="UPDATE configuracaopessoa set ativaEditorHTML='".$config['ativaEditorHTML']."' WHERE codPessoa=".$codPessoa."";
	}
  else{
     $sql="INSERT INTO configuracaopessoa (codPessoa, ativaEditorHTML) VALUES (".$codPessoa.",'".$config['ativaEditorHTML']."')";
  }
   //print_r($sql);
    $result = mysql_query($sql); 
	  return;
 } 
 
 /**
  *get uso Editoir HTML
  */   
  function getUsoEditorHTML($codPessoa){
   $sql="SELECT ativaEditorHTML FROM configuracaopessoa WHERE codPessoa=".$codPessoa;
	 $result=mysql_query($sql);
	 $linha=mysql_fetch_array($result);

  return $linha["ativaEditorHTML"];

  }
  
//======================================================================================================
// fun��o par mostrar arquivos em geral  utils/topo.pdf

/*function arquivosLinkados($codArquivo) 
{	
	 
  if ($codArquivo != "")
  {
      $strSQL = "SELECT  A.DESC_ARQUIVO".
	" A.CAMINHO_LOCAL_ARQUIVO, A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO ".
	" FROM arquivo A WHERE ".
	" A.COD_ARQUIVO =". $codArquivo;
  }
  else{
   echo "Sem refer�ncia ao arquivo";
   exit();
  }
 
  return mysql_query($strSQL);					
}
//usado na manipula��o de fotos
function checarFotosPessoas() {
  $sql= "SELECT  COD_PESSOA,FOTO,FOTO_REDUZIDA FROM pessoa";
  $result = new RDCLQuery($sql);
  return $result;
} */

?>
