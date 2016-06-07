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

include_once("CLDb.inc.php");
include("funcoesftp.inc.php");

//define RDCLQuery e quote_smart

/* define a origem e o destino da mensagem
 * remetente é utilixado para saber quem mandou a mensagem
 * destinatario é para quem quer 
*/
$usuarios[1]="De";
$usuarios[2]="Para";

/**
 * Constanstes para os tipos de caixa
 * MAIL_INBOX -> caixa de entrada
 * MAIL_OUTBOX -> caixa de saida  
 */ 
define("MAIL_INBOX",1);
define("MAIL_OUTBOX",2);


define('MAIL_LIMITE_MOSTRA_DESTS_INLINE',4000);

/**
 * Numero maximo de caracteres q podem aparecer no campo de detinatarios (para)
 * na listagem das mensagens na caixa de saida  
 */ 
define('MAIL_LIST_OUTBOX_DEST_MAX_CHARS',35);

/**
 * Numero maximo de caracteres q podem aparecer no campo de detinatarios (para)
 * na listagem das mensagens na caixa de saida  
 */ 
define('NO_REPLY_ADDRESS','noreply@navi.ufrgs.br');



/** FUNCOES PARA MANIPULACAO DO CORREIO INTERNO **/

/**
 * Lista as mensagens
 * @param $tipoCaixa Diz se eh da caixa de entrada ou saida 
 */ 
function getMailMessages($userId,$start=0,$numOfMsgs=10,$tipoCaixa=MAIL_INBOX,$codMsg='') {
  if ($tipoCaixa == MAIL_INBOX)
    return getInboxMessages($userId,$start,$numOfMsgs,$codMsg);
  elseif($tipoCaixa == MAIL_OUTBOX)
    return getOutboxMessages($userId,$start,$numOfMsgs,$codMsg);
}

/**
 * Retorna uma mensagem em especifico
 * @param $tipoCaixa Diz se eh da caixa de entrada ou saida 
 */ 
function getMessage($codMsg,$userId,$tipoCaixa=MAIL_INBOX) {
  $msg =  getMailMessages($userId,0,1,$tipoCaixa,$codMsg);
  return $msg->records[0];
}

/**
 * Retorna a qtde de mensagens
 * @param $tipoCaixa Diz se eh da caixa de entrada ou saida 
 */ 
function getMailCount($userId,$tipoCaixa=MAIL_INBOX) {
  if ($tipoCaixa == MAIL_INBOX)
    return getInboxCount($userId);
  elseif($tipoCaixa == MAIL_OUTBOX)
    return getOutboxCount($userId);
}

/**
 *  Obtem as mensagens da caixa postal do usuario
 */
function getInboxMessages($userId,$start=0,$numOfMsgs=10,$codMsg='') {
  $sql = "SELECT MSG.codMsg,user_from,user_to,subject,msg,data,lida,P.NOME_PESSOA as pessoa,P.USER_PESSOA ";
  $sql.= " FROM correio_msg as MSG";
  $sql.= " INNER JOIN correio_msg_dest as dest ON (MSG.codMsg = dest.codMsg)";
  $sql.= " INNER JOIN pessoa as P ON (P.COD_PESSOA=MSG.user_from)";
  $sql.= " WHERE ";
  $sql.= " dest.user_to=".quote_smart($userId);
  
  if (empty($codMsg)) {
    //le um conjunto de mensagens 
    $sql.= " AND (dest.excluida IS NULL or dest.excluida=0) ";
  }
  else {
    //le uma msg especifica
    $sql.= " AND MSG.codMsg=".quote_smart($codMsg);  
  }
  
  $sql.= " ORDER BY data desc ";
  $sql.= " LIMIT ".$start.",".$numOfMsgs;
    
  return new RDCLQuery($sql);
}

/**
 * Obtem as mensagens da caixa de saida
 */ 
function getOutboxMessages($userId,$start=0,$numOfMsgs=10,$codMsg='') {
  $sql = "SELECT MSG.codMsg,user_from,subject,msg,data,P.NOME_PESSOA as pessoa ";
  $sql.= " FROM correio_msg as MSG";
  $sql.= " INNER JOIN pessoa as P ON (P.COD_PESSOA=MSG.user_from)";
  $sql.= " WHERE ";
  
  if (empty($codMsg)) {
    //le um conjunto de msgs
    $sql.= "MSG.user_from=".quote_smart($userId);
    $sql.= " AND (MSG.caixaSaidaExcluida IS NULL OR MSG.caixaSaidaExcluida=0)";
  }
  else {
    //le uma msg especifica
    $sql.= " MSG.codMsg=".quote_smart($codMsg);
  }
  
  $sql.= " ORDER BY data desc ";
  $sql.= " LIMIT ".$start.",".$numOfMsgs;
  return new RDCLQuery($sql);  
}

/**
 *  Faz um count para ver a qtde de mensagens da caixa de entrada
 */
function getInboxCount($userId) {
  $sql = "SELECT COUNT(*) as numMsgs FROM correio_msg_dest ";
  $sql.=" WHERE user_to=".quote_smart($userId)." AND (excluida IS NULL or excluida=0)"; 
  $result = new RDCLQuery($sql);
  return $result->records[0]->numMsgs;  
}

/**
 *  Faz um count para ver a qtde de mensagens da caixa de saida
 */
function getOutboxCount($userId) {
  $sql = "SELECT COUNT(*) as numMsgs FROM correio_msg ";
  $sql.=" WHERE user_from=".quote_smart($userId)." AND (caixaSaidaExcluida IS NULL or caixaSaidaExcluida=0)"; 
  $result = new RDCLQuery($sql);
  return $result->records[0]->numMsgs;  
}

/**
 * Retorna uma lista com todos os destinatarios de determinada msg
 */ 
function getMsgDests($codMsg) {
  $sql = "SELECT user_to, P.NOME_PESSOA as pessoa, P.USER_PESSOA as user".
         " FROM correio_msg_dest as dest".
         " INNER JOIN pessoa P ON (P.COD_PESSOA=dest.user_to)".
         " WHERE codMsg=".quote_smart($codMsg);         
  return new RDCLQuery($sql);
}

