<?
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);
/** FUNCOES PARA O FORUM
    SEPARADAS NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA
    
    Funcoes :
    - forum
    - forum_inserir    
**/
include("utils.inc.php");


function forum($cod,$getTurma=1) {
  if ($cod == "") {
	    // Procura todas mensagens de uma determinada sala	
    $strSQL =    "SELECT FS.COD_SALA, FS.DESC_SALA, FM.COD_MENSAGEM, DATE_FORMAT(FM.DATA_MENSAGEM, '%d/%m/%y %T') as DATA_MENSAGEM, FM.TEXTO_MENSAGEM, FM.RESPOSTA, P.NOME_PESSOA, P.COD_PESSOA" .
      " FROM ".$_SESSION["configForum"]["tabelaSalas"]." FS, ".$_SESSION["configForum"]["tabelaMensagens"]." FM, pessoa P".
      " WHERE FS.COD_SALA = FM.COD_SALA AND FM.COD_PESSOA = P.COD_PESSOA ";
    if ($getTurma) {
      $strSQL .="AND FS.COD_INSTANCIA_GLOBAL ='" . $_SESSION['codInstanciaGlobal'] . "'";
    }
    $strSQL .=" ORDER BY FM.DATA_MENSAGEM DESC";

  }
   else
    // Procura uma mensagem especifica	
    $strSQL =    "SELECT DATE_FORMAT(FM.DATA_MENSAGEM, '%d/%m/%y %T') as DATA_MENSAGEM, FM.TEXTO_MENSAGEM, P.NOME_PESSOA,P.COD_PESSOA" .
      " FROM ".$_SESSION["configForum"]["tabelaMensagens"]." FM, PESSOA P".
      " WHERE FM.COD_PESSOA = P.COD_PESSOA AND FM.COD_MENSAGEM ='" . $cod . "'";

  return mysql_query($strSQL);	
}

/**
 *  Retorna as mensagens principais ( main threads )
 */
function getMainThreads(&$numTotalMessages,$msgIni=0,$numMainThreadsPerPage=10,$getTurma=1,$showAll=0, $codSala='', $param='') {
  $numTotalMessages = "";
  if (!$showAll && $numTotalMessages == "") {
    //se o numero total de mensagens estiver vazio entao faz o count das mensagens
    $sql = "SELECT COUNT(*) as numMsg FROM ".$_SESSION["configForum"]["tabelaSalas"]." FS, ".$_SESSION["configForum"]["tabelaMensagens"]." FM WHERE";
    $sql.= " FS.COD_SALA = FM.COD_SALA ";

    if ($getTurma) {
     $sql .="AND FS.COD_INSTANCIA_GLOBAL ='" . $_SESSION['codInstanciaGlobal'] . "'";
	 if(!empty($codSala)){
       $sql .="AND FS.COD_SALA ='" . $codSala . "'";
	 }
	}
	if(!empty($param['dataInicio'])&&!empty($param['dataFim'])){
	$sql .=" AND FM.DATA_MENSAGEM>='".$param['dataInicio']." 00:00:00' AND FM.DATA_MENSAGEM<='".$param['dataFim']." 23:59:59'";
	}
	if(!empty($param['COD_PESSOA'])){
		 $sql .=" AND FM.COD_PESSOA=".$param['COD_PESSOA'];
	}else{
    $sql.= " AND (RESPOSTA = 0 OR RESPOSTA IS NULL)";
	}
	
    $result = mysql_query($sql);

    if ($result && mysql_num_rows($result) > 0) {
      $linha = mysql_fetch_array($result);
      $numTotalMessages = $linha["numMsg"];
    }
    else {
      $numTotalMessages = 0;
      return array();
    }
  }
    
  // Procura todas mensagens principais de uma determinada sala	
  $strSQL =    "SELECT FS.COD_SALA, FS.DESC_SALA, FM.COD_MENSAGEM, DATE_FORMAT(FM.DATA_MENSAGEM, '%d/%m/%y %T') as DATA_MENSAGEM, FM.TEXTO_MENSAGEM, FM.RESPOSTA, FM.codTipoMsg, P.NOME_PESSOA, P.FOTO_REDUZIDA, P.COD_PESSOA,  P.FOTO_REDUZIDA".
    " FROM ".$_SESSION["configForum"]["tabelaSalas"]." FS, ".$_SESSION["configForum"]["tabelaMensagens"]." FM, pessoa P".
    " WHERE FS.COD_SALA = FM.COD_SALA AND FM.COD_PESSOA = P.COD_PESSOA ";
    if ($getTurma) {
      $strSQL .="AND FS.COD_INSTANCIA_GLOBAL ='" . $_SESSION['codInstanciaGlobal'] . "'";
		 if(!empty($codSala)){
			$strSQL .=" AND FS.COD_SALA ='" . $codSala . "'";
		}

    }
	if(!empty($param['dataInicio'])&&!empty($param['dataFim'])){
		 $strSQL .=" AND FM.DATA_MENSAGEM>='".$param['dataInicio']." 00:00:00' AND FM.DATA_MENSAGEM<='".$param['dataFim']." 23:59:59'";
	}

	if(!empty($param['COD_PESSOA'])){
		 $strSQL .=" AND FM.COD_PESSOA=".$param['COD_PESSOA'];
	}else{
    $strSQL .=" AND (RESPOSTA = 0 OR RESPOSTA IS NULL) ";
	}
   $strSQL .= " ORDER BY FM.DATA_MENSAGEM DESC";
  
  //se tem mais mensagens do q deve ser exibido por pagina, e nao devem ser mostradas todas as paginas  
  //entao faz uma limit query
  if (!$showAll &&($numTotalMessages > $numMainThreadPerPage) ){    
    $strSQL.= " LIMIT {$msgIni},{$numMainThreadsPerPage} ";
  }
  $result = mysql_query($strSQL);
    
  $msgs = array();
  while($linha = mysql_fetch_array($result)) {
    $msgs[] = $linha;
  }
  
  return $msgs;
}

