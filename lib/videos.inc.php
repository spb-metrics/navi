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


/** FUNCOES PARA A MANIPULACAO DE VIDEOS
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
**/

//======================================================================================================
// tools/video.asp
// Retorna os videos disponiveis para uma turma

/*Parametros para  a busca do caminho em diferentes resolução*/
 $caminho["alta"]="CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO";
 $caminho["baixa"]="CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO";

//tirei join com turma e disciplina
function videoAulasADM($codInstanciaGlobal)
{
  if ($codInstanciaGlobal != "")
    $strSQL = "SELECT VT.COD_VIDEO, VT.DESC_VIDEO_INSTANCIA , VT.COD_INSTANCIA_GLOBAL FROM video_instancia VT WHERE T.COD_INSTANCIA_GLOBAL=" . $codInstanciaGlobal . " ORDER BY DESC_VIDEO_INSTANCIA";
  else
    $strSQL = "SELECT VT.COD_VIDEO, VT.DESC_VIDEO_INSTANCIA , VT.COD_INSTANCIA_GLOBAL FROM video_instancia VT  WHERE ORDER BY VT.COD_INSTANCIA_GLOBAL,DESC_VIDEO_INSTANCIA";
	
  return mysql_query($strSQL);
}

//======================================================================================================
// tools/video_envio.asp
// Grava imformações no Banco

// ATENCAO - isso esta funcionando???

function videoAulasAltera($ARQ, $INSTGLOBAL, $PESSOA, $DESC, $DESCINST, $CAMINHO, $CAMINHOHTTP, $TAM, $TIPO)
{
  $strSQL = "UPDATE video SET " .
    " CAMINHO_LOCAL_VIDEO = " .quote_smart( $CAMINHO ). ", " .
    " TIPO_VIDEO  = " .quote_smart( $TIPO ). ", " .
    " DESC_VIDEO  = " .quote_smart( $DESC ). ", " .
    " CAMINHO_HTTP_VIDEO   = " .quote_smart ($CAMINHOHTTP) . " " .
    " WHERE COD_VIDEO  = " . $ARQ ;
			  
  mysql_query($strSQL);  
	
  if (! mysql_errno())
    {
      $strSQL = "DELETE FROM video_instancia WHERE COD_VIDEO = " . $ARQ;
      mysql_query($strSQL);  

      if (! mysql_errno())
	{
	  $strSQL = "INSERT INTO video_instancia (COD_VIDEO, COD_INSTANCIA_GLOBAL,  DESC_VIDEO_INSTANCIA) " .
	    " VALUES (". $ARQ .",". $INSTGLOBAL . ",". quote_smart($DESCINST ). ")";
	  mysql_query($strSQL);  
			
	  return (! mysql_errno());
	}
      else
	return false;
    }
  else
    return false;
}

//======================================================================================================
// tools/video_envio.asp; tools/video.asp;
// Apaga imformações no Banco

function videoAulasApaga($ARQ)
{
  $strSQL = "DELETE FROM video WHERE COD_VIDEO = " . $ARQ;
	
  if (! mysql_errno())
    {
      $strSQL = "DELETE FROM video_instancia WHERE COD_VIDEO = " . $ARQ;		
      mysql_query($strSQL);  
			
      return (! mysql_errno());
    }
  else
    return false;
}


//======================================================================================================
// tools/video_envio.asp
// Grava informações no Banco

function videoAulasInsere($INST, $PESSOA, $DESC, $DESCINST, $CAMINHO, $CAMINHOHTTP, $TAM, $TIPO)
{
  $strSQL = "SELECT COD_VIDEO FROM video ORDER BY COD_VIDEO DESC LIMIT 0, 1 ";

  $rsCon = mysql_query($strSQL);  
  $linha = mysql_fetch_array($rsCon);
	
  $ARQ = $linha["COD_VIDEO"] + 1;

  $strSQL = "INSERT INTO video (COD_VIDEO, CAMINHO_LOCAL_VIDEO, CAMINHO_HTTP_VIDEO , TIPO_VIDEO, DESC_VIDEO)" .
    " VALUES (". $ARQ .", " .quote_smart( $CAMINHO ). ",".quote_smart($CAMINHOHTTP ). ", " . $TIPO . ", ".quote_smart($DESC) . ") ";
	
  mysql_query($strSQL);  
			  
  if (! mysql_errno())
    {
      $strSQL = "INSERT INTO video_instancia (COD_VIDEO, COD_INSTANCIA_GLOBAL, DESC_VIDEO_INSTANCIA) " .
	" VALUES (". $ARQ .",". $INST . ",". quote_smart($DESCINT) . ")";
      mysql_query($strSQL); 
			  
      if (mysql_errno())
	{
	  mysql_query("DELETE FROM video WHERE COD_VIDEO = " . $ARQ); 
	  return false;
	}
      else
	return true;
    }
  else
    return false;
}