/**
 * Retorna o codigo do remetente de uma msg
 */ 
function getCodRemetenteMsg($codMsg) {
  $sql = "SELECT user_from FROM correio_msg WHERE codMsg=".(int)$codMsg;
  $msgRes = new RDCLQuery($sql);
  return $msgRes->records[0]->user_from;
}

/**
 * Retorna uma lista dos anexos da msg
 */ 
function correioMsgGetAnexos($codMsg,$nomeArq='') {
  $sql = "SELECT codMsg,nomeArq,mimeType FROM correio_msg_anexo WHERE codMsg=".(int)$codMsg;  
  if (!empty($nomeArq))
    $sql.= " AND nomeArq=".quote_smart($nomeArq);
  
  return new RDCLQuery($sql);
}

/**
 * Retorna o md5 do anexo
 */ 
function correioGetMd5Anexo($anexo) {
  global $caminhoUpload;
  global $caminhoUpload1;
  
  $codRemetente = getCodRemetenteMsg($anexo->codMsg);
  $path = $caminhoUpload."/anexosCorreio/".$codRemetente."/".$anexo->codMsg."/".$anexo->nomeArq;
  if (!file_exists($path)) {
    $path = $caminhoUpload1."/anexosCorreio/".$codRemetente."/".$anexo->codMsg."/".$anexo->nomeArq;
  }
  
  return md5_file($path);
}

/*
 * Salva os anexos da msg
 * O local de armazenamento dos arquivos anexos eh o seguinte:
 * $caminhoUpload / anexosCorreio / codigo do usuario remetente / codigo da mensagem  
 */ 
function salvaAnexos($files,$codMsg,$codRemetente) {
  global $caminhoUpload;

  //controla se tem anexo
  $numAnexos = 0;

  $path = $caminhoUpload."/anexosCorreio";
  
  //cria o diretorio de upload se for necessario
  if (!file_exists($path)) {
    mkdir($path);
  }
  //cria o diretorio de upload da pessoa q ta enviando a msg se for necessario
  $path = $path. "/".$codRemetente;
  
  if (!file_exists($path)) {
    mkdir($path);
  }

  //cria a pasta da mensagem
  //  $path = realpath($path."/".$codMsg);
  $path = $path."/".$codMsg;
  @mkdir($path);
   
  $i = 1;
  foreach($files as $fileDesc) {
    if (!empty($fileDesc['tmp_name']) && $fileDesc['size'] > 0) {
       $sucesso = false;

       $nomeArq = $i."_".$fileDesc["name"];
       
       //faz o upload do arquivo
       $sucesso = move_uploaded_file($fileDesc['tmp_name'], $path."/".$nomeArq);
       duplica($path."/".$nomeArq,$nomeArq,"anexosCorreio/".$codRemetente."/".$codMsg);
       
       if ($sucesso) {
          //grava no bco a referencia para o anexo
          $sql = "INSERT into correio_msg_anexo (codMsg, nomeArq, mimeType) VALUES (".quote_smart($codMsg).",".quote_smart($nomeArq).",".quote_smart($fileDesc['type']).")";
          mysql_query($sql);
          $sucesso = !mysql_errno();
       } 
       
       
       if (!$sucesso) {
         echo '<b>Erro ao salvar anexo '.strip_tags($fileDesc["name"])."</b><br>"; 
       }
       else {
       	 $numAnexos++;
       }
       
       $i++; 
    }  
  }  
  
  return $numAnexos;
}


/**
 * Deleta os anexos de determinada msg
 */ 
function correioDeletaAnexos($codMsg) {
  global $caminhoUpload;
  
  $anexos = correioMsgGetAnexos($codMsg);
  $sucesso = true;

  if (!empty($anexos->records)) {

    $codRemetente = getCodRemetenteMsg($codMsg);

    //caminho do diretorio de anexos da msg
    $path = $caminhoUpload."/anexosCorreio/".$codRemetente."/".$codMsg;

    //deleta os anexos no FS e os registros no bco tb
    foreach($anexos->records as $anexo) {
      //deleta o arquivo
      $suc = unlink($path."/".$anexo->nomeArq);        
     // delete_via_ftp("anexosCorreio/".$codRemetente."/".$codMsg."/".$anexo->nomeArq);
      $sucesso = $sucesso && $suc; 
    }
    
    //deleta os anexos no bco
    $sql = "DELETE FROM correio_msg_anexo WHERE codMsg=".(int)$codMsg;
    mysql_query($sql);
    if (mysql_errno())
      $sucesso = false;  

    //deleta o diretorio de anexos da msg
    $suc = rmdir($path);
    $sucesso = $sucesso && $suc;
  }

  return $sucesso;  
}


/**
 *  Marca a mensagem como lida
 */
function marcaMsgLida(&$msg,$userId) {
  $sql = "UPDATE correio_msg_dest SET lida=1 WHERE codMsg=".quote_smart($msg->codMsg)." AND user_to=".quote_smart($userId);
  return mysql_query($sql);
}

/**
 *  A partir de uma lista de nome de usuarios separada por , retorna os codigos deles num array
 *  Retorna em $codUsersInterno o codigo dos usuarios que receberao o mail por correio interno
 *  Retorna em $usersExterno um array onde cada elemento eh um hash da seguinte estrutura : 
 *     $usersExterno[$codPessoa] = $mail, onde $codPessoa eh o codigo do usuario e mail eh o mail externo dele
 */