//retorna as mensagens filhas
function getChildMessages(&$mainThreads,$getTurma=1, $codSala='', $param='') {
  if ((is_array($mainThreads) && count($mainThreads) > 0 && empty($param['COD_PESSOA']))) {
    $strSQL =    "SELECT FS.COD_SALA, FS.DESC_SALA, FM.COD_MENSAGEM, DATE_FORMAT(FM.DATA_MENSAGEM, '%d/%m/%y %T') as DATA_MENSAGEM, FM.TEXTO_MENSAGEM, FM.RESPOSTA, FM.codTipoMsg, P.NOME_PESSOA, P.COD_PESSOA, P.FOTO_REDUZIDA" .
      " FROM ".$_SESSION["configForum"]["tabelaSalas"]." FS, ".$_SESSION["configForum"]["tabelaMensagens"]." FM, pessoa P".
      " WHERE FS.COD_SALA = FM.COD_SALA AND FM.COD_PESSOA = P.COD_PESSOA ";

	if(!empty($param['dataInicio'])&&!empty($param['dataFim'])){
		$strSQL .=" AND FM.DATA_MENSAGEM>='".$param['dataInicio']." 00:00:00' AND FM.DATA_MENSAGEM<='".$param['dataFim']." 23:59:59'";
	}

	
    if ($getTurma) {
      $strSQL .=" AND FS.COD_INSTANCIA_GLOBAL =" . quote_smart($_SESSION['codInstanciaGlobal']) ;
	  if(!empty($codSala)){
	    $strSQL .=" AND FS.COD_SALA =" . quote_smart($codSala) ;
	  }
    }
    
    //monta o or com as mensagens pai
    $str = "";
    foreach($mainThreads as $msgPai) {
      if (!empty($str)) $str.= " OR ";
      $str.= "MAIN_THREAD={$msgPai["COD_MENSAGEM"]}";
    }
    $strSQL.= " AND ({$str}) ";
    
    $strSQL.= " ORDER BY FM.DATA_MENSAGEM DESC"; 
    $result = mysql_query($strSQL);
    
    //coloca as mensagens filhas num array 2d indexado pelo codigo da msg pai
    $msgs = array();
    while($linha = mysql_fetch_array($result)) {
      $msgs[$linha["RESPOSTA"]][] = $linha;
    }
    
    return $msgs;
  }
  else
    return 0;
}
 
//======================================================================================================
// interacao/forum/envia_texto.asp