//======================================================================================================
// chat/index.asp; chat/redireciona.asp; chat/video.asp;

function videoChat($acao, $valor)
{
  if ($acao == "consultar") 
    {
      $strSQL = "SELECT * FROM chat_camera WHERE COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"];
      return mysql_query($strSQL);
    }
	 
  if ($acao == "atualizar")
    {
      if ($valor == 0) 
	{
	  $strSQL = "UPDATE chat_camera SET EM_USO = '0' WHERE COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"];
	  mysql_query($strSQL);
	  return true;
	}
      else
	{
	  $strSQL = "UPDATE chat_camera SET EM_USO = '1' WHERE COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"];
	  mysql_query($strSQL);
	  return true;
	}	
    }
}

function listaVideosAdm($codInstanciaGlobal, $local, $quem)
{
  $strSQL = "SELECT DISTINCT V.DESC_VIDEO, V.COD_VIDEO";

  if ($local == "instanciaAtual")
    {
      $strSQL .= " FROM video V, video_instancia VT WHERE V.COD_VIDEO = VT.COD_VIDEO";
		
      if ($codInstanciaGlobal != "")
	$strSQL .= " AND VT.COD_INSTANCIA_GLOBAL = '" . $codInstanciaGlobal . "'";
    }

  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT VT.COD_VIDEO FROM video_instancia VT");
		
      $strSQL .= " FROM video V WHERE V.COD_VIDEO NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_VIDEO"] . ",";
		
      $strSQL .= "0)";
    }

  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT VT.COD_VIDEO FROM video_instancia VT");
		
      $strSQL .= " FROM video V WHERE V.COD_VIDEO IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_VIDEO"] . ",";
		
      $strSQL .= "0)";	
    }

  if ($local == "")
    $strSQL .= " FROM video V";

  if ($quem != "")
    $strSQL = "Select * FROM video V WHERE V.COD_PESSOA = '". $quem ."'";
		
  $strSQL .= " ORDER BY V.COD_VIDEO";

  return mysql_query($strSQL);					
}

//======================================================================================================

function VideoVerificaAcesso($cod_video)
{
  $permite = false;
		
  if ($_SESSION["NIVEL_ACESSO_FUTURO"] == 1)
    $permite = true;
	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
    {
      // Verifica se a noticia é dele
		
      $strSQL = "SELECT COD_VIDEO FROM video".
	" WHERE COD_VIDEO = '". $cod_video ."'".
	" AND COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."'";
				  
      $rsCon = mysql_query($strSQL);
		
      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }

	
  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 or $_SESSION["NIVEL_ACESSO_FUTURO"] == 3 ) and (! $permite) )
    {
      // Verifica se a noticia é de alguma turma na qual ele é professor
			$strSQL = "SELECT VI.COD_ARQUIVO FROM video_instancia VI WHERE VI.COD_VIDEO=".quote_smart($cod_video).
					" AND VI.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"];

      $rsCon = mysql_query($strSQL);

      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }

  if ( ( $_SESSION["NIVEL_ACESSO_FUTURO"] == 2 ) and (! $permite) )
    {
      // Verifica se a noticia é da alguma turma cujo curso ele é adm
      $strSQL = "SELECT VT.COD_VIDEO FROM VIDEO_TURMA VT, TURMA T, DISCIPLINA D, ADMINISTRADOR_CURSO AC".
	" WHERE VT.COD_VIDEO = '". $cod_video ."'".
	" AND AC.COD_ADM = '". $_SESSION["COD_ADM"] ."' AND AC.COD_CURSO = D.COD_CURSO".
	" AND D.COD_DIS = T.COD_DIS AND T.COD_TURMA = VT.COD_TURMA";
				  
      $rsCon = mysql_query($strSQL);

      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }
	
  return $permite;
}

