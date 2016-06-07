<?
/*
 * Script utilitario
 */
/* COLOCAR AQUI AS INSTANCIAS, CASO NECESSARIO
//$instanciasGlobais[]=284;
$instanciasGlobais[]=285;
$instanciasGlobais[]=286;
$instanciasGlobais[]=287;
$instanciasGlobais[]=288;
$instanciasGlobais[]=289;
$instanciasGlobais[]=290;
$instanciasGlobais[]=291;
$instanciasGlobais[]=292;
$instanciasGlobais[]=293;
$instanciasGlobais[]=294;
$instanciasGlobais[]=295;
$instanciasGlobais[]=296;
$instanciasGlobais[]=297;
$instanciasGlobais[]=298;
*/

foreach($instanciasGlobais as $codSala) {
  //tabela de mensagens 
  echo "<br>";
  echo  "CREATE TABLE `chat_mensagem_".$codSala."` (
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
            ) ; ";
  
  echo "<br>";
  /*
   * Esse trecho comentado serve para buscar os registros da tabela unica, caso necessario,
   * por exemplo se a segmentação ocorrer depois de ja ter havido chats   
   */   
  /*
  //usado para zerar a tabela
  echo "TRUNCATE chat_mensagem_".$codSala.";<br>";
  //buscar as tabelas da tabela compartilhada
  echo "INSERT INTO chat_mensagem_".$codSala." (COD_PESSOA,COD_SALA,NOME_ENVIA,NOME_CHAT,NOME_RECEBE,RESERVADO,MENSAGEM,DATA) <br>";
  echo "Select COD_PESSOA,COD_SALA,NOME_ENVIA,NOME_CHAT,NOME_RECEBE,RESERVADO,MENSAGEM,DATA from chat_mensagem where COD_SALA=".$codSala."; <br>";
  */
  echo "<br>";
  //tabela de ACESSOS 
  echo "CREATE TABLE `chat_acesso_".$codSala."` (
            `COD_ACESSO` int( 11 ) NOT NULL AUTO_INCREMENT ,
            `COD_PESSOA` int( 11 ) NOT NULL default '0',
            `NOME` varchar( 200 ) NOT NULL default '',
            `COD_SALA` int( 11 ) NOT NULL default '0',
            `DATA_ENTRADA` timestamp NULL default NULL ,
            `DATA_SAIDA` timestamp NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY ( `COD_ACESSO` ) ,
            KEY `COD_SALA` ( `COD_SALA` )
            ) ; <br>";

  /*
   * Esse trecho comentado serve para buscar os registros da tabela unica, caso necessario,
   * por exemplo se a segmentação ocorrer depois de ja ter havido chats   
   */   
   /*  
  echo "INSERT INTO chat_acesso_".$codSala."<br>";
  echo "Select * from chat_acesso where COD_SALA=".$codSala."; <br>";
  */
  
  
}          
?>
