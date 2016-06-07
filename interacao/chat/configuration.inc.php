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

//ini_set("display_errors",1);
//error_reporting(E_ALL ^ E_NOTICE);
//$url = "http://localhost";

$talk_system_id = 'NAVi';
$talk_cmd_id    = 'COMMAND';
$talk_null_room = 'OUTER_LANDS';

$talk_time_for_sleep      = 5;
$talk_valid_chars         = "/^[a-zA-Z_1-9\/áéíóúãõâêôÁÉÍÓÚÃÕÂÊÔçÇ. ]+$/";

define("TALK_COMMAND_PREFIX", "/");        // character to prefix commands with
define("TALK_CMD_PRIVMSG",    "MSG");      // command to send private messages
define("TALK_CMD_NOTICE",     "NOTICE");   // command to send notices
define("TALK_CMD_QUIT",       "QUIT");     // command to stop the chat
define("TALK_CMD_ACTION",     "ME");       // command for actions
define("TALK_CMD_NICK",       "NICK");     // command to change our nick
define("TALK_CMD_WHOIS",      "WHOIS");    // command to retrieve whois info
define("TALK_CMD_KICK",       "KICK");	   // command to kick someone
define("TALK_CMD_MODE",       "MODE");	   // command to change channel modes
define("TALK_CMD_LEAVE",      "LEAVE");	    
define("TALK_CMD_JOIN",       "JOIN");
define("TALK_CMD_PASS",       "PASS");

define("TALK_VAL_PRIVMSG", "10");   // command to send private messages
define("TALK_VAL_NOTICE",  "11");   // command to send notices
define("TALK_VAL_QUIT",    "12");   // command to stop the chat
define("TALK_VAL_ACTION",  "13");   // command for actions
define("TALK_VAL_NICK",    "14");   // command to change our nick
define("TALK_VAL_WHOIS",   "15");   // command to retrieve whois info
define("TALK_VAL_KICK",    "16");   // command to kick someone
define("TALK_VAL_MODE",    "17");	// command to change channel modes
define("TALK_VAL_LEAVE",   "18");	    
define("TALK_VAL_JOIN",    "19");
define("TALK_VAL_PASS",    "20");

define("STR_MSG_RESERVED",      " fala reservadamente para ");
define("STR_MSG_TALK",          "");
define("STR_MSG_TO",            " para ");
define("STR_MSG_ENTERING_ROOM", "Entrando na sala");
define("STR_MSG_NICK",          "Seu nome agora é ");
define("STR_MSG_INVALID_CHARS", "Você digitou caracteres inválidos");
define("STR_MSG_KNOWN_AS",      " agora será chamado de ");


//Retorna o nome correto da tabela de mensagens
//Estas funcoes estao aqui pois este arquivo é incluido pelos indicadores e acervo, que tambem a usam

//por hora vamos obrigar a utilizar tabela própria no chat
function getMessageTableName($codSala) {
  
  $messageTableName='chat_mensagem';
  
  //Verifica se esta se usando sessao para otimizacao
  if (!isset($_SESSION['useSelfTable'])) {
    if (isSelfTable($codSala)) { $messageTableName.='_'.$codSala; }
  }
  else if ($_SESSION['useSelfTable']) {
    $messageTableName.='_'.$codSala;
  }
  return $messageTableName;

  //return 'chat_mensagem_'.$codSala;
}

function isSelfTable($codSala) {
  
  $sql = "select useSelfTable from chatconf where codInstanciaGlobal=". $codSala;
  $result = mysql_query($sql);
  if (mysql_num_rows($result)) {
    $linha = mysql_fetch_assoc($result);
    return $linha['useSelfTable'];
  }
  else {   
   return SEGMENTAR_TABELAS_CHAT;
  }
}

?>