function forum_inserir($texto, $resposta, $codMainThread=0, $cod_sala, $codInstanciaGlobal, $tipoMsg) {
    $strSQL = "INSERT INTO ".$_SESSION["configForum"]["tabelaMensagens"]." (COD_PESSOA, COD_SALA, TEXTO_MENSAGEM, MAIN_THREAD , RESPOSTA,  codTipoMsg)" .
    " VALUES (".quote_smart($_SESSION["COD_PESSOA"]).",".quote_smart($cod_sala).",".quote_smart($texto).",".quote_smart($codMainThread).",".quote_smart($resposta).",".$tipoMsg.")";

  
  mysql_query($strSQL);
  $lastIdMensagem=mysql_insert_id();

  if(!empty($_SESSION["configForum"]["tabelaMsgNova"])){
     $msgNova=msgNova($cod_sala,$_SESSION["configForum"]["tabelaMsgNova"], $codInstanciaGlobal, $lastIdMensagem, quote_smart($_SESSION["COD_PESSOA"])); 
  }
  return (! mysql_errno()); 
}		

/**
 * Le o arquivo ini dos icones e retorna um array onde com a tag img e outro array com a traducao entre a tag img 
 *  e os caracteres
 */
function leIniIcones(&$imagens,&$traducao,$arquivoIni='') {
  if (empty($arquivoIni)) {
    $ini = parse_ini_file("emoticons/emoticons.ini",1);
  }
  else {
    $ini = @parse_ini_file($arquivoIni,1);  
  }
  //array contendo as tags img
  $imagens = array();
  //array contendo a traducao ( nem todas imagens precisam de traducao)
  $traducao = array();

  foreach($ini["Emoticons"] as $img=>$trad) {
    $trad = addSlashes($trad);
    $imgTag = "<img src=emoticons/".$img." class=emoticon>";
    $imagens[] =array ("imagem" => $imgTag, "traducao" => $trad );
    //Guarda as traducoes separadamente para usar strtr()
    if (!empty($trad))
      $traducao[$trad] = $imgTag;
  }
}

/**
 * 
 *  Imprime os icones, permitiondo que o usuario selecione algum para colocar nam mensagem
 *
 */
function imprimeIcones($arquivoIni='') {

  leIniIcones($imagens,$traducao,$arquivoIni);

  if (count($imagens) > 0) {
    
    $index = 1;
  
    echo "<table id=\"tabelaEmoticons\">";
    echo "<tr id=\"linhaTabelaEmoticons\">";    
    
    foreach($imagens as $img) {
      if ($index > 10) {
	      echo "</tr><tr id=\"linhaTabelaEmoticons\">";
	      $index = 1;
      }
      if (empty($img["traducao"])) { $texto = $img["imagem"]; } else {  $texto = addSlashes($img["traducao"]); }
      //o str_replace server para substituir < e > por suas entidade html, para nao confundir o browser
      $js = "document.form1.TEXTO.value += '".str_replace(array("<",">"),array("&lt;","&gt;"),$texto)."';";      
      echo "<td id=\"colunaTabelaEmoticons\"><a href=\"#\" onclick=\"".$js."\" alt=".$img["traducao"].">".$img["imagem"]."<br>".$img["traducao"]."</a></td>";
      $index++;
    }

    echo "</tr>";
    echo "</table>";
  }
  
}

/**
 * Faz a traducao de emoticons ( dos caracters para as imagens) 
 * Ext :) -> <img src=rindo.gif>
 */
function traduzEmoticons($texto,$arquivoIni='') {
  leIniIcones($imagens,$traducao,$arquivoIni);
  
  if (count($traducao) > 0) {
    $texto = strtr($texto,$traducao);
  }
  
  return $texto;
}

//==================================================================================================
//deve receber o forum que deve ser pesquizado e qual a turma
function listaTopicos($codInstanciaGlobal){
	$strSQL= "SELECT * FROM ".$_SESSION["configForum"]["tabelaSalas"]." WHERE COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal." ORDER BY COD_SALA DESC";
	
	return mysql_query($strSQL);

}

//===================================================================================================
//deve receber qual o forum a ser pesquizado e qual o topico
function numMsgsForum( $codSala){
	$strSQL= "SELECT count(*) as numMsg FROM ".$_SESSION["configForum"]["tabelaMensagens"]." WHERE COD_SALA=".$codSala; 
	
	
	$numMsgs= mysql_query($strSQL);

	$numMsgsForum= mysql_fetch_array($numMsgs);

	return $numMsgsForum['numMsg'];
}