//======================================================================================================
// Retorna um ResultSet com os videos

function listaVideos($cod_video, $codInstanciaGlobal, $acesso)
{


  if ($codInstanciaGlobal != "")
    {	
      $strSQL = "SELECT V.DESC_VIDEO, V.CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO, V.CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO, V.DOWNLOAD, V.COD_VIDEO, VT.COD_TIPO_ACESSO, VT.DESC_VIDEO_INSTANCIA" . 		
	" FROM video V, video_instancia VT" .
	" WHERE V.COD_VIDEO = VT.COD_VIDEO" .
	" AND VT.COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal.
	" AND VT.COD_VIDEO = " . $cod_video;

      if ($cod_video != "")
	$strSQL .=  " AND V.COD_VIDEO =" . $cod_video;
      else
	{		
	  if ($acesso != "")
	    $strSQL .= " AND (VT.COD_TIPO_ACESSO =" . $acesso . " OR VT.COD_TIPO_ACESSO=3 )";	
	}
				
      $strSQL .= " ORDER BY V.COD_VIDEO DESC";
    }
  else
    {
      $strSQL = "SELECT V.DESC_VIDEO, V.CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO, V.CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO, V.COD_VIDEO, V.DOWNLOAD".
	" FROM video V" .
	" WHERE V.COD_VIDEO = " . $cod_video;	
    }


  return mysql_query($strSQL);					
}

//===================================================================================================

function VideoAltera($cod_video, $desc_video, $cam_http, $cam_http_discada, $download)
{
  $strSQL = "UPDATE video SET " .
            "DESC_VIDEO = ".quote_smart($desc_video) . ", " .
            "CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO = ".quote_smart($cam_http ). ", " .
			"CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO = ".quote_smart($cam_http_discada ). ", " .
            "DOWNLOAD = ".quote_smart($download).",".
            "COD_PESSOA = " . $_SESSION["COD_PESSOA"] .
            " WHERE COD_VIDEO = " . $cod_video; 
	
  mysql_query($strSQL);
				 
  return (! mysql_errno());
}

//======================================================================================================

function VideoInsere($desc_video, $cam_http, $cam_http_discada, $download)
{
  $strSQL = "INSERT INTO video (COD_PESSOA, DESC_VIDEO,CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO,CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO,DOWNLOAD) " .
            " VALUES (". $_SESSION["COD_PESSOA"] .",".quote_smart($desc_video) . ",".quote_smart($cam_http).",".quote_smart($cam_http_discada).",".$download.")";
   mysql_query($strSQL);
  return (! mysql_errno());
}

//======================================================================================================