function getUsersIds($userNames,$codPessoa,&$codUsersInterno,&$usersExterno) {
  $names = explode(",",$userNames);
  
  $str = "";
  
  $codUsersInterno = array();
  $usersExterno = array();

  $instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual());

  foreach($names as $name) {
    //verifica se algum desses usernames na verdade eh um nome de grupo
    //um nome de grupo comeca por < e termina por >
    if (strpos($name,"<") !== FALSE && strpos($name,">") !== FALSE) {
      //eh um nome de grupo
      //coloca o codigo de todos os usuarios q participan no grupo
      //no array user id's
      if (trim($name) == "<meus_professores>") {
      	//eh o grupo especial dos professores
      	//se nao estiver em um nivel que relacione prof e aluno,
      	//entao mostra todos os professores dos subniveis
        
        
        if ($instanciaAtual->relacionaPessoas()) {
          $professores = listaProfessores();
        }
        else {
          $professores = listaTodosIntegrantes(new Professor());
        }
      	
      	
      	while($prof = mysql_fetch_object($professores)) {
      	  $codUsers[] = $prof->COD_PESSOA;
      	}
      }

      elseif (trim($name) == "<meus_colegas>") {
      	//eh o grupo especial dos colegas      	 
        if ($instanciaAtual->relacionaPessoas()) {
          $colegas = listaAlunos();
        }
        else {
          $colegas = listaTodosIntegrantes(new Aluno());
        }
      	
        while($colega = mysql_fetch_object($colegas)) {
      	  if ($colega->COD_PESSOA != $_SESSION["COD_PESSOA"])
      	    $codUsers[] = $colega->COD_PESSOA;
      	}
      }
      else {
      	//eh um grupo normal
      	$grupo = getGrupoUsuarios(trim($name));
      	if (!empty($grupo)) {
      	  $usuarios = getUsuariosParticipantesGrupo($grupo->codGrupo);
      	  if (!empty($usuarios->records)) {
      	    foreach($usuarios->records as $user) {
      	      if ($user->codPessoa != $codPessoa)
      		$codUsers[] = $user->codPessoa;
      	    }
      	  }
      	}
      }
    }

    else {    
      //eh um nome de usuario normal
      if (!empty($str)) $str.= " OR ";
      $str.= "USER_PESSOA=".quote_smart(trim($name));
    }
  }
  
  if (!empty($str)) {
    $sql = "SELECT COD_PESSOA,EMAIL_PESSOA,CORREIO_RECEBE_MAIL_INTERNO,CORREIO_RECEBE_MAIL_EXTERNO FROM pessoa WHERE ".$str;
    
    $usuarios = new RDCLQuery($sql);
    if (!empty($usuarios->records)) {
      foreach($usuarios->records as $user) {
	      if($user->CORREIO_RECEBE_MAIL_INTERNO) 
	      //recebe atraves do mail interno
	      $codUsersInterno[] = $user->COD_PESSOA;
	
	      if ($user->CORREIO_RECEBE_MAIL_EXTERNO) {
	        //recebe atraves do mail externo
	        $usersExterno[$user->COD_PESSOA] = $user->EMAIL_PESSOA;
	      }
      }
    }
  }
  
}

/**
 * Envia uma mensagem
 * $dests eh um array com os codigos dos destinatarios
 */
function sendMessage($remetente,$destsInterno,$destsExterno,$subject,$msg,$remetenteMailExterno,$files) {
  global $url;
  
  set_time_limit(0); //por via das dúvidas...

  $sql = "";
  if (is_array($destsInterno) && count($destsInterno) > 0) {
    //manda a mensagem para os destinatarios que usam correio_interno para receber msgs

    //inclui a mensagem em correio_msg
    $sql = "INSERT INTO correio_msg (user_from,subject,msg,data) VALUES ";
    $sql.= "(".quote_smart($remetente).",".quote_smart($subject).",".quote_smart($msg);
    $sql.= ",".time().")";
    mysql_query($sql);
    
    //obtem o codigo da mensagem recem-inserida
    $codMsg = mysql_insert_id();
    
    //agora registra os destinatarios para a mensagem 
    $sql = "INSERT INTO correio_msg_dest(codMsg,user_to) VALUES ";
    $str = "";
    foreach($destsInterno as $dest) {
      if (!empty($str)) $str.= " , ";
      $str.= "(".$codMsg.",".quote_smart($dest).")";
    }     
    
    //roda a query que salva os destinatarios 
    $sql.= $str;    
    mysql_query($sql);
  }
  if (count($files)) {
    //salva os arquivos anexos
    $numAnexos = salvaAnexos($files,$codMsg,$remetente);
  }
  if (is_array($destsExterno) && count($destsExterno) > 0) {
    $warn="";
    //trata o caso quando nao há email registrado
    if (empty($remetenteMailExterno)) { 
      $nomePessoa = new RDCLQuery("select NOME_PESSOA FROM pessoa Where COD_PESSOA=".quote_smart($remetente));
      $remetenteMailExterno=NO_REPLY_ADDRESS; 
      $warn = "Usuario ".$nomePessoa->records[0]->NOME_PESSOA. " ainda nao configurou seu email externo.";
    }
	
    //emerencial!!!
    //$remetenteMailExterno="Usuario ".$nomePessoa->records[0]->NOME_PESSOA. " enviou email pela plataforma NAVi. <navi@ea.ufrgs.br>"; 
    //manda a mensagem aos destinatarios que usam correio externo para receber msgs
    /*
    $extraHeaders = "From: <".$remetenteMailExterno.">\r\n";
    $extraHeaders.= "Reply-To: <".$remetenteMailExterno.">";
    */
    $extraHeadres ="MIME-Version: 1.0\r\n"; 
    $extraHeaders.= "From: <".NO_REPLY_ADDRESS.">\r\n"; 
    $extraHeaders.= "Reply-To: ".$remetenteMailExterno."\r\n";  
    $extraHeaders.="Content-type: text/html;  charset=iso-8859-1\r\n";
    /*$extraHeaders = "From: <".NO_REPLY_ADDRESS.">\r\n";
    $extraHeaders.= "Reply-To: <".$remetenteMailExterno.">";
    $extraHeaders.="Content-type: text/html;  charset=iso-8859-1\n\r";*/
    if ($numAnexos > 0) {
      //manda os links dos anexos    
      $msg.= "<br><br> ----- Anexos ----- <br><br>";
      
      //le os anexos
      $anexos = correioMsgGetAnexos($codMsg);
      
      if (!empty($anexos->records)) {
        $i=0;
        foreach($anexos->records as $anexo) {
          $i++;
          //adiciona o link para cada anexo
          $textoLink = "<A HREF=".$url."/interacao/correio/visualizaAnexo.php?codMsg=".$codMsg.
          "&nomeArq=".urlencode($anexo->nomeArq)."&hash=".correioGetMd5Anexo($anexo).">";
          
          $link = $url."/interacao/correio/visualizaAnexo.php?codMsg=".$codMsg.
          "&nomeArq=".urlencode($anexo->nomeArq)."&hash=".correioGetMd5Anexo($anexo);
          //$msg.= $i.") ".$anexo->nomeArq."\r\n";
         // $msg.= $link."\r\n\r\n";   
         $msg.= $textoLink.$i.") ".$anexo->nomeArq."</A><br>";
          $msg.= $link."<br><br>";   
        }
        
        $msg.= "Clique no link ou copie e cole a URL na barra de endereço de seu navegador.<br>";
      }
    }
	$nomePessoa = new RDCLQuery("select NOME_PESSOA FROM pessoa Where COD_PESSOA=".quote_smart($remetente));
    $msg.= $warn."\n";
    $msg.= "<br>-----Mensagem enviada usando o correio do NAVI por ".$nomePessoa->records[0]->NOME_PESSOA ."<br>-----email: ".$remetenteMailExterno;

	

    ini_set("SMTP",SERVIDOR_SMTP);
    $real_sender = '-f navi@ufrgs.br';
    foreach($destsExterno as $codPessoa=>$mailAddress) {
      //envia o mail externo
      //echo "mail(".$mailAddress.",".$subject.",".$msg.",".$extraHeaders.");\n<br>";
      //$sucesso = @mail("<".$mailAddress.">",$subject,$msg,$extraHeaders);
      //$sucesso = mail("<".$mailAddress.">",$subject,$msg,$extraHeaders);
      $sucesso = mail("<".$mailAddress.">",$subject,$msg,$extraHeaders, $real_sender);
      
       //SUGESTAO: Colocar aqui depois uma rotina que trabalhe o retorno em sucesso.
       //Se houver problema, marcar este usuário como problemático e enviar emails INTERNOS avisando para ele, 
       //até "n" emails, ou depois de certa data, avisando ele para arrumar seu email EXTERNO
       
      //echo "sucesso: ".$sucesso."<br>";
    }
    //exit;
  }
  
  
  if (mysql_errno())
    echo mysql_error();

}

