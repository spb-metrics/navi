<?
ini_set("display_errors",1);
error_reporting(E_ALL);
include("bd_funcoes.php");
//include("configuration.inc.php");	
$ROOM_ID = (int) $_REQUEST["ROOM"];
$USER_ID = (string) $_REQUEST["NOME"];

session_start();
$_SESSION["ROOM_ID"] = $ROOM_ID;
$_SESSION["USER_ID"] = $USER_ID;
$_SESSION["NOME_CHAT"] = getNomeChat($_SESSION["COD_PESSOA"]);
//se esta turma usar tabela propria, entao verifica se ela precisa ser criada...
if (isSelfTable($ROOM_ID)) { 
  verifyChatTables($ROOM_ID); $_SESSION['useSelfTable']=1; 
} 
else { 
  $_SESSION['useSelfTable']=0;
}
session_write_close();
$codPessoa = (int)$_SESSION["COD_PESSOA"];



//Se houver registros pendentes desta pessoa, sem a saida, são atualizados ('fechados') agora
talk_db_access_update($codPessoa,$ROOM_ID);
//A pessoa agora está on-line
talk_db_access_insert ($codPessoa,$USER_ID, $ROOM_ID);
talk_db_message_insert($codPessoa,$_SESSION["NOME_CHAT"],$ROOM_ID, $talk_system_id, "#".$ROOM_ID, false, $USER_ID ." entra na sala."); 	

?>
	<html>
		<head>
			<title>NAVi Chat </title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <link rel=stylesheet href=./chat.css type=text/css>
    </head>

		<frameset rows="270,165,*" frameborder="NO" border="0" framespacing="0"> 
		  <frame name="show"			    src="./show.html" scrolling="yes">
			<frame name="messageFrame" noresize src="./C.php" >
      <frame name="loadMessagesFrame"	   src="./B.php" scrolling="no"  style="display:none; visibility:hidden; zindex:-1;"> 
    </frameset>
		<noframes>
		</noframes>
    
	</html>
