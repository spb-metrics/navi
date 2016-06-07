<?
ini_set("display_errors",1);
error_reporting(E_ALL);
//include("configuration.inc.php");
include("bd_funcoes.php");
session_start();
//le as variaveis de sessao q ira usar e ja encerra a sessao, para nao bloquear os outros frames 
session_write_close();
$sRoomID = (int)$_SESSION["ROOM_ID"];
$sUserID = (string)$_SESSION["USER_ID"];
$codPessoa = (int)$_SESSION["COD_PESSOA"];
$nomeChat = (string)$_SESSION["NOME_CHAT"];

function talk_parse_command_message($cmd_line) {
  $cmd = explode(" ", $cmd_line);
	
  if (! isset($cmd[2])) 
		$cmd[2] = "";
  if( substr($cmd[0],0,1) != TALK_COMMAND_PREFIX) 
		die("Not a command line."); 

  switch (strtoupper(substr($cmd[0],1))) {
    case TALK_CMD_QUIT : talk_cmd_quit("12");break;
    default            : echo "<script>alert('Unknown cmd message received.')</script>";
  }
}
/*
function  talk_cmd_quit($nada) 
{

  global $talk_system_id,$sRoomID,$sUserID;
  talk_db_message_insert($sRoomID, $talk_system_id, "#".$sRoomID, false, $sUserID." sai da sala.");
  talk_db_access_update($sUserID);
   echo mysql_error();
  //echo ("<p>You was disconnected from chat.</p>");

//	echo (" <Script> parent.parent.location.href = './index.asp'; </Script> ");
//    global $talk_system_id;
//    talk_db_message_insert($_SESSION["ROOM_ID"], $GLOBALS["talk_system_id"], "#".$_SESSION["ROOM_ID"], false, $_SESSION["USER_ID"]." sai da sala.");
//    talk_db_access_update($_SESSION["USER_ID"]);
//    echo ("<p>You was disconnected from chat.</p>");
*/
function  talk_cmd_quit($nada) {
  global $talk_system_id,$sRoomID,$sUserID,$codPessoa,$nomeChat;

  talk_db_message_insert($codPessoa,$nomeChat,$sRoomID, $talk_system_id, "#".$sRoomID, false, $sUserID." sai da sala.");
  talk_db_access_update($codPessoa,$sRoomID);

  echo "<Script> parent.parent.location.href = './index.php'; </Script>";
  exit();		
}

/*
 * Guarda msg no bd
 */