/**
 *  Imprime a mensagem (na visualizacao da caixa postal - index.php )
 */
function printMessage(&$msg,$par,$tipoCaixa) {

  /*para distinguir entre as caixas de entrada e a de saida */
  $rowClass[1] ="rowOddTabInbox";
  $rowClass[2] ="rowOddTabInboxOut";
  
  $rowEvenClass[1] ="rowEvenTabInbox";
  $rowEvenClass[2] ="rowEvenTabInboxOut";
	
  if ($par==0) { $classeLinha = $rowEvenClass[$tipoCaixa]; } else {$classeLinha = $rowClass[$tipoCaixa]; }

  if ($msg->lida || $_REQUEST['tipoCaixa'] == MAIL_OUTBOX) {
    $classeCSS = "colTabInbox";
    $classeCSSLink = "linkLida";
    $imagemMsg = "lida.gif";
  }
  else {
    $classeCSS = "colTabInboxNaoLida";
    $classeCSSLink = "linkNaoLida";
    $imagemMsg = "naoLida.gif";
  }
  echo "<tr class=\"{$classeLinha}\"><td class=\"".$classeCSS."\" width=\"5%\"><input type=\"checkbox\" name=\"msg_".$msg->codMsg."\" id=\"msg_".$msg->codMsg."\" value=\"1\"></td>";
  $link ="<a href=\"".$_SERVER["PHP_SELF"]."?acao=A_le_msg&tipoCaixa=".$tipoCaixa."&codMsg=".$msg->codMsg."\" class=\"".$classeCSSLink."\">";

  if ($tipoCaixa == MAIL_INBOX) {
    //no inbox, exibe o nome de quem enviou a msg juntamente com uma imagem
    //q diz se a msg foi lida/nao lida
    echo "<td class=\"".$classeCSS."\">".$link."<img src=\"./imagens/{$imagemMsg}\" border=\"0\">&nbsp;&nbsp;".$msg->pessoa."</a></td>";
  }
  else {
    //no outbox, exibe os nomes para quem enviou a msg ( na verdade um resumo dos nomes) 
    //nao coloca nenhum icone indicando lida/naolida

    //le os destinatarios
    $dests = getMsgDests($msg->codMsg);
    $numDests = count($dests->records);

    //isso aqui serve para apenas colocar o nome dos destinatarios
    //segundo um limite de caracteres q pode estar presente
    $nomesDests = "";
    $i = 0;
    while(strlen($nomesDests) < MAIL_LIST_OUTBOX_DEST_MAX_CHARS && $i < $numDests) {
      if (!empty($nomesDests)) $nomesDests.= ", ";
      $nomesDests.= $dests->records[$i]->pessoa;
      $i++;
    }
    
    if ($i < $numDests)
      $nomesDests.= "...";

    echo "<td class=\"".$classeCSS."\">".$link.$nomesDests."</a></td>";    
  }

  echo "<td class=\"".$classeCSS."\">".$link.$msg->subject."</a></td>";
  echo "<td class=\"".$classeCSS." dataMensagem\" width=\"20%\">".$link.date("d/m/Y H:i",$msg->data)."</a></td>";
  echo "</tr>";
}

/**
 * Imprime uma lista com todos os destinatarios da msg
 */ 