function VideoExclue($cod_video)
{
  $strSQL = "DELETE FROM video WHERE COD_VIDEO = " . $cod_video;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function VideoCodigo($desc_video, $cam_http, $cam_http_discada)
{
  $strSQL = "SELECT COD_VIDEO FROM video WHERE".
    " COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."' AND".
    " DESC_VIDEO = ".quote_smart($desc_video) ." AND".
    " CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO = ".quote_smart($cam_http)." AND".
	" CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO = ".quote_smart($cam_http_discada). 
	" ORDER BY COD_VIDEO DESC";
  $rsCon = mysql_query($strSQL);
	
  if ($rsCon and ( mysql_num_rows($rsCon) > 0))
    $linha = mysql_fetch_array($rsCon);
		
  return $linha["COD_VIDEO"];
}

//======================================================================================================

function VideoLocalInsere($cod_video, $inst, $acesso, $desc_video)
{
  $strSQL = "INSERT INTO video_instancia (COD_VIDEO, COD_INSTANCIA_GLOBAL, COD_TIPO_ACESSO , DESC_VIDEO_INSTANCIA) " .
    " VALUES (". $cod_video .",".quote_smart( $inst) .",".quote_smart( $acesso) .", ".quote_smart($desc_video) . " )";
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function VideoLocalRemove($cod_video, $inst, $acesso)
{
  $strSQL = "DELETE FROM video_instancia WHERE COD_VIDEO = ". $cod_video ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;
	 
  mysql_query($strSQL);
	
  return (! mysql_errno());
}


//======================================================================================================

function VideoLocalAltera($cod_video, $inst, $desc_video_instancia, $acesso, $tipo_acesso_novo)
{
  $strSQL = "UPDATE video_instancia SET DESC_VIDEO_INSTANCIA = ".quote_smart($desc_video_instancia) .", COD_TIPO_ACESSO=".$tipo_acesso_novo .
    " WHERE COD_VIDEO = ". $cod_video ." AND COD_TIPO_ACESSO = ". $acesso ." AND COD_INSTANCIA_GLOBAL=". $inst;

  mysql_query($strSQL);	
	
  return (! mysql_errno());
}


//======================================================================================================

/*function listaVideosLocal($cod_video)
{
  $strSQL = "SELECT DISCIPLINA.COD_CURSO, VIDEO_TURMA.COD_TURMA, TURMA.COD_DIS, COD_TIPO_ACESSO AS ACESSO, DESC_VIDEO_TURMA FROM VIDEO_TURMA , TURMA, DISCIPLINA " .
    " WHERE DISCIPLINA.COD_DIS = TURMA.COD_DIS AND TURMA.COD_TURMA = VIDEO_TURMA.COD_TURMA AND COD_VIDEO = ". $cod_video .
    " ORDER BY VIDEO_TURMA.COD_TURMA, COD_TIPO_ACESSO";
	
  //	if ($cod_noticia == "")	
  //		$strSQL = "SELECT NULL AS COD_TURMA, NULL AS COD_DIS, NULL AS COD_CURSO, NULL AS ACESSO, NULL AS COL, NULL AS LIN FROM NOTICIA WHERE 0";

  return mysql_query($strSQL);					
}*/

//lista os locais em q determinado video esta publicado
function listaVideosLocal($codVideo) {
	return listaLocal("video_instancia","COD_VIDEO",$codVideo,"DESC_VIDEO_INSTANCIA");
}

//======================================================================================================
// aulas/index.asp
// Retorna os videos disponiveis para uma turma

function videoAulas($acesso)
{
	
  $strSQL = "SELECT V.COD_PESSOA, V.COD_VIDEO, V.DESC_VIDEO,V.CAMINHO_HTTP_VIDEO_ALTA_RESOLUCAO,V. CAMINHO_HTTP_VIDEO_BAIXA_RESOLUCAO, V.DOWNLOAD,VT.DESC_VIDEO_INSTANCIA, VT.COD_TIPO_ACESSO  FROM video V, video_instancia VT WHERE VT.COD_INSTANCIA_GLOBAL = " .  $_SESSION["codInstanciaGlobal"] . " AND V.COD_VIDEO = VT.COD_VIDEO AND (VT.COD_TIPO_ACESSO =" . $acesso . " OR VT.COD_TIPO_ACESSO=3 ) GROUP BY COD_VIDEO ORDER BY VT.DESC_VIDEO_INSTANCIA ";

  return mysql_query($strSQL);	
}
 
//======================================================================================================
// tools/video_operacao.asp;
// aulas/download.asp; aulas/redireciona.asp; aulas/topo.asp; aulas/video.asp
// Retorna o caminho para um determinado cod_video
//tirei join com turma e disciplina
function videoCaminho($cod_video,$resolucao)
{	global $caminho;

  $strSQL = "SELECT V.COD_VIDEO, V.{$caminho[$resolucao]}, V.DESC_VIDEO, VT.COD_TIPO_ACESSO, VT.COD_INSTANCIA_GLOBAL, VT.DESC_VIDEO_INSTANCIA ".
    " FROM video V, video_instancia VT ".
    " WHERE V.COD_VIDEO = VT.COD_VIDEO ".
    " AND V.COD_VIDEO = " .quote_smart( $cod_video);
			  
  if ($cod_video == "")
    $strSQL = "SELECT * FROM video WHERE 0";
	

  return mysql_query($strSQL);	
}

//======================================================================================================
function videoCaminho2($cod_video,$resolucao)
{	global $caminho;
  $strSQL = "SELECT V.".$caminho[$resolucao].
            " FROM video V".
			      " WHERE V.COD_VIDEO= ".quote_smart( $cod_video);

 return mysql_query($strSQL);	
}
//======================================================================================================
function getDescricaoVideo($codVideo) {
  $result= mysql_query("Select DESC_VIDEO from video Where COD_VIDEO=".quote_smart($codVideo));
  
  $linha = mysql_fetch_assoc($result);
  return $linha['DESC_VIDEO'];
}  


?>