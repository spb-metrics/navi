<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

include("../interacao/chat/configuration.inc.php");
ini_set("display_errors",0);

function constroiWherePeriodo($param){
	$sql='';
	if(!empty($param['dataInicio'])&&!empty($param['dataFim'])&&!empty($param['nomeCampoData'])){
	$sql .=" AND ".$param['nomeCampoData'].">='".$param['dataInicio']." 00:00:00' AND ".$param['nomeCampoData']."<='".$param['dataFim']." 23:59:59'";
	}
return $sql;
}
function getCodPessoaChatByNomeEnvia($codInstanciaGlobal,$param){
	global $talk_system_id; $param['nomeCampoData']='DATA';

  $sql  = " SELECT NOME_ENVIA, COD_PESSOA FROM ".getMessageTableName($codInstanciaGlobal);
  $sql .= " WHERE NOME_ENVIA != '{$talk_system_id}' AND NOME_ENVIA != 'SYSTEM'"; //nao busca a constante antiga e nem a nova
  if (!empty($codInstanciaGlobal)) {
    $sql .= " AND COD_SALA = ".$codInstanciaGlobal;
  }
   $sql .= constroiWherePeriodo($param);

  $sql .= " GROUP BY NOME_ENVIA"; //agrupa pelo campo passado. em principio eh COD_PESSOA
 
  $result = mysql_query($sql);
 

  $numMsgChat=array();

  while ($linha = mysql_fetch_assoc($result)) {
    $codPessoaChat[$linha["NOME_ENVIA"]]=$linha["COD_PESSOA"];
  }
  return $codPessoaChat;
}
function getNumMensagemChat($codInstanciaGlobal,$campoAgrupamento="COD_PESSOA", $param='') {
  global $talk_system_id; $param['nomeCampoData']='DATA';

  $sql  = " SELECT ".$campoAgrupamento." ,count(*) AS numMsg  FROM ".getMessageTableName($codInstanciaGlobal);
  $sql .= " WHERE NOME_ENVIA != '{$talk_system_id}' AND NOME_ENVIA != 'SYSTEM'"; //nao busca a constante antiga e nem a nova
  if (!empty($codInstanciaGlobal)) {
    $sql .= " AND COD_SALA = ".$codInstanciaGlobal;
  }
   $sql .= constroiWherePeriodo($param);

  $sql .= " GROUP BY ".$campoAgrupamento; //agrupa pelo campo passado. em principio eh COD_PESSOA

  $result = mysql_query($sql);
 

  $numMsgChat=array();

  while ($linha = mysql_fetch_assoc($result)) {
    $numMsgChat[$linha[$campoAgrupamento]]=$linha["numMsg"];
  }
  return $numMsgChat;
}

function getNumMensagemChatSYstem($codInstanciaGlobal,$campoAgrupamento="COD_PESSOA", $param='') {
  global $talk_system_id; $param['nomeCampoData']='DATA';

  $sql  = " SELECT ".$campoAgrupamento." ,count(*) AS numMsg  FROM ".getMessageTableName($codInstanciaGlobal);
  $sql .= " WHERE (NOME_ENVIA = '{$talk_system_id}' OR NOME_ENVIA = 'SYSTEM')"; // busca somente as mesnsagens  constante antiga e  a nova
  if (!empty($codInstanciaGlobal)) {
    $sql .= " AND COD_SALA = ".$codInstanciaGlobal;
  }
   $sql .= constroiWherePeriodo($param);

  $sql .= " GROUP BY ".$campoAgrupamento; //agrupa pelo campo passado. em principio eh COD_PESSOA

  $result = mysql_query($sql);
 

  $numMsgChat=array();

  while ($linha = mysql_fetch_assoc($result)) {
    $numMsgChat[$linha[$campoAgrupamento]]=$linha["numMsg"];
  }
  return $numMsgChat;
}

function getNumMensagens($codInstanciaGlobal,$tabelaMensagens,$tabelaSalas,$param='') {
	$param['nomeCampoData']='M.DATA_MENSAGEM';
  $sql  = " SELECT  COD_PESSOA,count( * )  AS numMsg FROM {$tabelaMensagens} M,{$tabelaSalas} S";
  $sql .= " WHERE M.COD_SALA=S.COD_SALA ";
  if (!empty($codInstanciaGlobal)) {
    $sql .= " AND S.COD_INSTANCIA_GLOBAL = ".$codInstanciaGlobal;
  }
  $sql .= constroiWherePeriodo($param);
  $sql .= " GROUP BY COD_PESSOA";


  $result = mysql_query($sql);

  while ($linha = mysql_fetch_assoc($result)) {
    $numMsgForum[$linha["COD_PESSOA"]]=$linha["numMsg"];
  }


  return $numMsgForum;
}


function getNumeroAcessos($codPessoa,$param='') {
	$param['nomeCampoData']='acesso';
  $sql  = " SELECT count( * ) AS numeroAcessos FROM acessopessoa " ;
  $sql .= " WHERE codPessoa=".quote_smart($codPessoa);
  $sql .= constroiWherePeriodo($param);

  $result = mysql_query($sql);
  $linha = mysql_fetch_array($result);
  if (!empty($linha['numeroAcessos'])) {
    return $linha['numeroAcessos'];
  }
  else {
    return 0; 
  }    
}
?>