function imprimeDestinatariosMsg(&$msg) {
  //le os destinatarios da mensagem
  $dests = getMsgDests($msg->codMsg);  
  
//  if (count($dests->records) > MAIL_LIMITE_MOSTRA_DESTS_INLINE) {
//  
//  }
//  else {
    $str = '';
    foreach($dests->records as $dest) {
      if (!empty($str))
        $str.= ', ';
      $str.= $dest->pessoa; 
    }
//  }
  return $str;
}

/**
 * Exibe a mensagem inteira ( index.php?acao=A_le_msg )
 */
function showMessage(&$msg,$tipoCaixa) {
  if (empty($msg)) { return '';} //hack de segurança  
  
	$tipoMsg[1]="Responder";
	$tipoMsg[2]="Encaminhar";
  	
  echo "<table class=\"tabShowMessage\">";
  echo "<tr><td class=\"colShowMessageLabel\">De:</td><td class=\"colShowMessage\"> <a href=\"../../consultar.php?&BUSCA_PESSOA=".$msg->pessoa."\" target=\"_blank\">".$msg->pessoa." </td>";
  echo "<tr><td class=\"colShowMessageLabel\">Para:</td>";
  echo "<td class=\"colShowMessage\">".imprimeDestinatariosMsg($msg)."</td>";
  echo "<tr><td class=\"colShowMessageLabel\">Assunto: </td><td class=\"colShowMessage\">".$msg->subject."</td>";
  echo "</table>";

  //por eqto tira todas as tags, colocar funcao q retira apenas as perigosas!
  //echo "<pre>"
  echo "<div class=\"contentMessage\">";
 // echo nl2br(strip_tags($msg->msg));
 echo nl2br($msg->msg);
  echo "</div>";
  //echo "</pre>";

  //imprime os anexos, se houver
  $anexos = correioMsgGetAnexos($msg->codMsg);
  if (!empty($anexos->records)) {
    echo "<div class=\"anexosDiv\"><span class=\"anexosDivHeader\">Anexos: </span><br>";
    echo '<ul>';
    foreach($anexos->records as $anexo) {
      echo "<li><a href=\"".$_SERVER["PHP_SELF"]."?acao=A_le_anexo&codMsg=".$anexo->codMsg."&nomeArq=".urlencode($anexo->nomeArq)."\" class=\"anexosDivLink\" target=\"_blank\">".$anexo->nomeArq."</a></li>";
    }
    echo '</ul>';
    echo "</div>";
  }


  $jsVoltar = "window.location.href = '".$_SERVER["PHP_SELF"]."?tipoCaixa=".$tipoCaixa."';\n";
  //responder está como link normal
  //$jsResponder = "   window.location.href = '".$_SERVER["PHP_SELF"]."?acao=A_compoe_msg&msgReply=".$msg->codMsg."';";
  $linkResponder = $_SERVER["PHP_SELF"]."?tipoCaixa=".$tipoCaixa."&acao=A_compoe_msg&msgReply=".$msg->codMsg;
  //excluir pergunta antes ao usuario, para confirmar
  $jsExcluir = "if (confirm('Voce realmente deseja excluir esta mensagem?')) { ";
  $jsExcluir.= " window.location.href = '".$_SERVER["PHP_SELF"]."?acao=A_delete&tipoCaixa=".$tipoCaixa."&codMsg[]=".$msg->codMsg."';}\n";

  echo "<table class=\"operacoesVisualizacaoMensagem\"><tr>";
  //echo "<td><input type=\"image\" value=\"Responder\" onclick=\"".$jsResponder."\" src=\"./imagens/responderEmail.gif\"></td>";
  echo "<td class=\"colunaOperVisMsg\"><a href=\"#\" onclick=\"".$jsVoltar."\"><img src=\"./imagens/voltar.jpg\" border=\"no\"><br>Voltar para caixa de mensagens</a></td>";
  echo "<td class=\"colunaOperVisMsg\"><a href=\"".$linkResponder."\" ><img src=\"./imagens/responderEmail.jpg\" border=\"no\"><br>{$tipoMsg[$tipoCaixa]}</a></td>";
  
  if ($tipoCaixa == MAIL_INBOX) {
    $linkResponderTodos = $_SERVER["PHP_SELF"]."?tipoCaixa=".$tipoCaixa."&acao=A_compoe_msg&msgReply=".$msg->codMsg."&replyToAll=1";      
    echo "<td class=\"colunaOperVisMsg\"><a href=\"".$linkResponderTodos."\" ><img src=\"./imagens/responderEmail.jpg\" border=\"no\"><br>Responder a todos</a></td>";    
  }

  echo "<td class=\"colunaOperVisMsg\"><a href=\"#\" onclick=\"".$jsExcluir."\"><img src=\"./imagens/excluirEmail.jpg\" border=\"no\"><br>Excluir</a></td>";
  echo "</tr></table>";
}

/**
 * Envia o anexo para o browser
 * O terceiro parametro opcional eh o q retorna da chamada de funcao correioMsgGetAnexos
 * Isto server para, se por algum motivo, como acontece em visualizaAnexo.php, o 
 * anexo ja tiver sido lido nao fazer a query novamente   
 */ 
function enviaAnexoBrowser($codMsg,$nomeArq,$anexoRes="") {
  global $caminhoUpload;
  global $caminhoUpload1;
  
  $codRemetente = getCodRemetenteMsg($codMsg);

  if (empty($anexoRes))
    $anexoRes = correioMsgGetAnexos($codMsg,$nomeArq);

  $path = $caminhoUpload."/anexosCorreio/".$codRemetente."/".$codMsg."/".$nomeArq;
  if (!file_exists($path)) {
    $path = $caminhoUpload1."/anexosCorreio/".$codRemetente."/".$codMsg."/".$nomeArq;
  }

  if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE")){
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Description: File Transfer");	
    header("Content-Disposition: inline; filename=".$nomeArq);
    header("Content-Transfer-Encoding: binary");
  }
  else
    header("Content-Disposition: attachment; filename=".$nomeArq);
  header("Content-type: ".$anexoRes->records[0]->mimeType);  
  header("Content-length: ".filesize($path));