/*function numMsgsNovasForum($codSala){
	//criar tabela que garda número de novas msgs
}*/

function formInsertTopico($codInstanciaGlobal,$criadoPorCodPessoa)
{
Global $url;
echo'<form name="f1" method="post" enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'?acao=insertTopico">',
	'<fieldset  class="fieldsetTopico" >';
echo"<legend class=\"menu\" style=\"cursor:pointer\">Inserir novo tópico<img src='".$url."/imagens/diminui.gif' onClick=\"mostraForm('novoTopico');mudaFigura(this,'".$url."')\" id=\"imagem\"></legend>";
echo'<div id="novoTopico" align="center" style=\"display:inline;\">',	
		'<table>',
			 '<tr>',
			  '<td align=center width="60%" >',				
				'<p><b> Descrição do Tópico: </b><br>',
				'<input type="text" name="topico" value="" size="80">',
				'<input type="hidden" name="codPessoa" value="'.$criadoPorCodPessoa.'" >',
				'</p></td></tr>',
		    '<tr>',
			  '<td align="center">',
				'<input type="button" name="Submit" value="Enviar" onclick="document.f1.submit();">',
	          '</td></tr>',
		'</table>',
		'</div>',
	'</fieldset>',
	'</form>';

}

Function formAlterarTopico($codSala,$topico,$criadoPorCodPessoa){
echo'<form name="f1" method="post" enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'?acao=alterarTopico">',
	' <p><b> Descrição do Tópico: </b><br>',
	'  <textarea name="topico" style="width: 90%; height: 30px;">'.$topico.'</textarea>',
	'  <input type="hidden" name="codPessoa" value="'.$criadoPorCodPessoa.'" >',
	'  <input type="hidden" name="codSala" value="'.$codSala.'" >',
	'  <input type="submit" name="Submit" value="Enviar" >',
	' </p>',
	'</form>';
}


function insertTopico($codInstanciaGlobal, $topico, $criadoPorCodPessoa)
{

	$desc_sala="";
	$strSQL =   "INSERT INTO ".$_SESSION["configForum"]["tabelaSalas"]." (COD_INSTANCIA_GLOBAL, DESC_SALA, topico,criadoPorCodPessoa)".
	" VALUES (".quote_smart($codInstanciaGlobal). ",".quote_smart($desc_sala).",".quote_smart($topico).",".quote_smart($criadoPorCodPessoa).")";

     mysql_query($strSQL);	
	 $cod_sala=mysql_insert_id();


	 return  $cod_sala;
}
//===========================================================================================
function alterarTopico($codInstanciaGlobal,$codSala,$topico,$codPessoa)
{
	$strSQL= " UPDATE ". $_SESSION["configForum"]["tabelaSalas"]." SET  topico=".quote_smart($topico)."".
			 " WHERE COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal." AND COD_SALA=".$codSala." AND criadoPorCodPessoa=".$codPessoa;

	mysql_query($strSQL);
	return (! mysql_errno());
}
function excluirTopico($codInstanciaGlobal,$codSala)
{
	$strSQL = "DELETE FROM ". $_SESSION["configForum"]["tabelaSalas"]." WHERE COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal." AND COD_SALA=".$codSala;

 mysql_query($strSQL);	

return (! mysql_errno());
}

//============================================================================================

/**
* funcoes  MsgNova relacionado com o numero de msgs novas nos topicos por pessoa 
**/

