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

//numero de mensagens por pagina
$numMsgsPerPage = 10;

include("../../config.php");
include($caminhoBiblioteca."/correio.inc.php");
include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/utils.inc.php");
session_name(SESSION_NAME); session_start(); 
if ($_REQUEST['acao']=='A_le_anexo') {
  security(0,1);
}
else {
  security();
}

/*if (isset($_REQUEST['naoUsarEditorHTML'])) { 
  $_SESSION['naoUsarEditorHTML']=$_REQUEST['naoUsarEditorHTML']; 
}*/
if (empty($_SESSION["COD_PESSOA"])) {
  die("acesso negado");
}

//function note($x) {  echo "<pre>"; print_r($x); echo "</pre>";}

//desenha o inicio do html
function printHeader($params="") {
  global $url;
  echo "<html>";
  echo "<head>";
  echo "<link rel=\"stylesheet\" href=\"../../cursos.css\" type=\"text/css\">";
  echo "<link rel=\"stylesheet\" href=\"correio.css\" type=\"text/css\">";
  echo  "<script language=\"JavaScript\" src=\"".$url."/js/editor.js\"></script>".
	     "<script language=\"javascript\" type=\"text/javascript\" src=\"".$url."/js/tiny_mce/tiny_mce.js\"></script>";
  if (!empty($params["titulo"]))
    echo "<title>{$params["titulo"]}</title>";
  echo "</head>";
  echo "<body {$params["body"]}>";
  echo "<h3 class=\"titulo\">{$params["tituloPagina"]}</h3>";

}
function selectMsg()
{
	 $js.= "function selTodas() {\n";
   $js.= "  for(i=0; i< document.form1.elements.length; i++) {\n";
   $js.= "    document.form1.elements[i].checked = true;\n";
   $js.= "  }\n";
   $js.= "}\n";
   
   $js.= "function selNenhuma() {\n";
   $js.= "  for(i=0; i< document.form1.elements.length; i++) {\n";
   $js.= "    document.form1.elements[i].checked = false;\n";
   $js.= "  }\n";
   $js.= "}\n";
   
   echo "<script language=\"JavaScript\" type=\"text/javascript\">\n".$js."</script>";
}

function plotMsg($messages,$tipoCaixa) {
  global $usuarios;

	$colClass[1]= "colHeaderTabInbox";
	$colClass[2]= "colHeaderTabInboxOut";
	
  //desenha as mensagens
  if (!empty($messages->records)) {
    echo "<form name=\"form1\">";
    //seleção das mensagens
    echo "<div class=\"msgActions\" align=\"center\">Selecionar:&nbsp;";
    echo "<a href=\"#\" onclick=\"selTodas()\">Todas</a> | &nbsp;";
    echo "<a href=\"#\" onclick=\"selNenhuma()\">Nenhuma</a> | &nbsp;";
    echo "<a href=\"javascript: apagaSelecionadas()\">Apagar selecionadas</a></div>";
    //Tabela de mensagens
    echo "<table id=\"tabelaInboxMsg\" align=\"center\" cellspacing=\"1\">";
    echo "<tr><td class=\"colTabInbox ".$colClass[$tipoCaixa]."\">&nbsp;</td>";
    echo "<td class=\"colTabInbox ".$colClass[$tipoCaixa]."\">".$usuarios[$tipoCaixa]."</td>";
    echo "<td class=\"colTabInbox ".$colClass[$tipoCaixa]."\">Assunto</td>";
    echo "<td class=\"colTabInbox ".$colClass[$tipoCaixa]."\">Data</td></tr>";
    $par=0;
    foreach($messages->records as $msg)
	  {
      printMessage($msg,$par,$tipoCaixa);
      if ($par==0) { $par=1; } else {$par=0; }
    }
     
    echo "</table>";
    echo "</form>";
  }
  else {
    echo "<br><br><br><br><p align=\"center\"24/10/2005>N&atilde;o h&aacute; mensagem em sua caixa postal.</p>";
  }
}