/*note($path);
  note($anexoRes);
  note($anexoRes->records[0]->mimeType);
  note(filesize($path));*/

  readfile($path);  
}

/**
 * Deleta as mensagens
 */
function deleteMessages($codUser,$codMsgs,$tipoCaixa) {

  $str = "";
  if (is_array($codMsgs) && count($codMsgs) > 0) {
    //forma um OR com os codigos das mensagens
    foreach($codMsgs as $cod) {
      if (!empty($str)) $str.= " OR ";
      $str.= " codMsg=".quote_smart($cod);
    }
  }
  
  if ($tipoCaixa == 1) {
    //caixa entrada
    
    //marca a msg como excluida
  	if (!empty($str)) {
      
      $sql = "UPDATE correio_msg_dest SET excluida=1 ";
      $sql.= "WHERE user_to=".quote_smart($codUser)." AND (".$str.") ";
            
  	  mysql_query($sql);
  	  //echo $sql;
      echo mysql_error();
    }    
  }
  elseif ($tipoCaixa == 2) {
    //caixa saida
    
    //marca a msg como excluida
    if (!empty($str)) {
      $sql = "UPDATE correio_msg SET caixaSaidaExcluida=1 WHERE ".
             "user_from=".quote_smart($codUser)." AND (".$str.")";
  	  mysql_query($sql);
  	  //echo $sql;
      echo mysql_error();             
    }
  }
  
  //verifica se para cada mensagem, ela ja foi excluida por todos os destinatarios
  //e na caixa de saida
  //se sim, entao pode excluir os registros propriamente ditos
  if (is_array($codMsgs) && count($codMsgs) > 0) {
    foreach($codMsgs as $cod) {
      if (caixaSaidaExcluida($cod) && todosDestExcluiramMsg($cod)) {
        deletaMsg($cod);
      }
    }    
  }
}

/**
 * Verifica se determinada mensagem foi marcada como excluida da caixa de saida
 */ 
function caixaSaidaExcluida($codMsg) {
  $sql = "SELECT caixaSaidaExcluida FROM correio_msg WHERE codMsg=".quote_smart($codMsg);
  $result = new RDCLQuery($sql);
  return $result->records[0]->caixaSaidaExcluida;
}

/**
 * Verifica se todos os destinatarios marcaram a mensagem como excluida
 */ 
function todosDestExcluiramMsg($codMsg) {
  $sql = "SELECT COUNT(*) as numNaoExcluiram FROM correio_msg_dest ".
         "WHERE codMsg=".quote_smart($codMsg)." AND (excluida IS NULL or excluida=0)";
  $result = new RDCLQuery($sql);
  return ($result->records[0]->numNaoExcluiram == 0);         
}

/**
 * Deleta uma mensagem
 * 1) Deleta todos os registros em correio_msg_dest
 * 2) Deleta o registro q contem a msg propriamente dita em correio_msg  
 */ 
function deletaMsg($codMsg) {
  //deleta os anexos
  correioDeletaAnexos($codMsg);

  //deleta os destinatarios
  $sql = "DELETE FROM correio_msg_dest WHERE codMsg=".quote_smart($codMsg);
  mysql_query($sql);
  
  //deleta a msg
  $sql = "DELETE FROM correio_msg WHERE codMsg=".quote_smart($codMsg);
  mysql_query($sql);
      
}

//calcula a mensagem que deve ser retonada dado um determinado numero de pagina
function calcCorreioIniMsg($pagina,$msgsPerPage) {
  return ($pagina-1)*$msgsPerPage; 
}

function imprimePaginacaoCorreio($numMsgs,$pagAtual,$numMsgsPerPage,$tipoCaixa) {
  if ($numMsgs > $numMsgsPerPage) {
    $numPaginas = ceil($numMsgs / $numMsgsPerPage);
    
    echo "<div id=\"paginacao\" class=\"paginacao\">P&aacute;gina&nbsp;";
    for($i=1; $i<=$numPaginas; $i++) {
      if ($paginaAtual == $i)
	echo "<a href=\"{$_SERVER["PHP_SELF"]}?tipoCaixa=".$tipoCaixa."&numFolderPage={$i}\" style=\"font-size: 14px; font-weight: bold\">{$i}</a>&nbsp;";
      else
	echo "<a href=\"{$_SERVER["PHP_SELF"]}?tipoCaixa=".$tipoCaixa."&numFolderPage={$i}\" style=\"font-size: 14px\">{$i}</a>&nbsp;";
    }
    echo "</div>";
  }
}

/** Checa se um determinado nome eh de um grupo
    Retorna 0 se nao for
    Se for retorna o codigo do grupo
**/
function getGrupoUsuarios($nome) {
  $sql = "SELECT codGrupo FROM correio_grupo WHERE nome=".quote_smart($nome);
  $grupo = new RDCLQuery($sql);
  return $grupo->records[0];
}

/**
 *  Retorna os usuarios participantes de um determinado grupo
 */
function getUsuariosParticipantesGrupo($codGrupo) {
  $sql = "SELECT codGrupo,codPessoa FROM correio_grupo_usuarios WHERE codGrupo=".quote_smart($codGrupo);
  return new RDCLQuery($sql);
}


/**
 *  Adiciona um usuario a um grupo de usuarios
 */
function adicionaUserGrupoUsuarios() {

}

/**
 * Deleta um usuario de um grupo de usuarios
 */
function deletaUserGrupoUsuarios() {
  
}

/**
 * Lista os contatos de um usuario
 */
function getContatos($codPessoa) {


  $sql = "SELECT contato.codPessoaContato,pessoa.USER_PESSOA,pessoa.NOME_PESSOA FROM correio_address_book contato INNER JOIN pessoa ON (contato.codPessoaContato=pessoa.COD_PESSOA) ";
  $sql.= " WHERE codPessoa=".quote_smart($codPessoa);
  $sql.= " ORDER BY NOME_PESSOA";
  return new RDCLQuery($sql);
}

/**
 * Adiciona o contato
 */