function MsgNova($codSala,$tabelaMsgNovas,$codInstanciaGlobal, $codMensagem, $codPessoa)
{
	$codTurma=getTurma($codInstanciaGlobal);
	$codNivel = getNivel($codInstanciaGlobal);
/*** Aluno e Turma********/
if ($codNivel==6) {
  $strSQL= "INSERT INTO ".$tabelaMsgNovas." (codSala,codPessoa, codMensagem)".
			 " SELECT ".$codSala." as codSala, A.COD_PESSOA as codPessoa, ".$codMensagem." as codMensagem".
			 " FROM aluno A ".
			 " INNER JOIN aluno_turma AT ON (A.COD_AL=AT.COD_AL)".
			 " WHERE AT.COD_TURMA=".$codTurma." AND A.COD_PESSOA<>".$codPessoa;  
			 }
//Aluno e Comunidade
if ($codNivel==7) {
  $strSQL= "INSERT INTO ".$tabelaMsgNovas." (codSala,codPessoa, codMensagem)".
			 " SELECT ".$codSala." as codSala, A.COD_PESSOA as codPessoa, ".$codMensagem." as codMensagem".
			 " FROM aluno A ".
			 " INNER JOIN alunocomunidade AT ON (A.COD_AL=AT.COD_AL)".
			 " WHERE AT.codComunidadeTematica=".$codTurma." AND A.COD_PESSOA<>".$codPessoa;  
}
			 
mysql_query($strSQL);	

	/*** professor e Turma********/
if ($codNivel==6) {
  $strSQL= "INSERT INTO ".$tabelaMsgNovas." (codSala,codPessoa, codMensagem)".
		 " SELECT ".$codSala." as codSala, P.COD_PESSOA as codPessoa, ".$codMensagem." as codMensagem".
		 " FROM professor P ".
		 " INNER JOIN professor_turma PT ON (P.COD_PROF=PT.COD_PROF)".
		 " WHERE PT.COD_TURMA=".$codTurma." AND P.COD_PESSOA<>".$codPessoa;  
}
	/*** professor e Comunidade********/
if ($codNivel==7) {
  $strSQL= "INSERT INTO ".$tabelaMsgNovas." (codSala,codPessoa, codMensagem)".
		 " SELECT ".$codSala." as codSala, P.COD_PESSOA as codPessoa, ".$codMensagem." as codMensagem".
		 " FROM professor P ".
		 " INNER JOIN professorcomunidade PT ON (P.COD_PROF=PT.COD_PROF)".
		 " WHERE PT.codComunidadeTematica=".$codTurma." AND P.COD_PESSOA<>".$codPessoa;  
}		 
mysql_query($strSQL);	

	/*** ADM nivel********   pode ser estendido  para outras pessoas só replicar codigo abaixo/
/*$strSQL= "INSERT INTO ".$tabelaMsgNovas." (codSala,codPessoa, codMensagem)".
		 " SELECT ".$codSala." as codSala, A.COD_PESSOA as codPessoa, ".$codMensagem." as codMensagem".
		 " FROM administrador A ".
		 " INNER JOIN professor_turma PT ON (P.COD_PROF=PT.COD_PROF)".
		 " WHERE PT.COD_TURMA=".$codTurma." AND A.COD_PESSOA<>".$codPessoa;  
			 
mysql_query($strSQL);	*/

    return (! mysql_errno());
}

function VerificaMsgNova($codInstanciaGlobal, $codSala, $codPessoa,$tabelaMsgNovas,$tabelaSala)
{
	$strSQL= "SELECT count(*) as numMSgNova FROM ".$tabelaMsgNovas." TMN" .
			 " INNER JOIN ".$tabelaSala." TS ON (TS.COD_SALA=TMN.codSala)".
			 " WHERE TMN.codSala=".$codSala." AND TS.COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal." AND TMN.codPessoa=".$codPessoa;

$numMsgs=mysql_query($strSQL);	
$numMsgsnovas= mysql_fetch_array($numMsgs);

return $numMsgsnovas['numMSgNova'];
}

function BuscaMsgNova($codInstanciaGlobal, $codSala, $codPessoa,$tabelaMsgNovas,$tabelaSala, $codMensagem)
{
	$strSQL= "SELECT * FROM ".$tabelaMsgNovas." TMN" .
			 " INNER JOIN ".$tabelaSala." TS ON (TS.COD_SALA=TMN.codSala)".
			 " WHERE TMN.codSala=".$codSala." AND TS.COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal." AND TMN.codPessoa=".$codPessoa." AND TMN.CodMensagem=".$codMensagem;

$buscaMensagem=mysql_query($strSQL);	

return $buscaMensagem;
}

function DelStatusMsgNova($codSala,$codPessoa,$tabelaMsgNovas)
{
	$strSQL= "DELETE FROM ".$tabelaMsgNovas." WHERE codSala=".$codSala." AND codPessoa=".$codPessoa;

	mysql_query($strSQL);	
    return (! mysql_errno());
}
 /*MsgNova($codInstanciaGlobal,);*/