function selectCheckboxes($messages,$tipoCaixa)
{   
	 $jsExcluir.= "function apagaSelecionadas() {\n";
   $jsExcluir.= " if (confirm('Voce realmente deseja apagar as mensagens selecionadas?')) {\n ";
   $jsExcluir.= "   url = 'index.php?acao=A_delete&tipoCaixa=".$tipoCaixa."';\n";
   $jsExcluir.= "   algumaSelecionada = false;\n";
   //monta array com os codigos das mensagens
   $jsExcluir.= "   msgArray = new Array(".count($messages->records)."); \n";
   foreach($messages->records as $key=>$msg) {
     $jsExcluir.= "   msgArray[".$key."] = ".$msg->codMsg.";\n";
   }
   $jsExcluir.= "   for(i=0; i < msgArray.length; i++) {\n";
   $jsExcluir.= "      if (document.form1['msg_' + msgArray[i]].checked) { \n";
   $jsExcluir.= "        algumaSelecionada=true; \n";
   $jsExcluir.= "        url+= '&codMsg[]=' + msgArray[i]\n";
   $jsExcluir.= "      }\n ";
   $jsExcluir.= "   } \n";
   $jsExcluir.= "   if (algumaSelecionada) {\n";
   $jsExcluir.= "     window.location.href = url;\n }";
   $jsExcluir.= " }\n";
   $jsExcluir.= "}\n";
    echo "<script language=\"Javascript\" type=\"text/javascript\">\n".$jsExcluir."</script>";
}

function menuCorreio() {
   //desenha o menu de ferramentas do correio
   echo "<table id=\"tool\" cellspacing=\"15\" align='center'><tr>";
   echo "<td><a href=\"".$_SERVER["PHP_SELF"]."?tipoCaixa=1&refresh=1\"><img src=\"./imagens/checarMensagens.gif\" border=\"no\"><br>Verificar Mensagens Recebidas</a></td>";
   echo "<td><a href=\"".$_SERVER["PHP_SELF"]."?acao=A_compoe_msg\"><img src=\"./imagens/comporMensagem.jpg\" border=\"no\"><br>Compor Mensagem</a></td>";
   echo "<td><a href=\"".$_SERVER["PHP_SELF"]."?tipoCaixa=2&changeFolder=1\"><img src=\"./imagens/enviadas.gif\" border=\"no\" height=\"48px\" width=\"48px\"><br>Mensagens Enviadas</a></td>";
   echo "</table>";
}