if (isset($_REQUEST["MESSAGE"]) && !empty($_REQUEST["MESSAGE"]) ) {
  /*
  if(!talk_is_online($codPessoa))   {
    sleep($talk_time_for_sleep + 1); // para atualizar informações
    if(! talk_is_online($codPessoa)) 
      talk_cmd_quit("23"); //die("You are disconnected.");    // definitivamente desconectado
  }
    */  
  if ( substr($_REQUEST["MESSAGE"], 0, 1) == TALK_COMMAND_PREFIX) {
    talk_parse_command_message($_REQUEST["MESSAGE"]);
  }
  else {
  
    $SEND_DESTINATION = $_REQUEST["SEND_DESTINATION"];
    //se o destino nao for especificado, entao a mensagem é para todos
    if(!isset($SEND_DESTINATION)) {
      $SEND_DESTINATION = "#" . $sRoomId;
    }
    //mensagem enviada a todos da sala
    if($SEND_DESTINATION == "#".$sRoomID) {
      talk_db_message_insert($codPessoa,$nomeChat,$sRoomID, $sUserID, "#" . $sRoomID, false, $_REQUEST["MESSAGE"]);
    }
    else {  //mensagem para certo participante e somente as pessoas envolvidas veem
      if(!isset($_REQUEST["RESERVED"]) )  {
        talk_db_message_insert($codPessoa,$nomeChat,$sRoomID, $sUserID, $SEND_DESTINATION, false, $_REQUEST["MESSAGE"]);
      }
      else {  //mensagem para certo participante e todas as pessoas da sala veem  
        talk_db_message_insert($codPessoa,$nomeChat,$sRoomID, $sUserID, $SEND_DESTINATION, true, $_REQUEST["MESSAGE"]);
      }
    }
  }
}
?>
<html>
	<head>
		<title>Escrita das Mensagens</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel='stylesheet' href='./../../cursos.css' type='text/css'>
		<link rel="stylesheet" href="./chat.css" type="text/css">
		<style type="text/css">
        BODY { font-size:10px; color:#000000; background-color:#FEFEEF; }
				.box { width: 264px }
				INPUT { border:none;}
		</style>
		<Script language="JavaScript">

    function errorsuppressor(){
      return true;
    }
    window.onerror=errorsuppressor;

    function MarcarReservado() {	
      var seleciona = null;
      seleciona = document.getElementById('SEND_DESTINATION');

      if (seleciona!=null  & typeof(seleciona)!='undeined') {  
        if (seleciona.selectedIndex != 0) {
          form1.RESERVED.checked = true;
        }
        else {
          form1.RESERVED.checked = false;
        }
      }
    }
    function submit_exit() {
      document.form1.MESSAGE.value = "/quit";
      document.form1.submit();
    }
    function sendMessageByKey(teclaPres) {
      if (teclaPres.shiftKey) {  return true;  }
      if (teclaPres.keyCode == 13){  document.form1.submit(); }
      return true;  
    }
    /*
    function reloadFrameHidden() {
       alert('aa');
       //window.parent.frames[2].location.href='./B.php';
       return true;
    } */
    </script>
  </head>

<body >

	<!-- <form name="form1" method="post" action="./C.php" onSubmit='javascript:reloadFrameHidden();'> -->
	<form name="form1" method="post" action="./C.php" >
  <table width="100%" align="center" border="0" class="margin" cellspacing='1'  cellpadding='1'>
	 <tr valign='top'>
	   <td width='61%'>
		 <span title='<?=$sUserID;?>'>Voc&ecirc;</span>  fala com: <input type="checkbox" name="RESERVED" value="yes">Reservado       
     <input type='button' name='reload' value='Atualizar Nomes' onClick="javascript:location.href='./C.php';">  
		 <br>
        <select name='SEND_DESTINATION' id='SEND_DESTINATION' class="box" onChange="MarcarReservado()">
			  <option value='#<?=$sRoomID?>' selected>Todos</option>
				  <?
          //atualiza os nomes
          //refreshOnLinePeople($sRoomID); 
          //Busca os nomes on-line sem duplicaoes
					$result = mysql_query("SELECT DISTINCT AC.NOME FROM ".getAccessTableName($sRoomID)." AC WHERE AC.DATA_SAIDA = 0 AND AC.COD_SALA = '".$sRoomID."' ORDER BY AC.NOME");
					while($myrow = mysql_fetch_array($result)) {
				        echo "<option value='" . $myrow["NOME"] . "'>" . $myrow["NOME"] . "</option>";
          }
				  ?>
		    </select>

 		  
			</td>
			
       <? //Configura os checkboxes. 
          if (empty($_REQUEST['firstTime']) || ($_REQUEST['scroll_text']=='y'))  { $rolagem = 'checked'; } else { $rolagem = ""; } 
          if (empty($_REQUEST['firstTime']) || ($_REQUEST['showInOut']!='y'))  { $mostrarInOut = ""; } else {  $mostrarInOut = 'checked';} 
       ?>
			 <td align="right" width='39%'>        
       Rolagem Automatica<input type='checkbox' name='scroll_text'  id='scroll_text' value='y' <? echo $rolagem;?> >
       <br>Ver entrada/saída a partir de agora<input type='checkbox' name='showInOut'  id='showInOut' value='y' <? echo $mostrarInOut;?>>
       <input type="hidden" value="1" name="firstTime">
       </td>
       </tr> 
			<tr>
			<td colspan="2" width='100%'>
			<textarea name="MESSAGE" style='width:100%;' rows="4" onKeyUp='javascript:sendMessageByKey(event);'></textarea>
      </td></tr>
      <tr>
      <td colspan="2"  align="right"  width='100%'>
        'Enter'= ENVIAR a mensagem. 'Shift' + 'Enter' = saltar a linha.&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="Enviar" value='   Enviar  ' >&nbsp;&nbsp;&nbsp;&nbsp;
			  <input type='button' name='exit'   value='    Sair   '	onClick="javascript:submit_exit();" style='background-color:#904020; color: #FFFFFF;'>		
			</td>
			</tr>
       </table> 
       <? exibeIconesChatCafe("document.form1.MESSAGE.value"); ?>
	</form>
  <script>document.form1.MESSAGE.focus();</script>
</body>
</html>