function adicionaContato($codPessoa,$codPessoaContato) {
  $sql = "INSERT INTO correio_address_book (codPessoa,codPessoaContato) VALUES ";
  $sql.= "(".quote_smart($codPessoa).",".quote_smart($codPessoaContato).") ";
  mysql_query($sql);
}

/**
 * Deleta o contato
 */
function deletaContato($codPessoa,$codPessoaContato) {
  $sql = "DELETE FROM correio_address_book WHERE codPessoa=".quote_smart($codPessoa);
  $sql.= " AND codPessoaContato=".quote_smart($codPessoaContato);
  mysql_query($sql);
}

/**
 * Deleta contatos, onde uma lista de pessoas a serem deletas eh passada
 */
function deletaContatosListaPessoas($codPessoa,$listaPessoasContato) {
  $sql = "DELETE FROM correio_address_book WHERE codPessoa=".quote_smart($codPessoa);
  
  $str = "";
  foreach($listaPessoasContato as $codPessoa) {
    if (!empty($str)) $str.= " OR ";
    $str.= " codPessoaContato=".quote_smart($codPessoa);
  }
  
  $sql.= " AND (".$str.") ";
  return mysql_query($sql);
}

/**
 *  Imprime meus contatos ( Meus contatos eh um grupo de usuarios especial de cada usuario )
 */
