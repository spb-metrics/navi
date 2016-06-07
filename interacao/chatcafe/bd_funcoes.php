<?php
//include_once ("./../../config.php");
/*
//$db = mysql_connect("localhost", "cursosnavi", "kt8mg76i");
//mysql_select_db("cursos_2");
//$db = mysql_connect("localhost", "root", "");
//mysql_select_db("cursos_2");*/
include("configchat.php");
include("configuration.inc.php");
$db = mysql_connect(BD_HOST, BD_USER, BD_SENHA);
mysql_select_db(BD_NAME);


function talk_db_message_insert($codPessoa,$nomeChat,$sala, $envia, $recebe, $reservado, $mensagem) {
  $sql="INSERT INTO ".getMessageTableName($sala)." (COD_PESSOA,COD_SALA, NOME_ENVIA, NOME_CHAT,NOME_RECEBE, RESERVADO, MENSAGEM, DATA) VALUES (".quote_smart($codPessoa).",".quote_smart($sala).",".quote_smart($envia).",".quote_smart($nomeChat).",".quote_smart($recebe).",".quote_smart($reservado).",".quote_smart($mensagem).", now())";
  //echo $sql;
  mysql_query($sql);	  
    
  if(mysql_errno()) { echo $sql; echo mysql_errno().": ".mysql_error()."<BR>"; die(); }
}

function talk_db_access_insert($codPessoa,$USER_ID, $ROOM_ID) {
  $sql = "INSERT INTO ".getAccessTableName($ROOM_ID)." (COD_PESSOA,NOME, COD_SALA, DATA_ENTRADA, DATA_SAIDA) ".
  " VALUES (".quote_smart($codPessoa).",".quote_smart($USER_ID).",".quote_smart($ROOM_ID).", now(), '00000000000000')";
  
  mysql_query($sql);

  if(mysql_errno()) { echo $sql; echo mysql_errno().": ".mysql_error()."<BR>"; die(); }
}
//marca a saida deste usuario, pois clicou em sair
function talk_db_access_update($codPessoa,$codSala) {
    //em 18/11/2005: Não é mais necessário fazer isto, pois o "on update timestamp" foi retirado.
        // O mysql esta setando data_entrada=now no update entao eu pego o data_entrada antes do update...
       //$result = mysql_query("SELECT DATA_ENTRADA FROM CHAT_ACESSO WHERE DATA_SAIDA = '00000000000000' AND NOME = '$USER_ID'");
	     //$myrow = mysql_fetch_array($result);
       //$sql = "UPDATE CHAT_ACESSO SET DATA_SAIDA=now(), DATA_ENTRADA='" . $myrow["DATA_ENTRADA"] . "' WHERE DATA_SAIDA = '00000000000000' AND NOME = '$USER_ID'";
    //agora basta fazer o update em data saida
    $sql = "UPDATE ".getAccessTableName($codSala)." SET DATA_SAIDA=now(),DATA_ENTRADA=DATA_ENTRADA WHERE DATA_SAIDA = '00000000000000' AND COD_PESSOA = ".quote_smart($codPessoa);
    mysql_query($sql);
   

    if(mysql_errno()) { echo $sql; echo mysql_errno().": ".mysql_error()."<BR>"; die(); }
}

function talk_is_online($codPessoa,$codSala) {
  $sql="SELECT NOME FROM ".getAccessTableName($codSala)." WHERE DATA_SAIDA = '00000000000000' AND COD_PESSOA = ".quote_smart($codPessoa);
  $result = mysql_query($sql);
							   
  if(mysql_errno()) { echo $sql; echo mysql_errno().": ".mysql_error()."<BR>"; die(); }

	if(mysql_num_rows($result)) 
    return true;
	else 
    return false;
}

function getNomeChat($codPessoa) {
  $sql = "select NOME_CHAT from pessoa where COD_PESSOA=".$codPessoa;

  $result = mysql_query($sql);
  
  $linha = mysql_fetch_assoc($result);
  
  return $linha['NOME_CHAT'];
}

function isProfessor($codPessoa) {
  $sql = "select * from professor where COD_PESSOA=".$codPessoa;

  $result = mysql_query($sql);
  
  $num = mysql_num_rows($result);
  return $num;
}