switch($_REQUEST["acao"]) {
 case "":
   /** 
     * MOSTRA AS MENSAGENS  
     * $tipoCaixa -> 1/2 indica se estamos vendo mensagens enviadas(2) ou recebidas(1)
     *               se estiver preenchido é a pasta em questão
     **/

  $tipoCaixa = $_REQUEST["tipoCaixa"];
  $changeFolder=$_REQUEST["changeFolder"];	

  /*entradas de testes
	if( empty($tipoCaixa)){
		echo "<br>to voltando com o tipo caixa vaziu<br>";}
	else{
  echo "<br>ola to voltando para o main ".$tipoCaixa;
  echo "<br>to na pasta".$pasta;

  echo "<br>e o change folder está ".$changeFolder;  
  echo "<br><br>Bom trabalho a todos!!<br>";}
/*termina aqui as entradas de teste*/

   //faz o count das mensagens se necessario
   //if ($_REQUEST["refresh"] || empty($tipoCaixa)|| ($tipoCaixa== 1)) {
   if ($_REQUEST["refresh"] || empty($tipoCaixa) ) {
     $tipoCaixa = 1;
	
	   $_SESSION["numMsgs"] = getMailCount($_SESSION["COD_PESSOA"],$tipoCaixa); 
     $_SESSION["timeLastMsgCheck"] = time();
   }
   elseif ($_REQUEST["changeFolder"]){
     $_SESSION["numMsgs"] = getMailCount($_SESSION["COD_PESSOA"],$tipoCaixa); 
   }

   // Verificamos se há novas mensagens, mesmo que não estejamos na caixa de entrada, 
   // para avisar o usuario de que há mensagens novas
   if ((time() - $_SESSION["timeLastMsgCheck"]) > 600 ) 
   {
     $_SESSION["numMsgs"] = getMailCount($_SESSION["COD_PESSOA"], $tipoCaixa); 
     $_SESSION["timeLastMsgCheck"] = time();
     /* ESCREVER CODIGO AQUI PARA AVISAR DE QUE CHEGARAM MENSAGENS*/
	   echo "<B>Chegaram novas mensagens!!";
   }
     
   //seta a pagina que esta olhando ( por padrao a 1)
   if(empty($_SESSION["folderPage"]) || $_REQUEST["changeFolder"] || $_REQUEST["refresh"])
     $_SESSION["folderPage"] = 1;
   elseif (!empty($_REQUEST["numFolderPage"])) {
     $_SESSION["folderPage"] = $_REQUEST["numFolderPage"];
   }

   //imprime o cabecalho html
   $params["tituloPagina"]="Correio - Caixa de mensagens";
   printHeader($params);

	 echo	"<div id=\"divInbox\">";
   
   if (isset($_REQUEST["incluiu"]))
     echo "<p>Mensagem enviada com sucesso!</p>";
   
   if(isset($_REQUEST["deletou"]))
     echo "<p>Mensagens deletadas com sucesso!</p>";

   // Menu de opcoes
   menuCorreio();

   //javascript para selecionar todas ou nenhuma mensagem
	 selectMsg();  

   //le as mensagens (apenas da pagina atual)
  /* echo "folder Page".$_SESSION["folderPage"];*/
   $msgIni = calcCorreioIniMsg($_SESSION["folderPage"],$numMsgsPerPage);
   $messages = getMailMessages($_SESSION["COD_PESSOA"],$msgIni,$numMsgsPerPage,$tipoCaixa);
  
   //desenha mensagem
   plotMsg($messages,$tipoCaixa);
   //javascript para a exclusao das mensagens
   //ve quais os checkboxes estao ligados e redireciona para acao A_delete com o array
   //com os codigos das mensagens formado
   selectCheckboxes($messages,$tipoCaixa);
   
   //imprime a paginacao
   imprimePaginacaoCorreio($_SESSION["numMsgs"],$_SESSION["folderPage"],$numMsgsPerPage,$tipoCaixa);
   
   echo "</div>".
	    "</body>".
	    "</html>";
   
   break;

 case "A_le_msg":
   /** LE A MENSAGEM **/

   //imprime header html
   printHeader();
   
   //le a mensagem do banco 
    
   $msg = getMessage($_REQUEST["codMsg"],$_SESSION['COD_PESSOA'],$_REQUEST["tipoCaixa"]);
   //mostra ela
   showMessage($msg,$_REQUEST["tipoCaixa"]);
      
   if ($_REQUEST['tipoCaixa'] == MAIL_INBOX && empty($msg->lida)) {  
     //marca ela como lida se isso nao foi feito ainda
     //(apenas qdo estiver na caixa de entrada)
     marcaMsgLida($msg,$_SESSION["COD_PESSOA"]);
   }
   echo "</body>";
   echo "</html>";

   break;

  case "A_compoe_msg":
    /** FORMULARIO DE COMPOSICAO DA MENSAGEM **/
    printHeader($params);
   
     if (isset($_REQUEST["msgReply"])) {

     $msgReply = getMessage($_REQUEST["msgReply"],$_SESSION['COD_PESSOA'],$_REQUEST["tipoCaixa"]);
     $elementFocus = "message";
    }
    else {
     $elementFocus = "to";
    }
    
    $js = "function verificaFormMsgMail() {\n".
         "  if (document.form1.to.value == '') { alert('Por favor coloque algum destinatário para a mensagem.'); document.form1.to.focus(); return false; }\n".
         "  if (document.form1.subject.value == '') { alert('É necessário que a mensagem possua um assunto.'); document.form1.subject.focus(); return false; }\n".   
         "  if (!checkAnexosFileNames()) { alert('Alguns dos arquivos anexos possuem o mesmo nome. Por favor renomeie eles e tente enviar novamente.'); return false; }\n".
         "  return true;".
         "}";
    echo "<script type=\"text/javascript\">".$js."</script>";         
    echo "<script type=\"text/javascript\" src=\"correio.js\"></script>";
    
    echo "<form name=\"form1\" id=\"form1\" method=\"POST\" action=\"".$_SERVER["PHP_SELF"]."?acao=A_envia_msg&tipoCaixa=".$_REQUEST['tipoCaixa']."\" enctype=\"multipart/form-data\">";  
    echo "<p class=\"titulo\">Enviar mensagem</p>";
    echo "<table align=\"left\" width=\"400\" id=\"tblCompoeMsg\"><tbody>";
    
    if (!empty($msgReply)) {
      if ($_REQUEST['replyToAll']) {
       //responde a todos os destinatarios da msg original
       $destinatarios = getMsgDests($msgReply->codMsg);
       $dest = "";
       $dest = $msgReply->USER_PESSOA;
        foreach($destinatarios->records as $destinatario) {
         if (!empty($dest)) $dest.= ", ";
           $dest.= $destinatario->user; 
        }
      }
      else {
       //responde apenas ao autor da msg original
      $dest = $msgReply->USER_PESSOA;
      }     
    }
    elseif (!empty($_REQUEST["to"]))
     $dest = $_REQUEST["to"];
    
    else
     $dest = "";
    
    
    echo "<tr><td class=\"to\">Para<br>&nbsp;  </td><td><input name=\"to\" id=\"to\" type=\"text\" value=\"".$dest."\" size=\"50\"><br><a href=\"{$url}/interacao/correio/index.php?acao=A_procura_user\" target=\"_blank\">Procura usu&aacute;rio</a>";
    echo "&nbsp; | &nbsp;<a href=\"#\" onclick=\"javascript: document.form1.to.value = '';\">Limpar</a>";
    
    echo "<br><small>(nomes de usu&aacute;rio separados por virgula ",")</small></td></tr>";
    
    echo "<tr><td class=\"to\"  >Assunto</td><td>";
    
    if (empty($msgReply))
     echo "<input name=\"subject\" type=\"text\" value=\"".$_REQUEST["subject"]."\" size=\"50\">";
    else
     echo "<input name=\"subject\" type=\"text\" value=\"Re : ".$msgReply->subject."\" size=\"50\">";
    
    echo "</td></tr>";
    
    
    echo "<tr><td colspan=\"3\">";
    //usar ou nao o editor html
     echo ativaDesativaEditorHtml();
     echo "<br>";
    /*if ($_SESSION['naoUsarEditorHTML']) {
      echo "<a href='".$_SERVER['PHP_SELF']."?naoUsarEditorHTML=0&acao=A_compoe_msg'>Usar o editor HTML</a><br>";
    }
    else {
      echo "<a href='".$_SERVER['PHP_SELF']."?naoUsarEditorHTML=1&acao=A_compoe_msg'>Não usar o editor HTML</a>";
    } */
    //Mensagem propriamente dita    
    echo "<textarea rows=\"15\" cols=\"70\" name=\"message\">";
    
    if (!empty($msgReply))
     echo "\n\n\n----Mensagem Original ---\n".$msgReply->msg;
    elseif (!empty($_REQUEST["message"]))
     echo $_REQUEST["message"];
    
    echo "</textarea></td></tr>";
    
    echo "<tr><td class=\"anexosTitle\">Anexos</td></tr>";
    echo "<tr id=\"linhaAnexo1\"><td colspan=\"3\"><input type=\"file\" name=\"anexo_1\" onchange=\"addNewAnexoCtrl()\"></td></tr>";
    
    
    echo "<tr align=\"center\"><td align=\"center\" ><a href=\"#\" onclick=\"javascript: if (verificaFormMsgMail()) { document.form1.submit(); }\" ><img src=\"./imagens/enviarMensagem.jpg\" border=\"no\"><br>Enviar Mensagem</a></td>";
    echo "<td><a href=\"#\" onclick=\"window.location.href = 'index.php?tipoCaixa=".$_REQUEST['tipoCaixa']."';\" ><img src=\"./imagens/cancelar.jpg\" border=\"no\"><br>Cancelar</a></td>";
    echo "</tr>";
    echo "</tbody></table>";
    echo "</form>";
    
    imprimeMeusContatos($_SESSION["COD_PESSOA"]);
    
    //Adiciona script para colocar foco no "to" ( compor msg nova) ou "messagem" (na resposta)
    echo "<Script> form1.{$elementFocus}.focus(); </Script>";
    
    echo "</body>";
    echo "</html>";
    
    break;

 case "A_envia_msg":
    $message = str_replace("\n", "<br>", $_REQUEST["message"]);
   /** GRAVA E ENVIA A MENSAGEM **/

   //le os ids a partir dos nomes de usuario
   getUsersIds($_REQUEST["to"],$_SESSION["COD_PESSOA"],$usersMailInterno,$usersMailExterno);
   
   //grava caixa entrada 
   sendMessage($_SESSION["COD_PESSOA"],$usersMailInterno,$usersMailExterno,$_REQUEST["subject"],$message,$_SESSION['CORREIO_MAIL_PESSOA'],$_FILES);

   //redireciona para a listagem de mensagens
   echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?incluiu=1&refresh=1&tipoCaixa='.$_REQUEST['tipoCaixa'].'";</script>';
   //header("Location: ".$_SERVER["PHP_SELF"]."?incluiu=1&refresh=1&tipoCaixa=".$_REQUEST['tipoCaixa']);
   exit;
   
   break;
   
 case 'A_le_anexo':
  
   /** LE O CONTEUDO DE UM ANEXO E MANDA PARA O BROWSER **/
   
   enviaAnexoBrowser($_REQUEST['codMsg'],$_REQUEST['nomeArq']); 
     
 break;  

 case "A_delete":
   /** DELETA A(s) MENSAGENS 
    $_REQUEST["codMsg"] eh um array com os codigos das mensagens a ser deletadas
   **/   
 
   deleteMessages($_SESSION["COD_PESSOA"],$_REQUEST["codMsg"],$_REQUEST["tipoCaixa"]);
   echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?deletou=1&tipoCaixa='.$_REQUEST["tipoCaixa"].'";</script>';
   //header("Location: ".$_SERVER["PHP_SELF"]."?deletou=1&tipoCaixa=".$_REQUEST["tipoCaixa"]);
   //volta para para a caixa que chamou 
   exit;

   break;

 case "A_procura_user":
   /** PROCURA USUARIO **/
   $params["titulo"] = "Procura usu&aacute;rio";
   printHeader($params);
   
   echo "<form name=\"form1\" action=\"".$_SERVER["PHP_SELF"]."?acao=A_procura_user\" method=\"POST\">";
   echo "Digite parcialmente o nome do usu&aacute;rio ou nome da pessoa<br>";
   echo "<input type=\"text\" name=\"nome\" value=\"".$_REQUEST["nome"]."\">&nbsp;";
   echo "<input type=\"submit\" value=\"Procurar\">";
   echo "</form>";

   $js = "function adicionaPessoaDest(nomUser) {\n";
   $js.= "  if (window.opener.document.form1.to.value != '') \n";
   $js.= "     window.opener.document.form1.to.value+= ', ';\n";
   $js.= "  window.opener.document.form1.to.value += nomUser;\n";
   $js.= "}\n";
   echo"<p align='center'><a href='javascript:window.close()'>Fechar Janela</a></p>";
   echo "<script language=\"JavaScript\" type=\"text/javascript\">\n".$js."</script>";

   if ($_REQUEST["reloadOpener"]) {
     //deve recarregar a janela pai, de compor a mensagem
     //isso server para atualizar os meus contatos
     echo "<script language=\"JavaScript\" type=\"text/javascript\">";
     echo "formComp = window.opener.document.form1;\n";
     echo "to = formComp.to.value;\n";
     echo "subject = formComp.subject.value;\n";
     echo "message = formComp.message.value;\n";
     echo "window.opener.location.href = '".$_SERVER["PHP_SELF"]."?acao=A_compoe_msg&to='+to+'&subject='+subject+'&message='+message;\n";
     echo "</script>";
     }

   if (isset($_REQUEST["nome"])) {
     $pessoas = procuraPessoas($_REQUEST["nome"]);
     if ($pessoas == -1) {
       //encontrou muitos registros
       echo "<p>Muitas pessoas foram encontrados. Por favor refine sua pesquisa.</p>";
     /* echo"<p align='center'><a href='javascript:window.close()'>Fechar Janela</a></p>";*/
     }
     elseif ($pessoas == 0) {
       echo "<p>N&atilde;o foram encontrados pessoas. Por favor altere sua pesquisa.</p>"; 
     /* echo"<p align='center'><a href='javascript:window.close()'>Fechar Janela</a></p>";*/
     }
     
     else {

       echo "<center>";
       echo "Clique no <b>NOME</b> da pessoa para adicion&aacute;-la como destinat&aacute;ria da mensagem:<br> ";
       echo "<table width=\"75%\" class=\"tabelaProcuraUsuarios\" cellspacing=\"1\">";
       $par=0;
       foreach($pessoas->records as $pessoa) {
	       echo "<tr class=\"linhaProcura{$par}\"><td width=\"60%\"><a href=\"#\" onclick=\"adicionaPessoaDest('".$pessoa->USER_PESSOA."')\">".$pessoa->NOME_PESSOA." (".$pessoa->USER_PESSOA.")</a></td>";
	       echo "<td width=\"40%\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=A_add_contato&codPessoaContato=".$pessoa->COD_PESSOA."&nome=".$_REQUEST["nome"]."\">&nbsp;&nbsp;&nbsp;[Cadastrar em Meus Contatos]&nbsp;&nbsp;&nbsp;</a></td></tr>";
         if ($par==0) { $par=1;} else { $par=0;}

       }
         echo "</center>";
      }
   
   }

      //Adiciona script para colocar foco na caixa de busca
   echo "<Script> form1.nome.focus(); </Script>";
   
   break;   
   
 case "A_add_contato":
   
   /** ADICIONA EM MEUS CONTATOS **/
   adicionaContato($_SESSION["COD_PESSOA"],(int)$_REQUEST["codPessoaContato"]);
   echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=A_procura_user&nome='.$_REQUEST["nome"].'&reloadOpener=1";</script>';
   //header("Location: ".$_SERVER["PHP_SELF"]."?acao=A_procura_user&nome=".$_REQUEST["nome"]."&reloadOpener=1");
   exit;
  
   break;

 case "A_deleta_contato":
   /** DELETA CONTATOS **/
   if (is_array($_REQUEST["codPessoasContato"]) && count($_REQUEST["codPessoasContato"]) > 0) {
     deletaContatosListaPessoas($_SESSION["COD_PESSOA"],$_REQUEST["codPessoasContato"]);
   }
   echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=A_compoe_msg&to='.$_REQUEST['to'].'&subject='.$_REQUEST['subject'].'&message='.$_REQUEST['message'].'";</script>';
   //header("Location: ".$_SERVER["PHP_SELF"]."?acao=A_compoe_msg&to=".$_REQUEST["to"]."&subject=".$_REQUEST["subject"]."&message=".$_REQUEST["message"]);
   exit;
   
   break;   
}

?>