function imprimeMeusContatos($codPessoa) {
   global $url;
  //obtem os contatos da pessoa
  $contatos = getContatos($codPessoa);
  
  //adiciona o usuario a lista de destinatarios
  $js = "function addUser(nomUser) {\n";
  $js.= "   if (document.form1.to.value != '') document.form1.to.value+= ', ';\n";
  $js.= "   document.form1.to.value+= nomUser;\n";
  $js.= "}\n";

  //seleciona todos da lista
  $js.= "function selTodos(tipo) {\n";
  $js.= "  for(i=0; i< document.formMeusContatos.elements.length; i++) {\n";
  $js.= "    if (document.formMeusContatos.elements[i].name.indexOf(tipo) != -1)\n";
  $js.= "      document.formMeusContatos.elements[i].checked = true;\n";
  $js.= "  }\n";
  $js.= "}\n";

  //des-seleciona todos da lista
  $js.= "function selNenhum(tipo) {\n";
  $js.= "  for(i=0; i< document.formMeusContatos.elements.length; i++) {\n";
  $js.= "    if (document.formMeusContatos.elements[i].name.indexOf(tipo) != -1)\n";
  $js.= "      document.formMeusContatos.elements[i].checked = false;\n";
  $js.= "  }\n";
  $js.= "}\n";
  
  //adiciona os selecionados no checkbox para a lista de destinatarios
  $js.= "function adicionaDestSelecionados() {\n";
  $js.= "  for(i=0; i< document.formMeusContatos.elements.length; i++) {\n";
  $js.= "    if (document.formMeusContatos.elements[i].checked)";
  $js.= "      addUser(document.formMeusContatos.elements[i].value);\n";
  $js.= "  }\n";
  $js.= "}\n";

  //apaga os selecionados da lista de contatos
  $js.= "function apagarSelecionados() {\n";
  $js.= "if (confirm('Voce tem certeza que deseja apagar esses contatos?')) {\n";
  
  //forma um array com os codigos dos contatos
  $str = "";
  if (!empty($contatos->records)) {
    foreach($contatos->records as $contato) {
      if (!empty($str)) $str.= ",";
      $str.= $contato->codPessoaContato;
    }
  }
  $js.= "  cods = new Array(".$str.");\n";
  
  $js.= "  url = '".$_SERVER["PHP_SELF"]."?acao=A_deleta_contato&to='+document.form1.to.value;\n";
  $js.= "  url+= '&subject='+document.form1.subject.value + '&message='+document.form1.message.value;\n";
  $js.= "  algumChecked = false;\n";
  
  //agora checa se esses contatos estao selecionados e coloca seus codigos na url
  $js.= "  for (i=0; i < cods.length; i++) {\n";
  $js.= "     if (document.formMeusContatos['contato_outro_' + cods[i]].checked) {\n";
  $js.= "       algumChecked = true;\n";
  $js.= "       url+= '&codPessoasContato[]=' + cods[i];\n";
  $js.= "     }\n";
  $js.= "  }\n";
  $js.= "  if (algumChecked)\n";
  $js.= "    window.location.href = url;\n";
  $js.= "}\n";
  $js.= "}\n";

  //adiciona professores
  $js.= "function adicionaProfessores() {\n";
  $js.= "   addUser('<meus_professores>');\n";
  $js.= "}\n";
  
  //adiciona colegas
  $js.= "function adicionaColegas() {\n";
  $js.= "   addUser('<meus_colegas>');\n";
  $js.= "}\n";
  
  echo "<script language=\"JavaScript\" type=\"text/javascript\">\n".$js."</script>";
  //adiciona divs.js
  echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../../js/divs.js\"></script>";
  
  echo "<div id=\"divMeusContatos\">";
  echo "<div class=\"headerMeusContatos\">Meus Contatos</div>";

  
  echo "<form name=\"formMeusContatos\">";

  echo "<span><a href=\"#\" onclick=\"adicionaDestSelecionados()\">Adicionar selecionados aos destinat&aacute;rios</a></span><br><br>";
  echo "<hr>";
  
  if (empty($_SESSION["COD_PROF"])) {
    $tituloProf = "Meus Professores";
    $tituloAlunos = "Meus Colegas";
  }
  else {
    //se o cara eh professor entao muda o titulo dos grupos de contatos ( professores e colegas )
    $tituloProf = "Meus Colegas";
    $tituloAlunos = "Meus Alunos";
  }
  
  //mostra os professores 
  echo "<div id=\"divMeusProfessores\" class=\"divInternaMeusContatos\"><a href=\"#\" onclick=\"toggle('tabMeusContatosProf')\">+/-</a> ";
  echo $tituloProf."</a> <a href=\"#\" onclick=\"selTodos('prof')\">Todos</a>  | <a href=\"#\" onclick=\"selNenhum('prof')\">Nenhum</a>";
  
  //lista os professores
	//se nao estiver em um nivel que relacione prof e aluno,
	//entao mostra todos os professores dos subniveis
  
  $instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual());

  if ($instanciaAtual->relacionaPessoas()) {
    $profResult = listaProfessores();
  }
  else {
    $profResult = listaTodosIntegrantes(new Professor());
  }

  if (mysql_num_rows($profResult) > 0) {
    
    echo "<table id=\"tabMeusContatosProf\" class=\"tabMeusContatos\">";
    
    while($prof = mysql_fetch_object($profResult)) {
      if ($prof->COD_PESSOA != $_SESSION["COD_PESSOA"]) {
	      echo "<tr><td><input type=\"checkbox\" name=\"contato_prof_".$prof->COD_PESSOA."\" id=\"contato_prof_".$prof->COD_PESSOA."\" value=\"".$prof->USER_PESSOA."\"></td>";
	      echo "<td class=\"contato\"><a href=\"javascript: addUser('".$prof->USER_PESSOA."')\">".$prof->NOME_PESSOA." (".$prof->USER_PESSOA.")</a></td></tr>";
      }
    }    
    echo "</table>";    
  }
  echo "</div>";
  //fim mostra professores
  
  echo "<hr>";

  //mostra os colegas
  echo "<div id=\"divMeusColegas\" class=\"divInternaMeusContatos\"><a href=\"#\" onclick=\"toggle('tabMeusContatosColegas')\">+/-</a> ";
  echo $tituloAlunos."</a> <a href=\"#\" onclick=\"selTodos('aluno')\">Todos</a>  | <a href=\"#\" onclick=\"selNenhum('aluno')\">Nenhum</a>";
    
  if ($instanciaAtual->relacionaPessoas()) {
	  $alunosResult = listaAlunos();
  }
  else {
    $alunosResult = listaTodosIntegrantes(new Aluno());
  }
  
  if (mysql_num_rows($alunosResult) > 0) {
    
    echo "<table id=\"tabMeusContatosColegas\" class=\"tabMeusContatos\">";
    
    while($aluno = mysql_fetch_object($alunosResult)) {

      if ($_SESSION["COD_PESSOA"] != $aluno->COD_PESSOA) {
        echo "<tr><td><input type=\"checkbox\" name=\"contato_aluno".$aluno->COD_PESSOA."\" id=\"contato_aluno_".$aluno->COD_PESSOA."\" value=\"".$aluno->USER_PESSOA."\"></td>";
        echo "<td class=\"contato\"><a href=\"javascript: addUser('".$aluno->USER_PESSOA."')\">".$aluno->NOME_PESSOA." (".$aluno->USER_PESSOA.")</a></td></tr>";
      }
    }
    
    echo "</table>";
  }
  echo "</div>";
  //fim mostra colegas

  echo "<hr>";
  
  //agora lista outros contatos se hoverem
  echo "<div id=\"divOutrosContatos\" class=\"divInternaMeusContatos\"><a href=\"#\" onclick=\"toggle('tabMeusContatosOutros')\">+/-</a> Outros contatos</a> <a href=\"#\" onclick=\"selTodos('outro')\">Todos</a>  | <a href=\"#\" onclick=\"selNenhum('outro')\">Nenhum</a>";
  
  echo "<table id=\"tabMeusContatosOutros\" style=\"display: none\">";
  
  if (!empty($contatos->records)) {
    foreach($contatos->records as $contato) {
      echo "<tr><td><input type=\"checkbox\" name=\"contato_outro_".$contato->codPessoaContato."\" id=\"contato_outro_".$contato->codPessoaContato."\" value=\"".$contato->USER_PESSOA."\"></td>";
      echo "<td class=\"contato\"><a href=\"javascript: addUser('".$contato->USER_PESSOA."')\">".$contato->NOME_PESSOA." (".$contato->USER_PESSOA.")</a></td></tr>";
    }
  }
  
  //echo "<tr><td colspan=\"2\"><a href=\"#\" onclick=\"javascript: newwin = window.open('".$_SERVER["PHP_SELF"]."?acao=A_procura_user','Procura Usuario','width=420,height=350'); newwin.opener = self; newwin.focus();\">Incluir contato</a> | ";
  echo "<tr><td colspan=\"2\"><a href=\"{$url}/interacao/correio/index.php?acao=A_procura_user\" target=\"_blank\">Incluir contato</a> | ";
  echo "<a href=\"javascript: apagarSelecionados()\">Apagar contato</td></tr>";
  echo "</table>";
  
  echo "</div>";
  
  //fim lista outros contatos
  
  echo "</form>";
  echo "<span>Clique no nome do contato para adicion&aacute;-lo aos destinat&aacute;rios da mensagem</span>";
  
  echo "</div>";  
}

/**
 *  Retorna todas as pessoas do ambiente
 */
function getPessoas() {
  $sql = "SELECT COD_PESSOA,NOME_PESSOA,USER_PESSOA FROM pessoa ORDER BY NOME_PESSOA";
  return new RDCLQuery($sql);
}

/**
 * Procura pessoas
 */
function procuraPessoas($nome) {
  
  $lnome = quote_smart($nome."%");
  $sql = "SELECT COUNT(*) as numRows FROM pessoa where USER_PESSOA LIKE ".$lnome." OR NOME_PESSOA LIKE ".$lnome;
  $result = new RDCLQuery($sql);
  if ($result->records[0]->numRows == 0) {
    return 0; // nenhum usuario encontrado
  }
  elseif ($result->records[0]->numRows <= 40) {
    $sql = "SELECT COD_PESSOA,USER_PESSOA,NOME_PESSOA FROM pessoa where USER_PESSOA LIKE ".$lnome." OR NOME_PESSOA LIKE ".$lnome;
    $sql.= " ORDER BY NOME_PESSOA ";
    return new RDCLQuery($sql);
  }
  else
    return -1; //encontrou muitos registros, deve refinar a pesquisa
}

?>
