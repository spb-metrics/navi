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
//error_reporting(E_ALL);
ob_start("ob_gzhandler"); 
//include("configuration.inc.php");
include("bd_funcoes.php");
session_name(SESSION_NAME); session_start(); security();
//le as variaveis de sessao q ira usar e ja encerra a sessao, para nao bloquear os outros frames 
session_write_close();
$sRoomID = (int)$_SESSION["ROOM_ID"];
$sUserID = (string)$_SESSION["USER_ID"];
$codPessoa = (int)$_SESSION["COD_PESSOA"];

//gera o conteudo do javascript que acrescentará localmente as mensagens na pagina visualizada
function talk_print_message($codPessoa,$sender_id, $nomeParticipante, $receiver_id, $message_text, $timestamp, $reservado, $classe='') {
  global $sUserID,$sRoomID,$output;
  if ($sender_id == $GLOBALS["talk_cmd_id"]) { return; }
  //se for reservado, e a pessoa nao estiver envolvida, nao mostra
	if ( $reservado==1) { 
    if ( !(($receiver_id == $sUserID) OR ($sender_id == $sUserID)) )  { return; }
  }
  $strOutput =""; 
  
  //$strOutput .= "<span class=\"horario\">(".date("H:i:s",$timestamp).")</span>";
  if (empty($nomeParticipante)) {  //caso o nome de guerra esteja em branco, pega os dois primeiros nomes ou 20 por default    
    $tamanho = strpos($sender_id,' ') + strpos(substr($sender_id,strpos($sender_id,' ')+1,strlen($sender_id)),' '); 
    if (!$tamanho) { $tamanho=19;}
    $tamanho++;
    $nomeParticipante = substr($sender_id,0,$tamanho);
  }
  //trata nome do participante para nao ter bug nos nomes
  $nomeParticipante = str_replace( array ( '&', '"', "'", '<', '>', "\r\n" , "\n" ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '<br>', '<br>' ), $nomeParticipante); 
  //começa a montar a string de retorno 
  $strOutput .= "<span title=\"".$sender_id.", ".date("H:i:s",$timestamp)."\">".$nomeParticipante."</span>";
  //$strOutput .=$sender_id;
  if ($reservado == 1) {
	    $strOutput .= STR_MSG_RESERVED . $receiver_id;
	}
  else 	{
    $strOutput .= STR_MSG_TALK;
		if ($receiver_id != "#".$sRoomID) {
			 $strOutput .= STR_MSG_TO . " " . $receiver_id;
    }
	}
  $strOutput .= ": ";
  $strOutput .= str_replace( array ( '&', '"', "'", '<', '>', "\r\n" , "\n" ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '<br>', '<br>' ), rtrim($message_text) );		
  //determina o layout de exibiçao
  if (isProfessor($codPessoa)) { 
    $classeTipoMensagem='msgProfessor'; 
  } 
  else if ($strOutput[strlen($strOutput)-1]=='?'){    
    $classeTipoMensagem='msgPergunta';  
  }
  $output .= "<div class=\"msgNormal ".$classe." ".$classeTipoMensagem."\">".$strOutput."</div>";
}
$inicio_dia  = date("Y").date("m").date("d")."000000" ;
$fim_dia     = date("Y").date("m").date("d")."235959" ;

if (!empty($_REQUEST['lastMessage'])) {  $last_message = $_REQUEST['lastMessage']; }
else {  $last_message = 0;}

//marca 'alive' do usuario no banco
//alive($codPessoa);

//Monta SQL e recupera mensagens
$sql = "SELECT MSG.COD_PESSOA,MSG.COD_MENSAGEM, MSG.NOME_ENVIA, MSG.NOME_CHAT, MSG.NOME_RECEBE, MSG.MENSAGEM , UNIX_TIMESTAMP(MSG.DATA) U_M_TIMESTAMP, MSG.RESERVADO, MSG.COD_SALA ".
       " FROM ".getMessageTableName($sRoomID)." MSG ".
       " WHERE MSG.DATA < ". $fim_dia ." AND MSG.DATA > ".$inicio_dia .
       "  AND MSG.COD_MENSAGEM > ".quote_smart($last_message)." AND MSG.COD_SALA = ".quote_smart($sRoomID);
if (!$_REQUEST['showInOut']) { 
  $sql.= " AND MSG.NOME_ENVIA!=".quote_smart($talk_system_id);
}
$sql.= " ORDER BY MSG.COD_MENSAGEM";
$result = mysql_query($sql);

$output = "";  //inicializacao da variavel de mensagens 
if (!isset($_REQUEST)) { $par=0; } else { $par=$_REQUEST['par']; }  //linha par/impar

while ($myrow = mysql_fetch_array($result))  {
  $last_message = $myrow["COD_MENSAGEM"];
  if ($par) { $par=0; $classe='linhaPar';} else { $par=1; $classe='linhaImpar';} 
  talk_print_message($myrow["COD_PESSOA"],$myrow["NOME_ENVIA"], $myrow["NOME_CHAT"],$myrow["NOME_RECEBE"], $myrow["MENSAGEM"], $myrow["U_M_TIMESTAMP"], $myrow["RESERVADO"],$classe);
}
?>
<script>
/*  em caso de erro vai em frente*/
function goAhead() {
  return true;
}
window.onError=goAhead;
<? 
if (!isset($_REQUEST['lastMessage'])) { //primeira entrada
  echo " alert('Bem vindo a aula interativa!'); "; 
}
?>
/* Movimenta o texto se o usuario desejar */
function mover_texto() {
  el = null;  
  el = window.parent.frames[1].document.getElementById('scroll_text');
  if (el!=null && typeof(el)!='undefined') {
    if ( el.checked) { move();  }
  }
}
function move() {  
	with (window.parent.frames[0].window) {
		if (typeof(scrollBy) != 'undefined')		{
			scrollBy(0, 65000);
			scrollBy(0, 65000);
		}
		else if (typeof(scroll) != 'undefined') {
			scroll(0, 65000);
			scroll(0, 65000);
		};
	}
}
function atualizaConteudo(conteudo) {
   el = null; 
   el = window.parent.frames[0].document.getElementById('mensagensChat');
   if (el!=null && typeof(el)!='undefined') { 
     el.innerHTML=el.innerHTML + conteudo; 
   }
}
// Faz o reload para buscar novas mensagens
function reloadMySelf() {
  el=null;
  el = window.parent.frames[1].document.getElementById('showInOut');
  if (el!=null && typeof(el)!='undefined') {
    if (el.checked) {
      location.href = './B.php?lastMessage=<?=$last_message?>&par=<?=$par?>&showInOut=1';
    }
    else {
     location.href = './B.php?lastMessage=<?=$last_message?>&par=<?=$par?>&showInOut=0';
    }
  }
  else { //por padrao nao mostra entradas e saidas
   location.href = './B.php?lastMessage=<?=$last_message?>&par=<?=$par?>&showInOut=0';
  }
}
<? 
//Acrescenta as mensagens ainda nao vistas
echo " atualizaConteudo('".$output."'); "; 
if (!isset($_REQUEST['lastMessage'])) { //primeira entrada: ja mostramos o alert, agora vamos m
  echo " move(); "; 
}
else { //demais entradas
  echo " mover_texto();  ";
}
/*espera 5 segundos para dar o reload*/
echo " setTimeout('reloadMySelf()',5500); "; 
?>
</script>
