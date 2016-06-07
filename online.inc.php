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

//
$ftp_server = "";
$ftp_user = "";
$ftp_pass = "";
$url = "http://localhost/navi/";

define(BD_HOST,"localhost");
define(BD_USER,"");
define(BD_SENHA,"");
define(BD_NAME,"navi");

define('TABELA_TORPEDO','torpedo');

//caminhos de upload
$caminhoUpload="/var/www/navi/upload_navi";

require_once('defineSession.php');

//Fun��o quote_smart replicada (pelo menos por enquanto) para poder ser utilizada
//no sistema de alive e no script de fotos, que n�o carregam todo o n�cleo

function quote_smart ($value) {
 // Stripslashes 
 if (get_magic_quotes_gpc()) { 
   $value = stripslashes ($value );
 } 
 // Quote if not integer 
 if (! is_numeric ($value )) { 
   $value ="'" .mysql_real_escape_string ($value ) . "'" ;
 } 
 return $value ;
}

//problemas na chamada dessa funcao no alive.php
function security($scriptPublico=0) { //scriptPublico: index (as vezes) e noticia (as vezes)
  global $url;
   
  if (!$scriptPublico)   {
    if (
        (empty($_SESSION['COD_PESSOA'])) ||  //teste basico da sessao
        //Dados do cliente para conferir
        ($_SESSION['REMOTE_ADDR']!=$_SERVER['REMOTE_ADDR']) || 
        ($_SESSION['HTTP_USER_AGENT']!=$_SERVER['HTTP_USER_AGENT']) ||
        (SERVER!=$_COOKIE['server']) ||
        ($_SESSION['COD_PESSOA']!=$_COOKIE['codPessoa']) ||
        ($_COOKIE['id']!=md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].SERVER.$_SESSION['COD_PESSOA']) )     
       )  {
       echo "<script>alert('Por favor informe novamente seu usu�rio e senha'); window.top.location.href='".$url."/logoff.php';</script>";  
    }
  }
}

?>
