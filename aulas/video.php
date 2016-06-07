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



//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/videos.inc.php");
session_name(SESSION_NAME); session_start(); security();
global $caminho;

?>

<html>
	<head>
		<title>V�deo-aula</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body>

<p align="center"> 

<?php
	
	
	if ( ($_SESSION["codInstanciaGlobal"] == "") OR ($_REQUEST["COD_VIDEO"] == "") )
	{
		echo "Acesso Negado";
		exit();
	 }
	
		$rsCon   = videoCaminho($_REQUEST["COD_VIDEO"],$_REQUEST["RESOLUCAO"]);
		
    		if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) )
			exit();

		$linha = mysql_fetch_array($rsCon);

		if ( ($_SESSION["COD_PESSOA"] != "") and ($linha["COD_TIPO_ACESSO"] == 1) )
			exit();

			
	
		if ( $linha[$caminho[$_REQUEST["RESOLUCAO"]]] != "")
		{ 
				$_SESSION["VIZUALIZA"] = "1";
				$rsCon = videoCaminho($_REQUEST["COD_VIDEO"],$_REQUEST["RESOLUCAO"]);
				$video = mysql_fetch_array($rsCon);

				
				//echo "<a href=\"redireciona.php?RESOLUCAO=". $_REQUEST["RESOLUCAO"] ."&COD_VIDEO=" . $_REQUEST["COD_VIDEO"] . "\">video</a>";
		/*		echo "<object id=\"Video Aula\" classid=\"CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6\"";
				//echo "		codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715\"";
				echo "		standby=\"Carregando os componentes do Microsoft Windows Media Player ...\"";
				echo "		type=\"application/x-oleobject\">";
				//echo "	<param name=\"src\" value=\"https://www.ead.ufrgs.br/navi/aulas/redireciona.php?RESOLUCAO=". $_REQUEST["RESOLUCAO"] ."&COD_VIDEO=" . $_REQUEST["COD_VIDEO"] . "\">";
				echo "	<param name=\"URL\" value=\"". $cam_httpAuxiliar . $video[$caminho[$_REQUEST["RESOLUCAO"]]]. "\">";
				echo "	<param name=\"controls\"      value=\"All\">";
				echo "	<param name=\"autostart\"     value=\"true\">";
				echo "    <param name=\"ShowStatusBar\" value=\"1\">";
				echo "</object>";*/
				
			echo  '<object id="WMPlay" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" '.
      				'			codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"'.
      				'			standby="Carregando o componente do Microsoft Windows Media Player..."'.
      				'			type="application/x-oleobject">'.
      				'<param name="autoStart" value="True">'.
      				'<param name="FileName" value="'.$cam_httpAuxiliar . $video[$caminho[$_REQUEST["RESOLUCAO"]]].'">'.
      				'<param name="ShowAudioControls" value="1">'.
      				'<param name="ShowCaptioning" value="0">'.
      				'<param name="ShowControls" value="1">'.
      				'<param name="ShowDisplay" value="0">'.
      				'<param name="ShowGotoBar" value="0">'.
      				'<param name="ShowPositionControls" value="0">'.
      				'<param name="ShowStatusBar" value="1">'.
      				'<param name="ShowTracker" value="1">'.
      				'<embed type="application/x-mplayer2"'.
      				'			pluginspage="http://www.microsoft.com/isapi/redir.dll?prd=windows&sbp=mediaplayer&ar=media&sba=plugin"'.
      				'			filename="'. $cam_httpAuxiliar . $video[$caminho[$_REQUEST["RESOLUCAO"]]]. '"'.
      				'			ShowAudioControls="1"'.
      				'			ShowCaptioning="0"'.
      				'			ShowControls="1"'.
      				'			ShowDisplay="0"'.
      				'			ShowGotoBar="0"'.
      				'			ShowPositionControls="0"'.
      				'			ShowStatusBar="1"'.
      				'			ShowTracker="1"'.
      				'			id="WMPlay"'.
      				'			></embed>'.
      			  '</object>';
		}
	?>	
</p>
</body>
</html>