//============================================================================
function getTurma($codInstanciaGlobal)
{
	$strSQL= "SELECT codInstanciaNivel FROM instanciaglobal WHERE codInstanciaGlobal=".$codInstanciaGlobal;
$codInstanciaNivel=mysql_query($strSQL);	
$codTurma= mysql_fetch_array($codInstanciaNivel);

return $codTurma['codInstanciaNivel'];
}

function getNivel($codInstanciaGlobal)
{
	$strSQL= "SELECT codNivel FROM instanciaglobal WHERE codInstanciaGlobal=".$codInstanciaGlobal;
$codNivel=mysql_query($strSQL);	
$codNivel= mysql_fetch_array($codNivel);

return $codNivel['codNivel'];
}

//==================funcoes relacionndas a classificação das msgs=============================================
function getTipoMsg()
{
	$strSQL= "SELECT * FROM tipo_msg WHERE 1";

	return mysql_query($strSQL);

}


function classificaTipoMsg($coTipodMsg)
{
	$strSQL= "SELECT descMsg FROM tipo_msg TM WHERE TM.codTipoMsg=".$coTipodMsg;


$tipoMsg=mysql_query($strSQL);	
$descMsg= mysql_fetch_array($tipoMsg);

	return $descMsg['descMsg'];
}

function DelMSG($codMSG)
{
	$strSQL=" DELETE FROM ". $_SESSION["configForum"]["tabelaMensagens"]." WHERE COD_MENSAGEM=".$codMSG;
	mysql_query($strSQL);	
	$strSQL=" DELETE FROM ". $_SESSION["configForum"]["tabelaMsgNova"]." WHERE CodMensagem=".$codMSG;
	mysql_query($strSQL);	
    return (! mysql_errno());
}

Function formAlterarMSG($codMSG,$MSG,$codPessoa,$codSala){
echo'<form name="f1" method="post" enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'?acao=alterarMSG">',
	' <p><b> Descrição da mensagem: </b><br>',
	'  <textarea name="MSG" style="width: 90%; height: 30px;">'.$MSG.'</textarea>',
	'  <input type="hidden" name="codPessoa" value="'.$codPessoa.'" >',
	'  <input type="hidden" name="codMSG" value="'.$codMSG.'" >',
	'  <input type="hidden" name="COD_SALA" value="'.$codSala.'" >',
	'  <input type="submit" name="Submit" value="Enviar" >',
	' </p>',
	'</form>';
}

function AlterarMSG($codMSG,$MSG,$codPessoa)
{
	$strSQL="UPDATE ". $_SESSION["configForum"]["tabelaMensagens"]." SET TEXTO_MENSAGEM='".$MSG."'  WHERE COD_PESSOA=".$codPessoa." AND COD_MENSAGEM=".$codMSG;

	mysql_query($strSQL);
	return (! mysql_errno());
}
//====================================================================================
function geraArrayLocalPessoasForm($codInstanciaGlobal) {
 
  echo "var pessoas = new Array();";
  echo "var codPessoa = new Array();";


  $sql = " SELECT * FROM pessoa P".
		 " INNER JOIN ". $_SESSION["configForum"]["tabelaMensagens"]." FM ON (FM.COD_PESSOA=P.COD_PESSOA)".
		 " INNER JOIN ". $_SESSION["configForum"]["tabelaSalas"]."	FS ON (FM.COD_SALA=FS.COD_SALA)".	 
         " WHERE FS.COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal." ".
		 " GROUP BY FM.COD_PESSOA".
		 " ORDER BY P.NOME_PESSOA ASC";
  $result = mysql_query($sql);
  $i=0;

  while( $linha =  mysql_fetch_assoc($result) ) {
	echo "codPessoa[".$i."]=".$linha['COD_PESSOA'].";";
    echo "pessoas[".$i."]='".replace_accents(ucwords(strtolower($linha['NOME_PESSOA'])))."';";
   
    $i++;
  }
}
function getNomeTopico($codSala){
	$sql="SELECT topico FROM ". $_SESSION["configForum"]["tabelaSalas"]." WHERE COD_SALA=".$codSala;

$rsCon=mysql_query($sql);
$nomeTopico=mysql_fetch_array($rsCon);

return $nomeTopico["topico"];

}

function replace_accents($str) {
  $str = htmlentities($str);
  $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/','$1',$str);
  return html_entity_decode($str);
}
?>
