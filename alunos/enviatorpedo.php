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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include("../config.php");
include($caminhoBiblioteca."/torpedo.inc.php");
include($caminhoBiblioteca."/perfil.inc.php");
session_name(SESSION_NAME); session_start(); security(); session_write_close();

if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die('Sem permissao de interacao'); }  

/*
 * Envio de torpedos. 
 */ 

$acao = $_REQUEST['acao'];
$codPessoaDestino = $_REQUEST['codPessoaDestino'];
$fechar=(int)$_REQUEST['fechar'];

//controle para voltar corretamente a pagina de origem
//antes de entrar na pagina de envio de torpedos
if (empty($_REQUEST['voltar'])) {
  $voltar=$_SERVER['HTTP_REFERER'];
}
else {
  $voltar=$_REQUEST['voltar'];
}


switch ($acao) {

  case "":
    
    echo "<link rel='stylesheet' href='".$urlCss."/".CSS_PADRAO."' type='text/css'>";
    echo "<body class='bodybg'><center>";
    echo "<h3>Enviar torpedo para ".printNome($codPessoaDestino)."</h3>";
    if ($_REQUEST['offline']) { echo "<span style='color:red;'>".printNome($codPessoaDestino)." est&aacute; OFFLINE. Somente ao entrar novamente no NAVi poder&aacute; ver o torpedo enviado agora.</span>"; }
    
    echo "<div id='msgTorpedo'>
    <form action='".$_SERVER['PHP_SELF']."?acao=A_envia&voltar=".$voltar."&fechar=".$fechar."&codPessoaDestino=".$codPessoaDestino."' method='POST' name='formTorpedo'>
    <textarea name='textoTorpedo' id='textoTorpedo' rows='10' cols='30' title='Escreva o torpedo'></textarea>
    <br><input type='submit' value='Enviar torpedo'>";
    if (empty($fechar)) {
      echo "<br><br><a href='".$voltar."'>Voltar</a>";
    }
    else {
      echo "<br><br><a href='#' onClick='window.close();'>Fechar</a>";    
    }
    echo "</form></div>";
    echo "</center><script>el=document.getElementById('textoTorpedo'); el.focus();</script></body>";
    break;


  case "A_envia":
    $codPessoaOrigem  = (int)$_SESSION['COD_PESSOA'];
    enviaTorpedo($codPessoaOrigem,$codPessoaDestino,$_REQUEST['textoTorpedo']);
    echo "<script>";
    echo "alert('Torpedo enviado.');";
    
    if (empty($fechar)) {      
      echo "window.location.href='".$_SERVER['PHP_SELF']."?&voltar=".$voltar."&fechar=".$fechar."&codPessoaDestino=".$codPessoaDestino."';";
    }
    else {
      echo "window.close();";
    } 
    echo "</script>";
    

    break;
}
?>