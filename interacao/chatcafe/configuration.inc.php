<?
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
function getMessageTableName($codSala) {
  $messageTableName='chatcafe_mensagem';
  
  //Verifica se esta se usando sessao para otimizacao
  if (!isset($_SESSION['useSelfTable'])) {
    if (isSelfTable($codSala)) { $messageTableName.='_'.$codSala; }
  }
  else if ($_SESSION['useSelfTable']) {
    $messageTableName.='_'.$codSala;
  }
  
  return $messageTableName;
}

function isSelfTable($codSala) {
  $sql = "select useSelfTable from turma where COD_TURMA=".$codSala;
  
  $result = mysql_query($sql);
  
  $linha = mysql_fetch_assoc($result);
  
  return $linha['useSelfTable'];
}

/**
 * Le o arquivo ini dos icones e retorna um array com a traducao entre a tag img 
 *  e os caracteres
 */
function iconesChatCafe() {
  $ini = parse_ini_file("emoticons/emoticons.ini",1);
  
  //array contendo a traducao ( nem todas imagens precisam de traducao)
  $traducao = array();
  foreach($ini["Emoticons"] as $img=>$trad) {
    $traducao[addSlashes($trad)] = "<img src=emoticons/".$img." class=emoticon>";
  } 
  
  return $traducao;
}

/*
 * Exibe para o usuário os ícones disponíveis e sua tradução, para que ele possa tanto clicar quanto usar o texto diretamente
 * Adaptada da funcao do forum
 */  
function exibeIconesChatCafe($elementoForm) {
  
  $traducao = iconesChatCafe();
  
  if (count($traducao) <= 0) { return 0; } 
      
  $index = 1;

  echo "<table id=\"tabelaEmoticons\" cellspacing='2'>";
  echo "<tr id=\"linhaTabelaEmoticons\">";    
  
  foreach($traducao as $texto=>$img) {
    if ($index > 10) {
      echo "</tr><tr id=\"linhaTabelaEmoticons\">";
      $index = 1;
    }
    //o str_replace server para substituir < e > por suas entidade html, para nao confundir o browser
    $js = $elementoForm." += '".addSlashes($texto)."';";      
    echo "<td id=\"colunaTabelaEmoticons\"><a href=\"#\" onclick=\"".$js."\" title='Clique para colocar o icone na mensagem'>".$img."<br>".$texto."</a></td>";
    $index++;
  }

  echo "</tr>";
  echo "</table>";
}


?>
