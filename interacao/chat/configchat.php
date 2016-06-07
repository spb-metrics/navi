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

ini_set("session.use_only_cookies",1);
define(BD_HOST,"localhost");
define(BD_USER,"");
define(BD_SENHA,"");
define(BD_NAME,"navi");

$url = "";
define('SERVER','localhostnavi2011');

/* 
 * Configura o padrao do chat, se é segmentar cada atividade com sua tabela de chat ou nao
 * instancias em particular podem ter configuracao diferente, definida na tabela chatconfiguracao   
 */
define('SEGMENTAR_TABELAS_CHAT',0);


require_once('../../defineSession.php');

//define(TIMEOUT_ALIVE,30);
if (!function_exists('quote_smart')) {
  // Quote variable to make safe 
  function quote_smart ($value )
  {
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
}

if (!function_exists('security')) {

  /*
   * Seguranca no acesso, chamada em todos os itens
   */
  function security($scriptPublico=0,$controlaInstancia=0) { //scriptPublico: index (as vezes) e noticia (as vezes)
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
         //note($_SESSION); note($_COOKIE); note($_SERVER); note($_REQUEST); exit;
         echo "<script>alert('Por favor informe novamente seu usuário e senha'); window.top.location.href='".$url."/logoff.php';</script>";  
      }
      $codInstanciaGlobal = (int)$_SESSION['codInstanciaGlobal'];    
      //funcao javascript que confere a instancia global atual com a gerada no topo
      if (!$controlaInstancia) {
        echo '<script>';
        echo 'if (window.top.document.getElementById("instanciaGlobal").innerHTML!='.$codInstanciaGlobal.') {'; 
        echo '  alert("Erro de acesso. Voce pode ter aberto mais de uma janela do NAVi."); window.top.location.href="'.$url.'/logoff.php"';
        echo '}';
        echo '</script>';
      } 
    }  
  }  
}
?>
