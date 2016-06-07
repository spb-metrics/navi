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


include_once ("../config.php");
include_once ($caminhoBiblioteca."/perfil.inc.php");
session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die; }

?>
 <html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body link="#6699CC" vlink="#6699CC" alink="#6699CC">

<div align=center>
	<?php

	  if ($_REQUEST["OPCAO"]=="Excluir")
     $sucesso = recadoApagar($_REQUEST["COD_RECADO"]);
    else
      {
      if($_REQUEST["OPCAO"]=="Lida")
        $sucesso = recadomsgLida($_REQUEST["COD_RECADO"]);
      else{
  		  $sucesso = recadoInserir($_REQUEST["COD_PESSOA_RECEBE"], $_REQUEST["TEXTO"]);
        
        $rsCon = vericaRecadoMail($_REQUEST["COD_PESSOA_RECEBE"]);
        $linha = mysql_fetch_array( $rsCon);
              
        if($linha["RECADO_MAIL"]){
          sendMessageRecado($linha["EMAIL_PESSOA"],$_SESSION["NOME_PESSOA"],$linha["NOME_PESSOA"]);
          }
        }
      }
  if($sucesso)
    echo "<script> location.href=\"./recados.php?COD_PESSOA_RECEBE=".$_REQUEST["COD_PESSOA_RECEBE"]."\";</script>"; 
	//===========================================================================================================

function sendMessageRecado($email_pessoa, $nome_pessoa, $nome_pessoa_recebe){
  ini_set("SMTP",SERVIDOR_SMTP);
GLOBAL $url;
	$body.=" Olá, " .$nome_pessoa_recebe.",\n\t" .$nome_pessoa." deixou um recado para você.".
    "\n\nVerifique seus recados '".$url."'";

 $body.= "\n\n\n".
         " Para não ser informado sobre o aviso de recebimento de recados pelo seu e-mail externo".
         " atualize seu cadastro desmarcando essa opção.\n".
		  	 " Atenciosamente\n".
		  	 "  Equipe NAVi";	

//echo "mail(".$mailAddress.",".$subject.",".$msg.",".$extraHeaders.");\n<br>";	

      @mail ($email_pessoa,
			"Navi - ".$nome_pessoa." escreveu um recado para você", 
			$body, 
			"From:noreply@navi.ufrgs.br\r\n" . 
			"X-Mailer: PHP/". phpversion());

}
	
?>

é