//Retorna o nome correto da tabela de acessos
function getAccessTableName($codSala) {
  $accessTableName='chatcafe_acesso';
  //Verifica se esta se usando sessao para otimizacao
  if (!isset($_SESSION['useSelfTable'])) {
    if (isSelfTable($codSala))  { $accessTableName.='_'.$codSala; }
  }
  else if ($_SESSION['useSelfTable']) {
    $accessTableName.='_'.$codSala;
  }
  
  return $accessTableName;
}

//se for usada uma tabela propria para a turma, entao cria automaticamente, se necessário....
function verifyChatTables($codSala) {
  
  //Verifica se a tabela de MENSAGENS existe...
  mysql_query("select COD_SALA FROM  chatcafe_mensagem_".$codSala." LIMIT 1");
  //verifica se houve o erro 1146, de tabela nao existente
  if (mysql_errno()==1146) {
    $sql = "CREATE TABLE `chatcafe_mensagem_".$codSala."` (
            `COD_MENSAGEM` int( 11 ) NOT NULL AUTO_INCREMENT ,
            `COD_PESSOA` int( 11 ) NOT NULL default '0',
            `COD_SALA` int( 11 ) NOT NULL default '0',
            `NOME_ENVIA` varchar( 200 ) NOT NULL default '',
            `NOME_CHAT` varchar( 20 ) NOT NULL default '',
            `NOME_RECEBE` varchar( 200 ) NOT NULL default '',
            `RESERVADO` tinyint( 1 ) NOT NULL default '0',
            `MENSAGEM` text NOT NULL ,
            `DATA` timestamp NULL ,
            PRIMARY KEY ( `COD_MENSAGEM` )
            ) ;";
  
    mysql_query($sql);
    echo mysql_error();
  }
  
  //Verifica se a tabela de ACESSOS existe...
  mysql_query("select COD_SALA FROM chatcafe_acesso_".$codSala." LIMIT 1");
  //verifica se houve o erro 1146, de tabela nao existente
  if (mysql_errno()==1146) {
    $sql = "CREATE TABLE `chatcafe_acesso_".$codSala."` (
            `COD_ACESSO` int( 11 ) NOT NULL AUTO_INCREMENT ,
            `COD_PESSOA` int( 11 ) NOT NULL default '0',
            `NOME` varchar( 200 ) NOT NULL default '',
            `COD_SALA` int( 11 ) NOT NULL default '0',
            `DATA_ENTRADA` timestamp NULL default NULL ,
            `DATA_SAIDA` timestamp NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY ( `COD_ACESSO` ) ,
            KEY `COD_SALA` ( `COD_SALA` )
            ) ;";
  
    mysql_query($sql);
    echo mysql_error();
  }
}
/*
FUNCOES CONSTRUIDAS PARA VERIFICAR SE O USUARIO ESTA ON-LINE

function refreshOnlinePeople($codSala) { 
  //$sql = "UPDATE chat_acesso SET DATA_SAIDA = NOW( ) ";
  //$sql.= "WHERE ( SECOND(TIMEDIFF(NOW( ),ALIVE)) >".TIMEOUT_ALIVE.") AND DATA_SAIDA =0 AND COD_SALA=".quote_smart($codSala);
  //echo $sql;
  $sql = "UPDATE chat_acesso SET DATA_SAIDA = NOW( ),DATA_ENTRADA=DATA_ENTRADA ";
  $sql.= "WHERE DATA_SAIDA =0 AND COD_SALA=".quote_smart($codSala). 
  
  " AND (
(
substring( now( ) , 15, 2 ) = substring( ALIVE, 11, 2 ) 
AND (
substring( now( ) , 18, 2 ) - substring( ALIVE, 13, 2 ) 
) >30
)
OR (
(
substring( now( ) , 15, 2 ) - substring( ALIVE, 11, 2 ) 
) >1
)
OR (
(
substring( now( ) , 12, 2 ) - substring( ALIVE, 9, 2 ) 
) >0
AND ( 60 - substring( ALIVE, 11, 2 ) ) >1
)
)
";

  mysql_query($sql);
}

function alive($codPessoa) { 
  $sql = "UPDATE chat_acesso SET ALIVE = NOW( ),DATA_ENTRADA=DATA_ENTRADA ";  //hack para o bd GFN/BB
  $sql.= "WHERE DATA_SAIDA =0 AND COD_PESSOA=".quote_smart($codPessoa) ;
  mysql_query($sql);
}
*/
?>
